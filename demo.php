<?php
require 'vendor/autoload.php';

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