<?php

namespace Chowjiawei\TikTokPay\Providers;

use Chowjiawei\TikTokPay\Facade\TikTokPayIndustryTrading;
use Chowjiawei\TikTokPay\Facade\TikTokPayIndustryTradingOld;
use Chowjiawei\TikTokPay\Facade\TikTokPayLifeService;
use Illuminate\Support\ServiceProvider;

class TikTokPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('TikTokPayIndustryTradingOld', function () {
            return new TikTokPayIndustryTradingOld();
        });
        $this->app->bind('TikTokPayIndustryTrading', function () {
            return new TikTokPayIndustryTrading();
        });
        $this->app->bind('TikTokPayLifeService', function () {
            return new TikTokPayLifeService();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../Config/tiktok-pay.php' => config_path('tiktok-pay.php'),
        ]);
    }
}
