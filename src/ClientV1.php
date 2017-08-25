<?php
namespace Xjtuana\Cas\ProxyClient;

class ClientV1 extends Client 
{
    /**
     * Version of CAS Proxy Server
     * 
     * @val string
     */
    const VERSION = 'v1';
    
    /**
     * Avaliable method paths of cas proxy server v1
     * 
     * @val string
     */
    const LOGIN_PATH = 'login';
    const LOGOUT_PATH = 'logout';
    const VERIFY_PATH = 'verify';
    
    /**
     * Get the url of login
     * 
     * @return string
     */
    protected function getLoginUrl(string $redirect_url = '') {
        if (empty($redirect_url)) {
            $redirect_url = $this->getClientUrl();
        }
        return $this->getServerUrl() . self::LOGIN_PATH . '?redirect_url=' . $redirect_url;
    }
    
    /**
     * Get the url of logout
     * 
     * @return string
     */
    protected function getLogoutUrl() {
        return $this->getServerUrl() . self::LOGOUT_PATH;
    }
    
    /**
     * Get the url of verify
     * 
     * @return string
     */
    protected function getVerifyUrl(string $guid = '') {
        return $this->getServerUrl() . self::VERIFY_PATH .'?guid=' . $guid;
    }
    
    /**
     * Get $guid from query string
     * 
     * @return string | null
     */
    public function getGuidFromQuery() {
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $parsed_array);
            if (true === array_key_exists('guid', $parsed_array)) {
                return $parsed_array['guid'];
            }
        }
        return null;
    }

    /**
     * Verify the $guid and get the userid from cas proxy server
     * 
     * @param $guid string  从Cas proxy server返回的guid，用于获取登录用户
     * 
     * @return string       返回登录用户名
     * 
     * @throws \XjtuAna\CASProxyClient\CASProxyClientException
     */
    public function verify(string $guid) {
        if (empty($guid)) {
            throw new CasProxyClientException('GUID is required');
        }
        $response = $this->http()->get( $this->getVerifyUrl($guid) );
        
        if ($status = $response->getStatusCode() !== 200) {
            throw new CasProxyClientException('Response ' . $status . ' from proxy server.');
        }

        $body = json_decode($response->getBody(), true);
        
        if (!isset($body['code']) || !isset($body['message']) || !isset($body['data'])) {
            throw new CasProxyClientException('Response Invalid');
        }
        
        if (0 !== $body['code']) {
            throw new CasProxyClientException('Code: ' . $body['code'] . '. Message: ' . $body['messaage']);
        }
        
        if (false === $body['data']['valid']) {
            throw new CasProxyClientException('GUID Invalid');
        }
        
        return $body['data']['userid'];
    }

    /**
     * Login and return the username
     * 
     * @param $redirect_url string  从CAS登录后跳转的URL，用于接收guid ($redirect_url?guid=...)
     * 
     * @return string               返回登录用户名
     */
    public function login(string $redirect_url = '') {
        if ( null === $guid = $this->getGuidFromQuery() ) {
            header( 'Location: ' . $this->getLoginUrl($redirect_url) );
            exit();
        } else {
            return $this->verify($guid);
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        header( 'Location: ' . $this->getLogoutUrl() );
        exit();
    }
}