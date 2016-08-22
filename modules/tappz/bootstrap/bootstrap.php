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

/*
|--------------------------------------------------------------------------
| Controller path
|--------------------------------------------------------------------------
|
|
*/
$controller_path = dirname(__FILE__).'/../app/controllers';
/*
|--------------------------------------------------------------------------
| Model path
|--------------------------------------------------------------------------
|
|
*/
$model_path = dirname(__FILE__).'/../app/services';
/*
|--------------------------------------------------------------------------
| System path
|--------------------------------------------------------------------------
|
|
*/
$system_path = dirname(__FILE__).'/../system';

/*
|--------------------------------------------------------------------------
| Require The Prestashop Config
|--------------------------------------------------------------------------
*/
require_once dirname(__FILE__).'/../../../config/config.inc.php';

/*
|--------------------------------------------------------------------------
|
| Application class Loader
|
|--------------------------------------------------------------------------
 */
require_once dirname(__FILE__).'/Loader.php';
spl_autoload_register(array('Loader', 'load'));
/*
|--------------------------------------------------------------------------
|
| Application Controller Loader
|
|--------------------------------------------------------------------------
 */

Loader::directory($controller_path);
/*
|--------------------------------------------------------------------------
|
| Application Model Loader
|
|--------------------------------------------------------------------------
 */

Loader::directory($model_path);
/*
|--------------------------------------------------------------------------
|
| Application System Loader
|
|--------------------------------------------------------------------------
 */

Loader::directory($system_path);
/*
|--------------------------------------------------------------------------
|  App Runner
|--------------------------------------------------------------------------
*/
error_reporting(0);
ini_set('display_errors', 'Off');
require_once dirname(__FILE__).'/Builder.php';
