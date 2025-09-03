<?php

namespace App\Console\Commands;

use App\Models\Calls;
use Cache;
use Carbon\Carbon;
use Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportCallsCsv extends Command
{
    private $accessToken = false;

    // O nome do comando que você usará no terminal
    protected $signature = 'import:calls {file}';

    // A descrição do comando
    protected $description = 'Importa os registros de chamadas de um arquivo CSV.';
    /**
     * Converte uma duração ISO 8601 (ex: PT1M24.142719S) para HH:MM:SS.
     */
    function getFilePath() : string {
        return $this->argument('file');
    }
    function convertDurationToTime(string $duration): string
    {
        // Remove os milissegundos e o ponto decimal
        $cleanDuration = preg_replace('/\.\d+S$/', 'S', $duration);

        try {
            $interval = new \DateInterval($cleanDuration);
        } catch (\Exception $e) {
            return '00:00:00';
        }
        
        return sprintf(
            '%02d:%02d:%02d',
            ($interval->d * 24) + $interval->h,
            $interval->i,
            $interval->s
        );
    }
    private function getToken() {
        $this->accessToken = Cache::get('3cx_access_token') ?? false;
        if (!$this->accessToken) {
            // 1. Login para pegar o token
            $loginResponse = Http::post('https://srv3cxsatitelecom.my3cx.com.br:5001/webclient/api/Login/GetAccessToken', [
                'Username'          => '1111',
                'Password'          => 'Sati12345@',
                'ReCaptchaResponse' => null,
                'SecurityCode'      => null,
            ]);
            if ($loginResponse->failed()) {
                throw new \Exception('Falha no login: '.$loginResponse->body());
            }


            $this->accessToken = urlencode($loginResponse->json('Token.access_token')); // ou veja o nome exato no retorno
            $expiresIn = $loginResponse->json('Token.expires_in');
            Cache::put('3cx_access_token', $this->accessToken, now()->addSeconds($expiresIn - 60));
        }
    }

    private function getArchiveCsv() {
        $periodTo = urlencode(Carbon::now()->addDay()->startOfDay()->setTimezone('UTC')->toIso8601String());
        $periodFrom = Carbon::now()->subDays(365)->startOfDay()->setTimezone('UTC')->toIso8601String();

        $reportUrl = "https://srv3cxsatitelecom.my3cx.com.br:5001/xapi/v1/ReportCallLogData/Pbx.GetCallLogData(periodFrom=$periodFrom,periodTo=$periodTo,sourceType=0,sourceFilter='',destinationType=0,destinationFilter='',callsType=0,callTimeFilterType=0,callTimeFilterFrom='0%3A00%3A0',callTimeFilterTo='0%3A00%3A0',hidePcalls=true)";
        $reportResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->get($reportUrl);

        return $reportResponse->json('value');
    }

    private function createCsv($csvContent) {
        $headers = ['Call Time', 'From', 'To', 'Direction', 'Status', '', 'Talking'];

        $stream = fopen('php://temp', 'r+');
        fputcsv($stream, $headers);

        foreach ($csvContent as $call) {
            $mappedData = [
                Carbon::parse($call['StartTime'])->format('Y-m-d H:i:s'),
                $call['SourceDisplayName'],
                $call['DestinationDisplayName'],
                $call['Direction'],
                $call['Status'],
                '', // Coluna em branco
                $this->convertDurationToTime($call['TalkingDuration'])
            ];

            fputcsv($stream, $mappedData);
        }
        // 4. Salvar o conteúdo do stream para um arquivo no disco
        rewind($stream); // Volta o ponteiro para o início
        $csvContent = stream_get_contents($stream);
        fclose($stream);
        // Salvar o conteúdo em um arquivo local
        Storage::disk('local')->put($this->getFilePath(), $csvContent);
    }

    private function save() {
        $csvPath = Storage::disk('local')->path($this->getFilePath());
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // Assumindo que a primeira linha é o cabeçalho

        $records = $csv->getRecords();
        foreach ($records as $record) {
            if (isset($record['Call Time']) && $record['Call Time'] === 'Totals') {
                continue;
            }
            Calls::firstOrCreate([
                'call_date' => $record['Call Time'],
                'from' => $record['From'],
                'to' => $record['To'],
                'direction' => $record['Direction'],
                'status' => $record['Status'],
                'call_time' => $record['Talking'],
            ]);
        }
    }

    public function handle()
    {
        $this->getToken();
        
        $csvContent = $this->getArchiveCsv();

        $this->createCsv($csvContent);
        
        if (!Storage::disk('local')->exists($this->getFilePath())) {
            $this->error("O arquivo não foi encontrado em: {$this->getFilePath()}");
            return self::FAILURE;
        }

        $this->save();

        $this->info('Registros de chamadas importados com sucesso!');
        
        Storage::disk('local')->delete($this->getFilePath());
        return self::SUCCESS;
    }
}