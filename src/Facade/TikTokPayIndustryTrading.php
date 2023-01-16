<?php

namespace Chowjiawei\TikTokPay\Facade;

use Carbon\Carbon;
use Chowjiawei\TikTokPay\Exception\TikTokPayException;
use Chowjiawei\TikTokPay\Services\TikTokIndustryTradingOldService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade as LaravelFacade;

class TikTokPayIndustryTrading extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {
        return 'TikTokPayIndustryTrading';
    }

    public function getConfig()
    {
        return config('tiktok-pay');
    }

}
