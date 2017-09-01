# Cas Proxy Client - PHP Version

PHP Client for [xczh/cas-proxy](https://git.xjtuana.com/xczh/cas-proxy)

## Version 对应版本

- `xjtuana/cas-proxy-client-php` v1.0 compatible with `xczh/cas-proxy` v1.1

## Usage 使用方法

- 通过Composer引入包（[Packagist](https://packagist.org/packages/xjtuana/cas-proxy-client)）

```shell
composer require xjtuana/cas-proxy-client ~1.0
```

- 示例代码

```php
use Xjtuana\Cas\ProxyClient\ClientV1;
use Xjtuana\Cas\ProxyClient\CasProxyClientException;

$client = new ClientV1([
    'protocol' => 'https',
    'hostname' => 'ana.xjtu.edu.cn',
    'prefix' => '/casproxy',
]);

try {
    $user = $client->login();
} catch(CasProxyClientException $e) {
    echo $e->getMessage();
}

echo $user;
```

## Related Packages 相关包

- [xjtuana/laravel-xjtuana](https://git.xjtuana.com/xjtuana/laravel-xjtuana)