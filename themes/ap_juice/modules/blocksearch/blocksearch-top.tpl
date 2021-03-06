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

<script type="text/javascript">
	$(document).ready( function(){
 		$("#search_block_top").each( function(){
	 		$(".btn-search").click( function(){
	 				$(".over-layer,.block-form,.close-overlay").addClass('active');
	 			});
 		});
 		$('.close-overlay').click(function(){
			$(".over-layer,.block-form,.close-overlay").removeClass('active');
 		});
 });
</script>

<!-- Block search module TOP -->
<div id="search_block_top">
	<div class="btn-search" title="{l s='Search' mod='blocksearch'}">
		<i class="fa fa-search"></i>
	</div>
	<span class="close-overlay"><i class="fa fa-close"></i></span>
	<div class="over-layer"></div>
	<div class="block-form clearfix">
		<form id="searchbox" class="container" method="get" action="{$link->getPageLink('search', null, null, null, false, null, true)|escape:'html':'UTF-8'}" >
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="{l s='Search' mod='blocksearch'}" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
			<button type="submit" name="submit_search" class="btn btn-outline" >
				<i class="fa fa-search"></i>
				<span>{l s='Search' mod='blocksearch'}</span>
			</button> 
		</form>
	</div>
</div>
<!-- /Block search module TOP -->