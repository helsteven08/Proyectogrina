{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Block languages module -->

<div id="leo_block_top" class="topbar-box popup-over e-translate-top">
	<div data-toggle="dropdown" class="popup-title">
		<a href="#">
			<span>{l s='Setting' mod='blockgrouptop'}</span>
			<i class="fa fa-cog"></i>
		</a>
	</div>
	<div class="popup-content">
		<div id="countries" class="languages-block">
			<span>{l s='Language' mod='blockgrouptop'}</span>
			<ul id="first-languages" class="countries_ul">
				{foreach from=$languages key=k item=language name="languages"}
					<li {if $language.iso_code == $lang_iso}class="selected_language"{/if}>
						{if $language.iso_code != $lang_iso}
							{assign var=indice_lang value=$language.id_lang}
							{if isset($lang_rewrite_urls.$indice_lang)}
								<a href="{$lang_rewrite_urls.$indice_lang|escape:htmlall}" title="{$language.name}">
							{else}
								<a href="{$link->getLanguageLink($language.id_lang)|escape:htmlall}" title="{$language.name}">

							{/if}
						{/if}
								<img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11" />
						{if $language.iso_code != $lang_iso}
							</a>
						{/if}
					</li>
				{/foreach}
			</ul>
		</div>
		<form id="setCurrency" action="{$request_uri}" method="post"> 	
			<input type="hidden" name="id_currency" id="id_currency" value=""/>
			<input type="hidden" name="SubmitCurrency" value="" />
			<span>{l s='Currency' mod='blockgrouptop'}</span>
			<ul id="first-currencies" class="currencies_ul">
				{foreach from=$currencies key=k item=f_currency}
					<li {if $cookie->id_currency == $f_currency.id_currency}class="selected"{/if}>
						<a href="javascript:setCurrency({$f_currency.id_currency});" rel="nofollow" title="{$f_currency.name}">
							{$f_currency.sign}
						</a>
					</li>
				{/foreach}
			</ul>
		</form>
	</div>
</div>


<!-- /Block languages module -->
