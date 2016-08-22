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
{if count($categoryProducts) > 0 && $categoryProducts !== false}
<div class="page-product-box blockproductscategory products_block block">
	<h4 class="title_1">{l s='Discover' mod='productscategory'}</h4>
	<h3 class="page-subheading productscategory_h3">{l s='Related items' mod='productscategory'}</h3>
	<div class="block_content">
		<div class="owl-row">
			<div id="productscategory_list" class="clearfix grid">
				{assign var ='tabname' value='blockproductscategory'}
				{assign var='itemsperpage' value='4'}
				{assign var='columnspage' value='4'}
				{$products = $categoryProducts}

				{foreach from=$products item=product name=products}
					<div class="item {if $smarty.foreach.mypLoop.first}active{/if}">
						<div class="product_block ajax_block_product{if isset($productClassWidget)} {$productClassWidget}{/if}">
							{if isset($productProfileDefault) && $productProfileDefault}
						            {capture name=productPath}{$tpl_dir}./profiles/{$productProfileDefault}.tpl{/capture}
						            {include file="{$smarty.capture.productPath}" callFromModule=isset($class)}
						        {else}
						            {include file="$tpl_dir./sub/product-item/product-item.tpl" callFromModule=isset($class)}
						        {/if}
						</div>
					</div>
				{/foreach}
			</div>
		</div>
	</div>
</div>
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
<script type="text/javascript">
	jQuery(document).ready(function() {
		$('#productscategory_list').owlCarousel({
			{if isset($IS_RTL) && $IS_RTL}
		    	direction:'rtl',
		    {else}
		    	direction:'ltr',
		    {/if}
	        items : {$columnspage},
	        itemsCustom : false,
            itemsDesktop : [1199,{$columnspage}],
            itemsDesktopSmall : [979,2],
            itemsTablet : [768,2],

            itemsMobile : [479,1],
            singleItem : false,         // true : show only 1 item
            itemsScaleUp : false,
            slideSpeed : 200,  //  change speed when drag and drop a item
            paginationSpeed :800, // change speed when go next page

            autoPlay : true,   // time to show each item
            stopOnHover : false,
            navigation : true,
            navigationText : ["&lsaquo;", "&rsaquo;"],

            scrollPerPage :true,
            responsive :true,
            
            pagination : false,
        	paginationNumbers : false,
            
            addClassActive : true,

        });
	});
</script>
{/if}
