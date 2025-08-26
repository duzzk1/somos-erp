<?php

namespace App\Console\Commands;

use App\Models\Calls;
use Illuminate\Console\Command;
use App\Models\CallRecord;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportCallsCsv extends Command
{
    // O nome do comando que você usará no terminal
    protected $signature = 'import:calls {file}';

    // A descrição do comando
    protected $description = 'Importa os registros de chamadas de um arquivo CSV.';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!Storage::disk('local')->exists($filePath)) {
            $this->error("O arquivo não foi encontrado em: {$filePath}");
            return self::FAILURE;
        }

        $csvPath = Storage::disk('local')->path($filePath);
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // Assumindo que a primeira linha é o cabeçalho

        $records = $csv->getRecords();
        foreach ($records as $record) {
            if (isset($record['Call Time']) && $record['Call Time'] === 'Totals') {
                continue;
            }
            Calls::create([
                'call_date' => $record['Call Time'],
                'from' => $record['From'],
                'to' => $record['To'],
                'direction' => $record['Direction'],
                'status' => $record['Status'],
                'call_time' => $record['Talking'],
            ]);
        }

        $this->info('Registros de chamadas importados com sucesso!');
        
        Storage::disk('local')->delete($filePath);
        return self::SUCCESS;
    }
}