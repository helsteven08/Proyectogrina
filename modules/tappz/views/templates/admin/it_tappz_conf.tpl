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
                <p class="standart-text">Sviluppa, progetta e distribuisci la tua app nativa per lo shopping con t-appz in meno di un giorno.</p>
                <div class='option-list'>
                    <ul>
                        <li>App per iPhone</li>
                        <li>App per iPad</li>
                        <li>App per Android</li>
                        <li>App per tablet Android</li>
                    </ul>
                </div>
                <p class="standart-text">Inizia oggi la tua prova gratuita, e lancia la tua app di prova in 5 facili passaggi.</p>
                <a href="http://t-appz.com/" class='start-btn'>Inizia la tua prova gratuita</a>
                <p class="low-text">Hai delle domande? </p>
                <p class="low-text">Scrivici due righe all’indirizzo <a href="mailto:support@t-appz.com"><strong>support@t-appz.com</strong></a></p>
            </div>
            <div class='col col-2'>
                <p class="standart-text"><strong>t-appz</strong>
                è una piattaforma di commercio mobile nativa, fai da te, per piccole e medie imprese che operano nel campo dell’e-commerce. t-appz fornisce app native per l’m-commerce, funzionanti al 100% su smartphone e tablet iOS e Android.
              </p>
                <div class='option-list'>
                    <ul>
                        <li>Non serve nessuna codifica</li>
                        <li>Temi e design pronti all’uso</li>
                        <li>Testa la tua app su dispositivi reali</li>
                        <li>Coinvolgimento del cliente tramite notifiche push e pop-up</li>
                        <li>Geo-fencing</li>
                        <li>Deep-linking</li>
                        <li>Supporto multi-lingue</li>
                        <li>Strumenti analitici integrati</li>
                        <li>Monitoraggio del negozio in tempo reale</li>
                        <li>Analitiche dell’app</li>
                        <li>Integrazione con lo strumento di tracking e-commerce Google Analytics</li>
                        
                    </ul>
                </div>
            </div>
            <div class='col col-3'>
                <iframe frameBorder="0" width="100%" height="300px"
                        src="http://www.youtube.com/embed/0v4d2izoqBE">
                </iframe>
                <a href="http://t-appz.com/" class='start-btn'>Inizia la tua prova gratuita</a>
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
                                <div class="description">Nome utente::</div>
                                <input type="text" name="tappz_token" value="{if isset($tappzToken['tappz_token'])}{$tappzToken['tappz_token']|escape:'htmlall':'UTF-8'}{/if}" >
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Salva</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class='opt-container'>
                    <form action="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" method="post">
                        <fieldset>
                            <div class="item">
                                <div class="description">Contratto:</div>
                                <textarea name="user_agreement" >{if isset($agreement)}{$agreement|escape:'htmlall':'UTF-8'}{/if}</textarea>
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Salva</button>
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
                                <div class="description">ID cliente Paypal:</div>
                                <input type="text" name="paypalClientId" value="{if isset($paypal['paypal_client_id'])}{$paypal['paypal_client_id']|escape:'htmlall':'UTF-8'}{/if}" >
                            </div>
                            <div class="submit">
                                <button class="btn btn-warning btn-lg" type="submit">Salva</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>