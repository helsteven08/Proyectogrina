{*
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
*}
<link rel='stylesheet' type='text/css'
      href='//fonts.googleapis.com/css?family=Open+Sans:400,600&subset=latin,latin-ext'/>
<link rel="stylesheet" type="text/css" href="{$mod_path|escape:'html':'UTF-8'}views/css/{$tappzCss|escape:'html'}">
<div class='tcp-container'>
    <div class='header'>
        <div class='images'>
            <div class='image'><img src="{$mod_path|escape:'html':'UTF-8'}views/img/android_cihaz@2x.png"></div>
            <div class='image'><img src="{$mod_path|escape:'html':'UTF-8'}views/img/tappz_logo@2x.png"></div>
            <div class='image'><img src="{$mod_path|escape:'html':'UTF-8'}views/img/apple_cihaz@2x.png"></div>
        </div>
    </div>
    <div class='body'>
        <div class='row'>
            <div class='col col-1'>
                <p class="standart-text">Desarrolla, diseña e implementa tu aplicación nativa para comprar con t-appz en menos de un día..</p>
                <div class='option-list'>
                    <ul>
                        <li>Aplicación de iPhone</li>
                        <li>Aplicación para iPad</li>
                        <li>Aplicación para Android</li>
                        <li>Aplicación para tablet Android</li>
                    </ul>
                </div>
                <p class="standart-text">Consigue tu prueba gratuita ahora y empieza a utilizar tu aplicación de prueba en 5 sencillos pasos </p>
                <a href="http://t-appz.com/" class='start-btn'>Consigue tu prueba gratuita</a>
                <p class="low-text">¿Tienes dudas? </p>
                <p class="low-text">Escríbenos a <a href="mailto:support@t-appz.com"><strong>support@t-appz.com</strong></a></p>
            </div>
            <div class='col col-2'>
                <p class="standart-text"><strong>t-appz</strong> es una plataforma comercial nativa de dispositivos móviles de desarrollo
                    propio para pequeñas y medianas empresas de e-commerce. t-appz ofrece aplicaciones m-commerce nativas completamente funcionales en tablets y smartphones con tecnología iOS y Android.</p>
                <div class='option-list'>
                    <ul>
                        <li>No es necesario programar</li>
                        <li>Temas y diseños listos para usar</li>
                        <li>Prueba tu aplicación en dispositivos reales</li>
                        <li>Compromiso con el cliente a través de notificaciones push y pop-ups</li>
                        <li>Geovalla</li>
                        <li>Enlace profundo</li>
                        <li>Disponible en varios idiomas</li>
                        <li>Herramienta de análisis integrada</li>
                        <li>Control de la tienda en tiempo real</li>
                        <li>Análisis de aplicación</li>
                        <li>Integración de la herramienta de seguimiento e-commerce Google Analytics</li>
                        
                    </ul>
                </div>
            </div>
            <div class='col col-3'>
                <iframe frameBorder="0" width="100%" height="300px"
                        src="http://www.youtube.com/embed/0v4d2izoqBE">
                </iframe>
                <a href="http://t-appz.com/" class='start-btn'>Consigue tu prueba gratuita</a>
            </div>
        </div>
        <div class='row'>
            <div class='opt-list'>
                <div class='opt-container'>
                    <form action="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" method="post">
                        <fieldset>
                            <div class="item">
                                <div class="description">Nombre de usuario:</div>
                                <input type="text" name="username" value="{if isset($tappzToken['username'])}{$tappzToken['username']|escape:'htmlall':'UTF-8'}{/if}">
                            </div>
                            <div class="item">
                                <div class="description">Token:</div>
                                <input type="text" name="tappz_token" value="{if isset($tappzToken['tappz_token'])}{$tappzToken['tappz_token']|escape:'htmlall':'UTF-8'}{/if}" >
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Guardar</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class='opt-container'>
                    <form action="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" method="post">
                        <fieldset>
                            <div class="item">
                                <div class="description">Acuerdo:</div>
                                <textarea name="user_agreement" >{if isset($agreement)}{$agreement|escape:'htmlall':'UTF-8'}{/if}</textarea>
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Guardar</button>
                            </div>
                        </fieldset>
                    </form>
                </div>

                <div class='opt-container'>
                    <form action="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" method="post">
                        <fieldset>
                            <div class="item">
                                <div class="description"></div>
                                <input type="checkbox" name="sandbox" {if isset($paypal['paypal_client_id'])}{if ($paypal['sandbox'])>0}checked{/if}{/if}><span class="description" style="vertical-align:text-top">Sandbox</span>
                            </div>
                            <div class="item">
                                <div class="description">Clave secreta de PayPal:</div>
                                <input type="text" name="paypalSecret" value="{if isset($paypal['paypal_client_id'])}{$paypal['paypal_secret']|escape:'htmlall':'UTF-8'}{/if}">
                            </div>
                            <div class="item">
                                <div class="description">Identificador de cliente Paypal:</div>
                                <input type="text" name="paypalClientId" value="{if isset($paypal['paypal_client_id'])}{$paypal['paypal_client_id']|escape:'htmlall':'UTF-8'}{/if}" >
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Guardar</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>