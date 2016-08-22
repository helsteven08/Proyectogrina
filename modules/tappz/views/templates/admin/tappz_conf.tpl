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
                <p class="standart-text">Develop,design and deploy your native shopping app with t-appz in less than a day.</p>
                <div class='option-list'>
                    <ul>
                        <li>iPhone App</li>
                        <li>iPad App</li>
                        <li>Android App</li>
                        <li>Android Tablet App</li>
                    </ul>
                </div>
                <p class="standart-text">Start your free trial today and launch your test app in 5 easy steps.</p>
                <a href="http://t-appz.com/" class='start-btn'>Start Your Free Trial</a>
                <p class="low-text">Got any questions?</p>
                <p class="low-text">Drop us a line at <a href="mailto:support@t-appz.com"><strong>support@t-appz.com</strong></a></p>
            </div>
            <div class='col col-2'>
                <p class="standart-text"><strong>t-appz</strong> is a DIY mobile commerce app building platform for e-commerce SMEs. Pre-itergrated with 30+ e-commerce carts, t-appz delivers NATIVE m-commerce apps, fully functional on iOS and Android smartphones & tablets.</p>
                <div class='option-list'>
                    <ul>
                        <li>No coding required</li>
                        <li>Ready-to-use design themes</li>
                        <li>Test your app on real devices</li>
                        <li>Customer engagement via push notifications & pop-ups</li>
                        <li>Geo-fencing</li>
                        <li>Deep-linking</li>
                        <li>Multiple language support</li>
                        <li>Real-time store monitoring</li>
                        <li>App analytics</li>
                        <li>Google Analytics e-commerce tracking tool integration</li>
                    </ul>
                </div>
            </div>
            <div class='col col-3'>
                <iframe frameBorder="0" width="100%" height="300px"
                        src="http://www.youtube.com/embed/0v4d2izoqBE">
                </iframe>
                <a href="http://t-appz.com/" class='start-btn'>Start Your Free Trial</a>
            </div>
        </div>
        <div class='row'>
            <div class='opt-list'>
                <div class='opt-container'>
                    <form action="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" method="post">
                        <fieldset>
                            <div class="item">
                                <div class="description">Username:</div>
                                <input type="text" name="username" value="{if isset($tappzToken['username'])}{$tappzToken['username']|escape:'htmlall':'UTF-8'}{/if}">
                            </div>
                            <div class="item">
                                <div class="description">Token:</div>
                                <input type="text" name="tappz_token" value="{if isset($tappzToken['tappz_token'])}{$tappzToken['tappz_token']|escape:'htmlall':'UTF-8'}{/if}" >
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Save</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class='opt-container'>
                    <form action="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" method="post">
                        <fieldset>
                            <div class="item">
                                <div class="description">Agreement:</div>
                                <textarea name="user_agreement" >{if isset($agreement)}{$agreement|escape:'htmlall':'UTF-8'}{/if}</textarea>
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Save</button>
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
                                <div class="description">Paypal Secret:</div>
                                <input type="text" name="paypalSecret" value="{if isset($paypal['paypal_client_id'])}{$paypal['paypal_secret']|escape:'htmlall':'UTF-8'}{/if}">
                            </div>
                            <div class="item">
                                <div class="description">Paypal Client ID:</div>
                                <input type="text" name="paypalClientId" value="{if isset($paypal['paypal_client_id'])}{$paypal['paypal_client_id']|escape:'htmlall':'UTF-8'}{/if}" >
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Save</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>