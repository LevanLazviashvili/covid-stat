<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class importCountries extends Command
{
    protected $signature = 'import:countries';

    protected $description = 'Imports countries from API to DB';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new \GuzzleHttp\Client([
            // SSL Certificate problem with devtest.ge
            'verify' => false
        ]);

        $request = $client->get(Config::get('statistic.countries.api_uri'));

        $Data = [];

        foreach (json_decode($request->getBody(), 1) AS $item) {
            $Data[] = [
                'code'  => Arr::get($item, 'code'),
                'name'  => json_encode(Arr::get($item, 'name'), JSON_UNESCAPED_UNICODE)
            ];
        }
        DB::table('countries')->insertOrIgnore($Data);
        return self::SUCCESS;
    }
}
