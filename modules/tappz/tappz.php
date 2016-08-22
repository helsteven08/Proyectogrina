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

class Tappz extends Module
{
    const SQL_FILE = 'tappz.sql';
    const TAPPZ_VIEW = '/views/';

    public function __construct()
    {
        $this->name = 'tappz';
        $this->version = '1.0.3';
        $this->author = 'T-appz team';
        $this->tab = 'mobile';
        $this->module_key = '446b31ae63ba2f39ca81f2c6b76ad47a';
        $this->displayName = $this->l('Tappz');
        $this->description = $this->l('Sell your products with your individual app.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall T-appz? ');
        $this->bootstrap = true;
        parent::__construct();
     
    }

    public function install()
    {
        if (!parent::install()
            || !file_exists(dirname(__FILE__).'/'.self::SQL_FILE)) {
            return false;
        } elseif (!$sql = Tools::file_get_contents(dirname(__FILE__).'/'.self::SQL_FILE)) {
            return false;
        } else {
            $q = str_replace(array('PRESTA_PREFIX_', 'PRESTA_DB_ENGINE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
            $q = preg_split("/;\s*[\r\n]+/", $q);
            foreach ($q as $query) {
                if ($query) {
                    if (!Db::getInstance()->execute(trim($query))) {
                        return false;
                    }
                }
            }
            $this->createhtacces();

            return true;
        }
    }

    public function uninstall()
    {
        if (!$this->deleteTappzSql() || !parent::uninstall()) {
            return false;
        }

        return true;
    }

    private function deleteTappzSql()
    {
        return Db::getInstance()->execute(
            'DROP TABLE IF EXISTS
			`'._DB_PREFIX_.'tappz_paypal`,
			`'._DB_PREFIX_.'tappz_token`,
			`'._DB_PREFIX_.'tappz_user_agreement`'
        );
    }
    public function createhtacces()
    {
        $path = _PS_ROOT_DIR_ . '/.htaccess';
        if (file_exists($path)) {
            $write_fd = fopen($path, 'a');
            if (!$write_fd) {
                return false;
            }
            $search = "RewriteEngine on";
            $rc = "RewriteCond %{HTTP:Authorization} ^(.*) \n";
            $rc .=" RewriteRule .* - [e=HTTP_AUTHORIZATION:%1] \n";
            $rc .= " RewriteEngine on \n\nRewriteRule ^tappz/?(.*)$ %{ENV:REWRITEBASE}modules/tappz/api.php?$1 [QSA,L]";

            $subject = Tools::jsonDecode(Tools::jsonEncode(Tools::file_get_contents($path)));
            $ff = str_replace($search, $rc, $subject);
            echo file_put_contents($path, $ff);
        }

    }



    public function getContent()
    {
        $this->postUserAgrement();
        $this->postTappzPaypal();
        $this->postTappzToken();
        $this->context->smarty->assign('mod_path', $this->_path);
        $this->context->smarty->assign(array('agreement' => $this->getUserAgreement()));
        $this->context->smarty->assign(array('paypal' => $this->getTappzPaypal()));
        $this->context->smarty->assign(array('tappzToken' => $this->getTappzToken()));
        $this->context->smarty->assign('tappzCss', 'style.css');
        $iso = $this->context->language->iso_code ;
        if ($iso == "it" || $iso == "es" || $iso == "fr") {
            $view = $iso."_tappz_conf.tpl";
        } else {
            $view = "tappz_conf.tpl";
        }
        return $this->fetchTemplate('templates/admin/'.$view);
    }

    public static function getUserAgreement()
    {
        $result = Db::getInstance()->executeS('
			SELECT `user_agreement`
			FROM `'._DB_PREFIX_.'tappz_user_agreement`
			order by id_tappz_user_agreement  desc limit 1');

        return empty($result[0]['user_agreement']) ? $result[0]['user_agreement'] = '' : $result[0]['user_agreement'];
    }
    public static function getTappzToken()
    {
        $result = Db::getInstance()->getRow('
			SELECT
			username ,
			token AS tappz_token
			FROM `'._DB_PREFIX_.'tappz_token`
			');

        return  sizeof($result) > 0 ? $result : array();
    }
    public static function getTappzPaypal()
    {
        $result = Db::getInstance()->getRow('
			SELECT *
			FROM `'._DB_PREFIX_.'tappz_paypal`
			order by id_tappz_paypal  desc');

        return  sizeof($result) > 0 ? $result : array();
    }
    private function postUserAgrement()
    {
        if (Tools::isSubmit('user_agreement')) {
            $user_agreement = Tools::getValue('user_agreement');
            Db::getInstance()->execute('
				INSERT INTO `'._DB_PREFIX_.'tappz_user_agreement` (`user_agreement`)
				VALUES( "'.pSQL($user_agreement).'")');
        }
    }

    private function postTappzPaypal()
    {
        if (Tools::isSubmit('sandbox') || Tools::isSubmit('paypalSecret') || Tools::isSubmit('paypalClientId')) {
            $sandbox = Tools::getValue('sandbox');
            ($sandbox == 'on') ? $sandbox = 1 : $sandbox = 0;
            $paypalSecret = Tools::getValue('paypalSecret');
            $paypalClientId = Tools::getValue('paypalClientId');
            Db::getInstance()->execute('
				INSERT INTO `'._DB_PREFIX_.'tappz_paypal` (`paypal_secret`,`paypal_client_id`,`sandbox`)
				VALUES( "'.pSQL($paypalSecret).'","'.pSQL($paypalClientId).'","'.pSQL($sandbox).'")');
        }
    }
    private function postTappzToken()
    {
        if (Tools::isSubmit('tappz_token') || Tools::isSubmit('username')) {
            $token = Tools::getValue('tappz_token');
            $username   = Tools::getValue('username');
            Db::getInstance()->execute('
				DELETE FROM  `'._DB_PREFIX_.'tappz_token`
				');
            Db::getInstance()->execute('
				INSERT  INTO `'._DB_PREFIX_.'tappz_token` (`token`,`username`)
				VALUES( "'.pSQL($token).'","'.pSQL($username).'")');
        }
    }
    public function fetchTemplate($name)
    {
        return $this->display(__FILE__, self::TAPPZ_VIEW.$name);
    }
}
