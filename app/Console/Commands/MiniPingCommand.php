<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MiniPingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mini:ping {--mode=ok}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $mode = $this->option('mode');

        $payload = [
                'lead' => [
                    'email' => 'john@example.com',
                    'phone' => '5551112233'
                ],
                'send' => [
                    "campaign"=> 'TEST',
                    "country"=> 'mx'
                ]
            ];

        $response = Http::timeout(10)
            ->withoutVerifying()
            ->asJson()
            ->withHeader('X-trace', 'mini')
            ->get(url("http://localhost:8000/fake-partner?mode={$mode}"));


        if($response->status() === 204){
            $state = 'invalid';
        }else {
            $status = $response->json('result.status');

            if ($status === 'accepted') {
                $state = 'valid';
                $payload['send']['sessionId'] = $response->json('result.token');
            }elseif($status === 'duplicate'){
                $state = 'duplicate';
            }else{
                $state = 'invalid';
            }
        }


        $this->line("STATE: {$state}");
        $this->line(json_encode($payload, JSON_PRETTY_PRINT));

    }
}
