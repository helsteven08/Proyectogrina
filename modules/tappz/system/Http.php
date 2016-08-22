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

class Http
{
    private $default_action = 'index';
    private $methods = array(
        'GET' => 'index',
        'POST' => 'store',
        'PUT' => 'update',
        'DELETE' => 'delete',
    );

    protected function request()
    {
        $param = $this->parse();
        $class = $this->resources($param);
        $this->registerMethod($param, $class);
    }

    private function parse()
    {
        $arr = array_keys($_GET);
        $result = explode('/', $arr[0]);

        return $result;
    }

    private function resources($param)
    {
        if (empty($param[0])) {
            $action = Tools::ucfirst(($this->default_action));
        } else {
            $action = Tools::ucfirst(($param[0]));
        }

        return $this->initClass($action.'Action');
    }

    private function registerMethod($param, $class)
    {
        if (!Module::isEnabled('tappz')) {
            return false;
        }
        $request = $_SERVER['REQUEST_METHOD'];
        $method = $this->methods[Tools::strtoupper($request)];
        if ($param[1] != '' && empty($param[2])) {
            $class->show($param[1]);
        } elseif ($param[2] != '' && $param[3] == '') {
            $method = $param[2].Tools::ucfirst(($method));
            $class->$method($param[1]);
        } elseif (($param[3]) != '') {
            $method = $param[2].Tools::ucfirst(($method)).Tools::ucfirst(($param[3]));
            $class->$method($param[1]);
        } else {
            $class->$method();
        }
    }

    private function initClass($name)
    {
        if (!class_exists($name, false)) {
            ;
        }

        return new $name();
    }
}
