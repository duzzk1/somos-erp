<?php

namespace App\Jobs;

use Artisan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Storage;


class ImportarChamadasCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;
    private $username = '';
    private $password = '';
    private $accessToken = null;
    public function __construct($username = '', $password = '')
    {
        $this->username = $username;
        $this->password = $password;
    }
    public static function handle()
    {
        Log::info(message: "Importação de chamadas iniciada com sucesso.");
    
        Artisan::call('import:calls', [
            'file' => 'calls/calls.csv'
        ]);

        Log::info("Importação de chamadas concluída com sucesso.");
    }
}