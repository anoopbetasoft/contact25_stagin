<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\currencies;
use Log;


class currency_rate_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron job for updating currency rates';

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
     * @return mixed
     */
    public function handle()
    {
       /* $endpoint = 'latest';
        $access_key = '680f44fe00fd5b001cd3d2d52fa3c15e';*/
        $currency = currencies::where('code','!=','ALL')->get();
       /* $from = 'GBP';
        $amount = '1';
        Log::info('Currency:'.$currency);
        $ch = curl_init('http://data.fixer.io/api/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&amount='.$amount.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json = curl_exec($ch);
        curl_close($ch);

        $exchangeRates = json_decode($json, true);
        Log::info('Rates:'.$exchangeRates['rates']);*/
        foreach($currency as $currencyname)
        {
            Log::infO('Currency Code:'.$currencyname->code);
            $ch = curl_init('https://api.exchangeratesapi.io/latest?base=GBP');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($ch);
            curl_close($ch);
            $exchangeRates = json_decode($json, true);
           currencies::where('currency_id',$currencyname->currency_id)->update(['rates'=>$exchangeRates['rates'][$currencyname->code]]);
         /*  Log::info('Currency Id:'.$currencyname->currency_id);
           Log::info('Currency Name:'.$currencyname->code);
           Log::info('currency name:'.$exchangeRates['rates']['GBP']);*/
        }

    }
}
