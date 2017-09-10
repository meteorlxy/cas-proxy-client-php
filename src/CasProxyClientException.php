<?php
namespace Xjtuana\Cas\ProxyClient;
use Exception;
class CasProxyClientException extends Exception {
    const HTTP_EXCEPTION = 1;
    const GUID_INVALID = 2;
    const RESPONSE_INVALID = 3;
    const RESPONSE_EXCEPTION = 4;
}