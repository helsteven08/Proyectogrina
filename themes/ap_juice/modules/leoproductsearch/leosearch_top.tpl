{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- Block search module -->

<script type="text/javascript">	
$(document).ready( function(){
	//$(".leo_block_search").each( function(){
		var content = $(".groupe-content");
		$(".groupe-btn").click( function(){
			content.toggleClass("eshow");
		}) ;
	//} );
});
</script>

<div id="leo_search_block_top" class="leo_block_search exclusive">
	{*<div class="groupe-btn dropdown btn-group hidden-md hidden-lg">
		<i class="fa fa-search"></i>
	</div>*}
	<div class="groupe-content">
		<form method="get" action="{$link->getPageLink('productsearch', true)|escape:'html':'UTF-8'}" id="leosearchtopbox">
			<input type="hidden" name="fc" value="module" />
			<input type="hidden" name="module" value="leoproductsearch" />
			<input type="hidden" name="controller" value="productsearch" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
	    	
			<div class="group-leosearch clearfix">
				<input class="search_query grey" type="text" id="leo_search_query_top" name="search_query" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
				<select name="cate" id="cate">
					<option value="">{l s='All Categories' mod='leoproductsearch'}</option>
				     {foreach $cates item = cate key= "key"}
				     <option value="{$cate.id_category|escape:'htmlall':'UTF-8'|stripslashes}" {if isset($selectedCate) && $cate.id_category eq $selectedCate}selected{/if} >{$cate.name}</option>
				     {/foreach}
	            </select>
			</div>
			<button type="submit" id="leo_search_top_button" class="btn btn-outline-inverse button button-small">{l s='Search' mod='leoproductsearch'}</button>
		</form>
	</div>
</div>
<!-- /Block search module -->
