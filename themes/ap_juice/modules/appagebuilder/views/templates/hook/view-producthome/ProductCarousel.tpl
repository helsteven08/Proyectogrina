{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright  2007-2015 Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
<!-- @file modules\appagebuilder\views\templates\hook\ProductCarousel -->
<div class="carousel slide" id="{$carouselName|escape:'html':'UTF-8'}">
	{($apLiveEdit)?$apLiveEdit:''}{* HTML form , no escape necessary *}
    {if count($products)>$itemsperpage}
        <a class="carousel-control left" href="#{$carouselName|escape:'html':'UTF-8'}" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#{$carouselName|escape:'html':'UTF-8'}" data-slide="next">&rsaquo;</a>
    {/if}
    <div class="carousel-inner">
        {$mproducts=array_chunk($products,$itemsperpage)}
        {foreach from=$mproducts item=products name=mypLoop}
            <div class="item {if $smarty.foreach.mypLoop.first}active{/if}">
                <ul class="product_list row grid {if isset($productClassWidget)}{$productClassWidget|escape:'html':'UTF-8'}{/if}">
                {foreach from=$products item=product name=products}
                    <li class="col-sp-12 {if ($smarty.foreach.products.iteration -1 ) is div by 5}product-default-li{/if} ajax_block_product product_block {$scolumn} {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if}">
                        {if isset($profile) && $profile}
                            {assign var="tplPath" value="$tpl_dir./profiles/$profile.tpl"}
                            {include file="$tplPath"}
                        {else}
                            {include file='./ProductItem.tpl'}
                        {/if}
                    </li>
                {/foreach}
                </ul>
            </div>		
        {/foreach}
    </div>
	{($apLiveEditEnd)?$apLiveEditEnd:''}{* HTML form , no escape necessary *}
{addJsDefL name=min_item}{l s='Please select at least one product' mod='appagebuilder' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' mod='appagebuilder' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#{$carouselName|escape:'html':'UTF-8'}').each(function(){
        $(this).carousel({
            pause: 'hover',
            interval: {$formAtts.interval|intval}
        });
    });
});
</script>