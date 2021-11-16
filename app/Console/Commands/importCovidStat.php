<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class importCovidStat extends Command
{
    protected $signature = 'import:covid_stat';

    protected $description = 'Imports Covid statistics from API to DB';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $countries = Country::all();
        $delay = Config::get('statistic.details.job_execution_time') / count($countries);
        $delay = min($delay, Config::get('statistic.details.maximum_delay_per_req'));

        $url = Config::get('statistic.details.api_uri');
        foreach ($countries AS $country) {
            $time_start = microtime(true);
            $client = new \GuzzleHttp\Client([
                // SSL Certificate problem with devtest.ge
                'verify' => false
            ]);
            $request = $client->post($url, [
                'form_params' => [
                    'code'  => Arr::get($country, 'code')
                ]
            ]);
            if ($request->getStatusCode() == 200) {
                $response = json_decode($request->getBody(), 1);
                DB::table('statistics')->upsert([
                    'country_id'    => Arr::get($country, 'id'),
                    'confirmed'     => Arr::get($response, 'confirmed'),
                    'recovered'     => Arr::get($response, 'recovered'),
                    'death'        => Arr::get($response, 'deaths'),
                ], ['country_id', 'update_date']);
            }

            $time_end = microtime(true);
            //for splitting requests into equal intervals during an hour
            sleep(round($delay - ($time_end - $time_start)));
        }
    }
}
