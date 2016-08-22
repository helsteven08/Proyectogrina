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

class BaseService
{
    protected static $messages = array(
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
    );

    public function response($result)
    {
        $response = $this->encode($result);

        header('Content-type: application/json');
        echo $response;
    }

    protected function getLangId()
    {
        return (int) Context::getContext()->language->id;
    }

    protected function getShopId()
    {
        return (int) Context::getContext()->shop->id;
    }

    public static function encode($value)
    {
        $result = Tools::jsonEncode($value);
        if ($result) {
            return $result;
        }
        throw new RuntimeException(static::$messages[json_last_error()]);
    }

    public static function decode($json, $assoc = false)
    {
        $result = Tools::jsonDecode($json, $assoc);
        if ($result) {
            return $result;
        }
        throw new RuntimeException(static::$messages[json_last_error()]);
    }
}
