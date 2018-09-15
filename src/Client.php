<?php
namespace Xjtuana\Cas\ProxyClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;

abstract class Client
{
  
    /**
     * Version of CAS Proxy Server
     * 
     * Accpetable: 'v1'
     * 
     * @val string
     */
    const VERSION = '';
    
    /**
     * Instace of HttpClient
     * 
     * @val \GuzzleHttp\Client
     */
    protected $http;
    
    /**
     * Protocol of CAS Proxy Server
     * 
     * @val string
     */
    protected $protocol;
    
    /**
     * Hostname(:port) of CAS Proxy Server
     * 
     * @val string
     */
    protected $hostname;
    
    /**
     * URL Prefix of CAS Proxy Server
     * 
     * @val string
     */
    protected $prefix;
    
    /**
     * Construct function
     * 
     * @param $config array 配置数组 protocol(默认'https') hostname(必须) prefix(默认'/'')
     * 
     */
    public function __construct(array $config) {
        $this->setConfig($config);
    }
    
    /**
     * Set the config
     * 
     * @param $config array 配置数组 protocol(默认'https') hostname(必须) prefix(默认'/'')
     * 
     * @throws \XjtuAna\CASProxyClient\CASProxyClientException
     */
    protected function setConfig(array $config) {
        if (empty($config['hostname'])) {
            throw new CasProxyClientException('Hostname is required');
        }
        $this->protocol = empty($config['protocol']) ? 'https' : $config['protocol'];
        $this->hostname = $config['hostname'];
        $this->prefix = empty($config['prefix']) ? '/' : '/' . trim($config['prefix'], '/') . '/' ;
    }
    
    /**
     * Get the http client instance
     * 
     * @return \GuzzleHttp\Client
     */
    protected function http() {
        if (! $this->http instanceof HttpClient) {
            $this->http = new HttpClient();
        }
        return $this->http;
    }
    
    /**
     * Get the current url
     * 
     * @return string
     */
    protected function getClientUrl() {
        $protocol = isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
            ? $_SERVER['HTTP_X_FORWARDED_PROTO']
            : isset($_SERVER['REQUEST_SCHEME'])
              ? $_SERVER['REQUEST_SCHEME']
              : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                ? 'https'
                : 'http';
            
        $host = $_SERVER['HTTP_HOST'];

        $request_uri = $_SERVER['REQUEST_URI'];
        
        return $protocol
            . '://'
            . $host
            . $request_uri;
    }
    
    /**
     * Get the url of cas proxy server according to the config
     * 
     * @return string
     */
    protected function getServerUrl() {
        return $this->protocol . '://' . $this->hostname . $this->prefix . static::VERSION . '/';
    }
    
}