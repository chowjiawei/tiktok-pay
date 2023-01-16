<?php

namespace Chowjiawei\TikTokPay\Providers;

use Chowjiawei\TikTokPay\Facade\TikTokPay;
use Illuminate\Support\ServiceProvider;

class TikTokPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('TikTokPay', function () {
            return new TikTokPay();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../Config/tiktok-pay.php' => config_path('tiktok-pay.php'),
        ]);
    }
}
