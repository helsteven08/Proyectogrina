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

class BaseAction
{
    protected function getJson()
    {
        $putresource = fopen('php://input', 'r');
        while ($putData = fread($putresource, 8192)) {
            $input_xml = $putData;
        }
        fclose($putresource);


        return $input_xml;
    }

    protected function getAuthorization()
    {
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        $header = (isset($authorization) && $authorization != '') ? $authorization : '';
        $auth = @end(@explode(' ', $header));

        return $auth;
    }
}
