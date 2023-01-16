<?php

namespace Chowjiawei\TikTokPay\Facade;

use Carbon\Carbon;
use Chowjiawei\TikTokPay\Exception\TikTokPayException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Facade as LaravelFacade;

class TikTokPay extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {
        return 'TikTokPay';
    }

    public function getConfig()
    {
        return config('tiktok-pay');
    }

    public function __construct()
    {
        $config = $this->getConfig();
        if (
            !isset($config['tiktok']) || !isset($config['token']) || !isset($config['salt']) || !isset($config['app_id'])
            || !isset($config['secret']) || !isset($config['notify_url']) || !isset($config['private_key_url']) || !isset($config['platform_public_key_url'])
            || !isset($config['public_key_url']) || !isset($config['version']) || !isset($config['settle_notify_url']) || !isset($config['agree_refund_notify_url'])
            || !isset($config['create_order_callback'])   || !isset($config['pay_callback'])
        ) {
            throw new TikTokPayException('必要配置缺失,请检查 tiktok-pay.php 文件后再试.');
        }
    }

    public function makeSign($method, $url, $body, $timestamp, $nonce_str)
    {
        $config = $this->getConfig();
        $text = $method . "\n" . $url . "\n" . $timestamp . "\n" . $nonce_str . "\n" . $body . "\n";
        $priKey = file_get_contents($config['private_key_url']);
        $privateKey = openssl_get_privatekey($priKey, '');
        openssl_sign($text, $sign, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }

    //查询订单
    public function query(string $trackNumber)
    {
        $config = $this->getConfig();

        $order = ['out_order_no' => $trackNumber];

        $timestamp = Carbon::now()->timestamp;
        $str = substr(md5($timestamp), 5, 15);
        $body = json_encode($order);
        $sign = $this->makeSign('POST', '/api/apps/trade/v2/query_order', $body, $timestamp, $str);
        $client = new Client();
        $url = 'https://developer.toutiao.com/api/apps/trade/v2/query_order';
        $response = $client->post($url, [
            'json' => $order ,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Byte-Authorization' => 'SHA256-RSA2048 appid="' . $config['app_id'] . '",nonce_str=' . $str . ',timestamp="' . $timestamp . '",key_version="' . $config["version"] . '",signature="' . $sign . '"'
            ]]);
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['err_no'] == 0) {
            return $data['data'];
        }
        return [];
    }
}
//if (!function_exists('a')) {
//}
