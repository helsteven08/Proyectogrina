{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright  2007-2015 Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
<!-- @file modules\appagebuilder\views\templates\hook\ApCategoryImage -->
{function name=menu level=0}
<div class="level{$level|intval}">
{foreach $data as $category}
	{if isset($category.children) && is_array($category.children)}
	<div class="cate_{$category.id_category|intval} cate_content">
		<a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'html':'UTF-8'}">
			{if isset($category.image)}
				<img height = '10px' src='{$category["image"]|escape:'html':'UTF-8'}' alt='{$category["name"]|escape:'html':'UTF-8'}' 
				 {if $formAtts.showicons == 0 || ($level gt 0 && $formAtts.showicons == 2)} style="display:none"{/if}/>
			{/if}
			<h4 class="title_block">
				<a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'html':'UTF-8'}">
					{$category.name|escape:'html':'UTF-8'}
				</a>
			</h4>
			<p id="leo-cat-{$category.id_category}" class="leo-qty" data-str="{l s=' items' mod='appagebuilder'}"></p>
		</a>
		{menu data=$category.children level=$level+1}
	</div>
	{else}
	<div class="cate_{$category.id_category|intval} cate_content">
		<a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'html':'UTF-8'}">
			{if isset($category.image)}
				<img class="img-responsive" src='{$category["image"]|escape:'html':'UTF-8'}' alt='{$category["name"]|escape:'html':'UTF-8'}' 
				 {if $formAtts.showicons == 0 || ($level gt 0 && $formAtts.showicons == 2)} style="display:none"{/if}/>
			{/if}
		</a>
		<div class="cate-descrip">
			<h4 class="title_block">
				<a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'html':'UTF-8'}">
					{$category.name|escape:'html':'UTF-8'}
				</a>
			</h4>
			<span id="leo-cat-{$category.id_category}" class="leo-qty" data-str="{l s=' items' mod='appagebuilder'}"></span>
		</div>
	</div>
	{/if}
{/foreach}
</div>
{/function}

{if isset($categories)}
<div class="widget-category_image block text-center">
	{($apLiveEdit)?$apLiveEdit:''}{* HTML form , no escape necessary *}
	{if isset($formAtts.title) && !empty($formAtts.title)}
	<h4 class="title_block">
		{$formAtts.title|escape:'html':'UTF-8'}
	</h4>
	{/if}
	<div class="block_content">
		{foreach from = $categories key=key item =cate}
			{menu data=$cate}
		{/foreach}
		<div id="view_all_wapper_{$random|escape:'html':'UTF-8'}" style="display:none">
			<span class ="view_all"><a href="javascript:void(0)">{l s='View all' mod='appagebuilder'}</a></span>
		</div> 
	</div>
	{($apLiveEditEnd)?$apLiveEditEnd:''}{* HTML form , no escape necessary *}
</div>
{/if}
<script type="text/javascript">
{literal} 
	jQuery(document).ready(function() {
		var limit = {/literal}{$formAtts.limit|intval}{literal};
		var level = {/literal}{$formAtts.cate_depth|intval}{literal};
		$("ul.level0").each(function(){
			var element = $(this).find("ul.level" + level + " >li").length;
			var count = 0;
			$(this).find("ul.level" + level + " >li").each(function(){
			count = count + 1;
			if(count > limit){
				$(this).remove();
			}

			});

			if(element > limit){
				view = $(".view_all","#view_all_wapper_{/literal}{$random|escape:'html':'UTF-8'}"){literal}.clone(1);
				view.appendTo($(this).find("ul.level" + level));
				var href = $(this).find('a:first').attr('href');
				$("a",view).attr("href", href);
			}
		})
	});
{/literal}
</script>