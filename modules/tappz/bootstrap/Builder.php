<?php
/**
 * NOTICE OF LICENSE.
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    Tappz Team
 *  @copyright 2009-2016 Tmob
 *  @license   LICENSE.txt
 */

class Builder extends Http
{


    public function run()
    {
        $this->checkAuth();
        $this->request();
    }



    public function checkAuth()
    {
        $header = (isset($_SERVER['HTTP_AUTHORIZATION'])
            && $_SERVER['HTTP_AUTHORIZATION'] != '') ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        $auth = (@explode(' ', $header));
        $url = urldecode($this->getUrl($_SERVER));
        $token  = $this->getToken();
        if (sizeof($token) == 0) {
            exit(' 401 - Token not initialized.Please create  token on configuration page ');
        } elseif (sha1(utf8_encode(trim($token.'|'.($url).'|'.@$auth[2])), false) != @$auth[1]) {
            exit(' 403 - Access denied.Please check your tokens');
        }
        return $auth;
    }

    private function urlOrigin($s, $use_forwarded_host = false)
    {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $sp = Tools::strtolower($s['SERVER_PROTOCOL']);
        $protocol = Tools::substr($sp, 0, strpos($sp, '/')).(($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':'.$port;
        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ?
            $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'].$port;

        return $protocol.'://'.$host;
    }

    private function getUrl($s, $use_forwarded_host = false)
    {
        return $this->urlOrigin($s, $use_forwarded_host).$s['REQUEST_URI'];
    }
    private function getToken()
    {
            $result = Db::getInstance()->getRow('
			SELECT
			token
			FROM `'._DB_PREFIX_.'tappz_token`
			');
            return  sizeof($result) > 0 ? $result['token'] : array();
    }
}
