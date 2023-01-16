<?php

namespace Chowjiawei\TikTokPay\Facade;

use Carbon\Carbon;
use Chowjiawei\TikTokPay\Exception\TikTokPayException;
use Chowjiawei\TikTokPay\Services\TikTokIndustryTradingOldService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade as LaravelFacade;

class TikTokPayIndustryTradingOld extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {
        return 'TikTokPayIndustryTradingOld';
    }

    public function getConfig()
    {
        return config('tiktok-pay');
    }

    public function __construct()
    {
        $config = $this->getConfig();
        if (
            !isset($config['industry-trading-old']) || !isset($config['industry-trading-old']['token']) || !isset($config['industry-trading-old']['salt']) || !isset($config['industry-trading-old']['app_id'])
            || !isset($config['industry-trading-old']['secret']) || !isset($config['industry-trading-old']['notify_url']) || !isset($config['industry-trading-old']['private_key_url']) || !isset($config['industry-trading-old']['platform_public_key_url'])
            || !isset($config['industry-trading-old']['public_key_url']) || !isset($config['industry-trading-old']['version']) || !isset($config['industry-trading-old']['settle_notify_url']) || !isset($config['industry-trading-old']['agree_refund_notify_url'])
            || !isset($config['industry-trading-old']['create_order_callback'])   || !isset($config['industry-trading-old']['pay_callback'])
        ) {
            throw new TikTokPayException('必要配置缺失,请检查 tiktok-pay.php 文件后再试.');
        }
    }

    public function makeSign($method, $url, $body, $timestamp, $nonceStr)
    {
        $config = $this->getConfig()['industry-trading-old'];
        $text = $method . "\n" . $url . "\n" . $timestamp . "\n" . $nonceStr . "\n" . $body . "\n";
        $priKey = file_get_contents($config['private_key_url']);
        $privateKey = openssl_get_privatekey($priKey, '');
        openssl_sign($text, $sign, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }

    //查询订单
    public function query(string $trackNumber)
    {
        $tikTokService = new TikTokIndustryTradingOldService();

        $config = $this->getConfig()['industry-trading-old'];

        $order = [
            'out_order_no' => $trackNumber
        ];

        $timestamp = Carbon::now()->timestamp;
        $str = substr(md5($timestamp), 5, 15);
        $body = json_encode($order);
        $sign = $this->makeSign($tikTokService->queryOrderMethod, $tikTokService->queryOrderUrl, $body, $timestamp, $str);
        $client = new Client();
        $url = $tikTokService->queryOrderFullUrl;
        $response = $client->post($url, [
            'json' => $order ,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Byte-Authorization' => 'SHA256-RSA2048 appid="' . $config['app_id'] . '",nonce_str=' . $str . ',timestamp="' . $timestamp . '",key_version="' . $config["version"] . '",signature="' . $sign . '"'
            ]]);
        return json_decode($response->getBody()->getContents(), true);
    }

    //发起退款(单个订单单个订单项)  发起后还需要审核 同意退款后才真正退款
    public function refund($trackNumber, $price, $itemOrderId)
    {
        $tikTokService = new TikTokIndustryTradingOldService();
        $config = $this->getConfig()['industry-trading-old'];

        $order = [
            'out_order_no' => $trackNumber,
            'out_refund_no' => $trackNumber,
            'order_entry_schema' => [
                'path' => 'pages/courseDetail/courseDetail',
                'params' => '{\"id\":\"96f8bbf8-57c6-4348-baf2-caffe18a9277\"}'
            ],
            "item_order_detail" => [
                [
                    "item_order_id" => $itemOrderId,
                    "refund_amount" => (int)$price
                ]
            ],
            'notify_url' => $config['refund_notify_url']
        ];

        $timestamp = Carbon::now()->timestamp;
        $str = substr(md5($timestamp), 5, 15);
        $body = json_encode($order);

        $sign = $this->makeSign($tikTokService->createRefundOrderMethod, $tikTokService->createRefundOrderUrl, $body, $timestamp, $str);
        $client = new Client();
        $url = $tikTokService->createRefundOrderFullUrl;
        $response = $client->post($url, [
            'json' => $order ,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Byte-Authorization' => 'SHA256-RSA2048 appid="' . $config['app_id'] . '",nonce_str=' . $str . ',timestamp="' . $timestamp . '",key_version="' . $config["version"] . '",signature="' . $sign . '"'
            ]]);
        return json_decode($response->getBody()->getContents(), true);
    }



    //发起退款(单个订单多个订单项)  发起后还需要审核 同意退款
    public function refundByItems($trackNumber, $item)
    {
//        $item= [
//            [
//                "item_order_id" => '',
//                "refund_amount" => (int)$price
//            ],
//            [
//                "item_order_id" => '',
//                "refund_amount" => (int)$price
//            ],
//        ];
        $tikTokService = new TikTokIndustryTradingOldService();
        $config = $this->getConfig()['industry-trading-old'];

        $order = [
            'out_order_no' => $trackNumber,
            'out_refund_no' => $trackNumber,
            'order_entry_schema' => [
                'path' => 'pages/courseDetail/courseDetail',
                'params' => '{\"id\":\"96f8bbf8-57c6-4348-baf2-caffe18a9277\"}'
            ],
            "item_order_detail" => $item,
            'notify_url' => $config['refund_notify_url']
        ];

        $timestamp = Carbon::now()->timestamp;
        $str = substr(md5($timestamp), 5, 15);
        $body = json_encode($order);

        $sign = $this->makeSign($tikTokService->createRefundOrderMethod, $tikTokService->createRefundOrderUrl, $body, $timestamp, $str);
        $client = new Client();
        $url = $tikTokService->createRefundOrderFullUrl;
        $response = $client->post($url, [
            'json' => $order ,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Byte-Authorization' => 'SHA256-RSA2048 appid="' . $config['app_id'] . '",nonce_str=' . $str . ',timestamp="' . $timestamp . '",key_version="' . $config["version"] . '",signature="' . $sign . '"'
            ]]);
        return json_decode($response->getBody()->getContents(), true);
    }


    //同意退款   钱在这里就会直接退回去
    public function agreeRefund($trackNumber)
    {
        $tikTokService = new TikTokIndustryTradingOldService();
        $config = $this->getConfig()['industry-trading-old'];

        $order = [
            'out_refund_no' => $trackNumber,
            'refund_audit_status' => 1,
        ];
        $timestamp = Carbon::now()->timestamp;
        $str = substr(md5($timestamp), 5, 15);
        $body = json_encode($order);

        $sign = $this->makeSign($tikTokService->agreeRefundOrderMethod, $tikTokService->agreeRefundOrderUrl, $body, $timestamp, $str);
        $client = new Client();
        $url = $tikTokService->agreeRefundOrderFullUrl;
        $response = $client->post($url, [
            'json' => $order ,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Byte-Authorization' => 'SHA256-RSA2048 appid="' . $config['app_id'] . '",nonce_str=' . $str . ',timestamp="' . $timestamp . '",key_version="' . $config["version"] . '",signature="' . $sign . '"'
            ]]);
        return json_decode($response->getBody()->getContents(), true);
    }


    //查询退款
    public function queryRefund($trackNumber)
    {
        $tikTokService = new TikTokIndustryTradingOldService();
        $config = $this->getConfig()['industry-trading-old'];

        $order = [
            'out_refund_no' => $trackNumber,
        ];
        $timestamp = Carbon::now()->timestamp;
        $str = substr(md5($timestamp), 5, 15);
        $body = json_encode($order);

        $sign = $this->makeSign($tikTokService->queryRefundOrderMethod, $tikTokService->queryRefundOrderUrl, $body, $timestamp, $str);
        $client = new Client();
        $url = $tikTokService->queryRefundOrderFullUrl;
        $response = $client->post($url, [
            'json' => $order ,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Byte-Authorization' => 'SHA256-RSA2048 appid="' . $config['app_id'] . '",nonce_str=' . $str . ',timestamp="' . $timestamp . '",key_version="' . $config["version"] . '",signature="' . $sign . '"'
            ]]);
        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }


    //设置回调信息  调用一次即可
    public function setCallBackConfig(array $settingData = [])
    {
        $tikTokService = new TikTokIndustryTradingOldService();
        $config = $this->getConfig()['industry-trading-old'];
        if (empty($settingData)) {
            $settingData = [
                'create_order_callback' => $config['create_order_callback'],
                'refund_callback' => $config['refund_notify_url'],
                'pay_callback' => $config['pay_callback'],
            ];
        }
        $timestamp = Carbon::now()->timestamp;
        $str = substr(md5($timestamp), 5, 15);
        $body = json_encode($settingData);
        $sign = $this->makeSign($tikTokService->setCallBackConfigMethod, $tikTokService->setCallBackConfigUrl, $body, $timestamp, $str);
        $client = new Client();
        $url = $tikTokService->setCallBackConfigFullUrl;
        $response = $client->post($url, [
            'json' => $settingData ,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Byte-Authorization' => 'SHA256-RSA2048 appid="' . $config['app_id'] . '",nonce_str=' . $str . ',timestamp="' . $timestamp . '",key_version="' . $config["version"] . '",signature="' . $sign . '"'
            ]]);
        return json_decode($response->getBody()->getContents(), true);
    }

    //查询当前回调设置
    public function getSettingReturn()
    {
        $tikTokService = new TikTokIndustryTradingOldService();
        $config = $this->getConfig()['industry-trading-old'];
        $order = [];
        $timestamp = Carbon::now()->timestamp;
        $str = substr(md5($timestamp), 5, 15);
        $body = json_encode($order);
        $sign = $this->makeSign($tikTokService->queryCallBackConfigMethod, $tikTokService->queryCallBackConfigUrl, $body, $timestamp, $str);
        $client = new Client();
        $url = $tikTokService->queryCallBackConfigFullUrl;
        $response = $client->post($url, [
            'json' => $order ,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Byte-Authorization' => 'SHA256-RSA2048 appid="' . $config['app_id'] . '",nonce_str=' . $str . ',timestamp="' . $timestamp . '",key_version="' . $config["version"] . '",signature="' . $sign . '"'
            ]]);
        return json_decode($response->getBody()->getContents(), true);
    }


    //发起分账
    public function createSettle($trackNumber, $desc)
    {
//        $trackNumber  分账的时候 财务写  这是分账的自定义id   $desc 分账描述
        $tikTokService = new TikTokIndustryTradingOldService();
        $config = $this->getConfig()['industry-trading-old'];

        $order = [
            'out_order_no' => $trackNumber,
            'out_settle_no' => $trackNumber,
            'settle_desc' => $desc,
//            'settle_params'=>"[{\"merchant_uid\":\"71034295218686712630\",\"amount\":".$amount."}]",
            'notify_url' => $config['settle_notify_url']
        ];

        $timestamp = Carbon::now()->timestamp;
        $str = substr(md5($timestamp), 5, 15);
        $body = json_encode($order);

        $sign = $this->makeSign($tikTokService->createSettleMethod, $tikTokService->createSettleUrl, $body, $timestamp, $str);
        $client = new Client();
        $url = $tikTokService->createSettleFullUrl;
        $response = $client->post($url, [
            'json' => $order ,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Byte-Authorization' => 'SHA256-RSA2048 appid="' . $config['app_id'] . '",nonce_str=' . $str . ',timestamp="' . $timestamp . '",key_version="' . $config["version"] . '",signature="' . $sign . '"'
            ]]);
        return json_decode($response->getBody()->getContents(), true);
    }


    //支付回调
    public function return(Request $request)
    {
        $status = $this->verify(json_encode($request->post()), $request->header()['byte-timestamp'][0], $request->header()['byte-nonce-str'][0], $request->header()['byte-signature'][0]);
        //搬运原来旧的逻辑
        if ($status) {
            return [
                'data' => $request->post(),
                'status' => true
            ];
        }
        return [
            'status' => false
        ];
    }

    //接收退款回调
    public function refundReturn(Request $request)
    {
        $status = $this->verify(str_replace("\\/", "/", json_encode($request->post(), JSON_UNESCAPED_UNICODE)), $request->header()['byte-timestamp'][0], $request->header()['byte-nonce-str'][0], $request->header()['byte-signature'][0]);
        if ($status) {
            return true;
        }
        return false;
    }

    //接收分账回调
    public function settleCallback(Request $request)
    {
        $status = $this->verify(str_replace("\\/", "/", json_encode($request->post(), JSON_UNESCAPED_UNICODE)), $request->header()['byte-timestamp'][0], $request->header()['byte-nonce-str'][0], $request->header()['byte-signature'][0]);
        if ($status) {
            return true;
        }
        return false;
    }

    //预下单回调
    public function returnPreCallback(Request $request)
    {
        $status = $this->verify(str_replace("\\/", "/", json_encode($request->post(), JSON_UNESCAPED_UNICODE)), $request->header()['byte-timestamp'][0], $request->header()['byte-nonce-str'][0], $request->header()['byte-signature'][0]);
        if ($status) {
            $data = $request->post();
//            $product = json_decode($data['msg'], true);
//            $goodsId = $product['goods'][0]['goods_id'];
//            $bytedanceOpenid = $product['union_id'];
            //全部数据要存起来 后续退款等操作都需要用 抖音不支持二次查询某些字段
        }
        return $data ?? [];
    }

    public function returnSuccess()
    {
        return [
            "err_no" => 0,
            "err_tips" => "success"
        ];
    }

    public function returnError(string $result = '')
    {
        return [
            "err_no" => 400,
            "err_tips" => $result ?? "business fail"
        ];
    }

    public function verify($http_body, $timestamp, $nonce_str, $sign)
    {
        $config = $this->getConfig()['industry-trading-old'];
        $data = $timestamp . "\n" . $nonce_str . "\n" . $http_body . "\n";
        $publicKey = file_get_contents($config['platform_public_key_url']);
        if (!$publicKey) {
            return null;
        }
        $res = openssl_get_publickey($publicKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        openssl_free_key($res);
        return $result;
    }
}
