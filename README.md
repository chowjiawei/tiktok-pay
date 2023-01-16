* [x] 抖音新交易系统 （泛知识 非旧交易系统 目前旧交易系统已经被官方废弃 ，正在更新）
* [x] 抖音新交易系统 （非泛知识）

如发现bug  请直接提issue或者直接提pr，造成的不便请谅解。

  
# TitTok-Pay

抖音-中国 （交易系统支付接入）


## JetBrains 支持的项目

非常感谢 Jetbrains 为我提供了从事这个和其他开源项目的许可。

[![](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)](https://www.jetbrains.com/?from=https://github.com/overtrue)



#### 目录

- [安装说明](#composer)
- [发布配置文件](#config)
- [抖音新交易系统-泛知识](#tiktokPayA)
- [抖音新交易系统-非泛知识](#tiktokPayB)

<a name="composer"></a>
# 安装说明

环境要求   laravel框架适用

- php => ^7 | ^8
- guzzlehttp/guzzle => ^6.3"
- laravel/framework => ~5.5|~6.0|~7.0|~8.0|~9.0

使用composer安装最新版 

`composer require chowjiawei/tiktok-pay`


<a name="config"></a>
# 发布配置文件

- 使用工具包请运行Artisan命令

`php artisan vendor:publish --provider="Chowjiawei\TikTokPay\Providers\TikTokPayServiceProvider"`

<a name="tiktokPay"></a>
## 抖音新交易系统
```use Chowjiawei\Helpers\Services\TTV2Service;```

`tiktok-pay.php` 配置文件中选项,需要配置完全才可以使用

```php
$tikTokPay=app('TikTokPay');
```
- 查询订单

```php

$tikTokPay->query('站内订单号，非抖音侧订单号');

正确时返回数组 其余返回空数组
```

- 发起退款 (单个订单单个订单项)

```php
$tiktokService= new TTV2Service();
$tiktokService->refund("站内订单号，非抖音侧订单号", '价格 为分', '$itemOrderId');
正确时返回true 其余返回false
```

- 发起退款 (单个订单多个订单项)

```php
        $item= [
            [
                "item_order_id" => '',
                "refund_amount" => (int)$price
            ],
            [
                "item_order_id" => '',
                "refund_amount" => (int)$price
            ],
        ];

$tiktokService= new TTV2Service();
$tiktokService->refundManyItem("站内订单号，非抖音侧订单号",$item);
正确时返回true 其余返回false
```
- 同意退款

```php
$tiktokService= new TTV2Service();
$tiktokService->agreeRefund("站内订单号，非抖音侧订单号");
正确时返回true 其余返回false
```

- 查询退款

```php
$tiktokService= new TTV2Service();
$tiktokService->getRefund("站内订单号，非抖音侧订单号");
返回数组
```

- 发起分账

```php
$tiktokService= new TTV2Service();
$tiktokService->settle("站内订单号，非抖音侧订单号", "分账描述");
正确时返回true 其余返回false
```

- 设置回调配置

#### `config`中配置完成后 `$settingData`可以不传
如果需要再次自定义或者扩展更多糊掉参数  可以传详细参数  更多参数参考抖音
```php

$settingData = [
 'create_order_callback' => "", 
 'refund_callback' => "",
 'pay_callback' => "",
 ];

$tiktokService= new TTV2Service();
$tiktokService->settingReturn(array $settingData=[]);
正确时返回true 其余返回false
```

- 查询回调配置

```php
$tiktokService= new TTV2Service();
$tiktokService->getSettingReturn();
正确时返回数组，其余返回空数组
```

- 支付回调

```php
$tiktokService= new TTV2Service();
$tiktokService->return($request);  //控制器内 直接将接受的Request $request 传入return方法，即可自动验签，并返回接收参数

返回 `status` 正确为`true` 附带 `data`数据    错误为 `false`
```

如果业务处理失败 需要手动返回抖音成功

```php
$tiktokService->returnOK(); 
```

如果业务处理失败 需要手动返回抖音失败

```php
$tiktokService->returnError($result='失败原因，可省略'); 
```

- 预下单回调

```php
$tiktokService= new TTV2Service();
$tiktokService->return($request);  //控制器内 直接将接受的Request $request 传入return方法，即可自动验签，并返回接收参数
```

如果业务处理失败 需要手动返回抖音成功
```php
$tiktokService->returnOK(); 
```
如果业务处理失败 需要手动返回抖音失败
```php
$tiktokService->returnError($result='失败原因，可省略'); 
```

### 建议将数组内数据  存起来 后续退款等操作都需要用 抖音不支持二次查询某些字段
如果需要退款  必须存储 item_order_id_list  获取如下:
```php
$itemOrderId = json_decode($extendItem['msg'], true)['goods'][0]['item_order_id_list'][0];
```

- 退款回调

```php
$tiktokService= new TTV2Service();
$tiktokService->refundReturn($request); 
```

如果业务处理失败 需要手动返回抖音成功
```php
$tiktokService->returnOK(); 
```
如果业务处理失败 需要手动返回抖音失败
```php
$tiktokService->returnError($result='失败原因，可省略'); 
```

- 分账回调

```php
$tiktokService= new TTV2Service();
$tiktokService->settleCallback($request); 
```

如果业务处理失败 需要手动返回抖音成功
```php
$tiktokService->returnOK(); 
```
如果业务处理失败 需要手动返回抖音失败
```php
$tiktokService->returnError($result='失败原因，可省略'); 
```