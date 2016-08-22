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
                <p class="standart-text">Développez, concevez et déployez votre application d’achats native en moins d’une journée avec t-appz.</p>
                <div class='option-list'>
                    <ul>
                        <li>Application iPhone</li>
                        <li>Application iPad</li>
                        <li>Application Android</li>
                        <li>Application tablette Android</li>
                    </ul>
                </div>
                <p class="standart-text">Commencez votre essai gratuit aujourd’hui et lancez votre application de test en 5 étapes simples</p>
                <a href="http://t-appz.com/" class='start-btn'>Commencez votre essai gratuit</a>
                <p class="low-text">Vous avez une question ? </p>
                <p class="low-text">Drop us a line at <a href="mailto:support@t-appz.com"><strong>Envoyez-nous un e-mail à support@t-appz.com</strong></a></p>
            </div>
            <div class='col col-2'>
                <p class="standart-text"><strong>t-appz</strong> est une plate-forme DIY de commerce 
                    mobile native pour les PME du secteur du e-commerce. t-appz fournit des applications natives de commerce mobile, disponibles sur smartphones et tablettes iOS et Android.                    
                </p>
                <div class='option-list'>
                    <ul>
                        <li>Codage non requis</li>
                        <li>Thèmes prédéfinis</li>
                        <li>Testez votre application sur des appareils réels</li>
                        <li>Engagement envers le client via des notifications push et des pop-ups</li>
                        <li>Gardiennage virtuel</li>
                        <li>Lien profond</li>
                        <li>Disponible en plusieurs langues</li>
                        <li>Outils analytiques intégrés</li>
                        <li>Contrôle de la boutique en temps réel</li>
                        <li>Analyses d’application</li>
                         <li>Outil de suivi du commerce électronique Google Analytics</li>
   
                    </ul>
                </div>
            </div>
            <div class='col col-3'>
                <iframe frameBorder="0" width="100%" height="300px"
                        src="http://www.youtube.com/embed/0v4d2izoqBE">
                </iframe>
                <a href="http://t-appz.com/" class='start-btn'>Commencez votre essai gratuit</a>
            </div>
        </div>
        <div class='row'>
            <div class='opt-list'>
                <div class='opt-container'>
                    <form action="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" method="post">
                        <fieldset>
                            <div class="item">
                                <div class="description">Nom d’utilisateur:</div>
                                <input type="text" name="username" value="{if isset($tappzToken['username'])}{$tappzToken['username']|escape:'htmlall':'UTF-8'}{/if}">
                            </div>
                            <div class="item">
                                <div class="description">Token:</div>
                                <input type="text" name="tappz_token" value="{if isset($tappzToken['tappz_token'])}{$tappzToken['tappz_token']|escape:'htmlall':'UTF-8'}{/if}" >
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Enregistrer</button>
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
                                <button class="btn btn-warning btn-lg" type="submit">Enregistrer</button>
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
                                <div class="description">Clé PayPal :  </div>
                                <input type="text" name="paypalSecret" value="{if isset($paypal['paypal_client_id'])}{$paypal['paypal_secret']|escape:'htmlall':'UTF-8'}{/if}">
                            </div>
                            <div class="item">
                                <div class="description">ID client PayPal :</div>
                                <input type="text" name="paypalClientId" value="{if isset($paypal['paypal_client_id'])}{$paypal['paypal_client_id']|escape:'htmlall':'UTF-8'}{/if}" >
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Enregistrer</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>