{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright  2007-2015 Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
<!-- @file modules\appagebuilder\views\templates\front\products\compare -->
{if isset($comparator_max_item) && $comparator_max_item}

		<a class="add_to_compare compare button btn" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" title="{l s='Add to compare' mod='appagebuilder'}"><span><i class="fa fa-refresh"></i></span></a>

{/if}


