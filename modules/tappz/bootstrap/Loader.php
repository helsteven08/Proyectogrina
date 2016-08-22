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

class Loader
{
    protected static $classes = array();

    public static function load($name)
    {
        if (isset(self::$classes[$name])) {
            require_once self::$classes[$name];
        }
    }

    public static function register($name, $path)
    {
        self::$classes[$name] = $path;
    }

    public static function directory($path)
    {
        $directory = new DirectoryIterator($path);
        foreach ($directory as $file) {
            if (!$file->isDot() && !$file->isLink() && $file->isDir()) {
                self::directory($file->getPathname());
            } elseif (Tools::substr($file->getFilename(), -4) === '.php') {
                $className = Tools::substr($file->getFilename(), 0, -4);
                self::register($className, $file->getPathname());
            }
        }
    }
}
