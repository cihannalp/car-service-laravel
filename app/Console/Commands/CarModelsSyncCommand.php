<?php

namespace App\Console\Commands;

use App\Models\CarModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CarModelsSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:car-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves car models from https://static.novassets.com/automobile.json and syncs to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $start = now();

        $url = 'https://static.novassets.com/automobile.json';
        
        $this->info('Getting data from the '.$url);

        $result = $this->getCarModelsDataFromUrl($url);

        $this->info('Data received. Starting to insert to database...');
        
        $result = json_decode($result, true);

        $bar = $this->output->createProgressBar(count($result["RECORDS"]));
        $bar->start();
        
        foreach ($result["RECORDS"] as $carModel) {
            CarModel::updateOrCreate($carModel);

            $bar->advance();
        }
         
        $bar->finish();

        $time = $start->diffInSeconds(now());
        $this->line("");
        $this->line("");
        $this->info('Completed. Processed in '.$time.' seconds');

        Cache::forget('carModels');
    }

    public function getCarModelsDataFromUrl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15"));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result= curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
