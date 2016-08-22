<?php /*%%SmartyHeaderCode:177865287757b5ec73ad84a1-23133016%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6b37e14832f01d4196509088d37a655ca4c83c36' => 
    array (
      0 => '/var/www/html/bocatelly/themes/ap_juice/modules/blocksearch/blocksearch-top.tpl',
      1 => 1471540207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '177865287757b5ec73ad84a1-23133016',
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b73d2e76b760_03185067',
  'has_nocache_code' => false,
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b73d2e76b760_03185067')) {function content_57b73d2e76b760_03185067($_smarty_tpl) {?>
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
	<div class="btn-search" title="Búsqueda">
		<i class="fa fa-search"></i>
	</div>
	<span class="close-overlay"><i class="fa fa-close"></i></span>
	<div class="over-layer"></div>
	<div class="block-form clearfix">
		<form id="searchbox" class="container" method="get" action="//190.60.31.15/bocatelly/index.php?controller=search" >
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="Búsqueda" value="" />
			<button type="submit" name="submit_search" class="btn btn-outline" >
				<i class="fa fa-search"></i>
				<span>Búsqueda</span>
			</button> 
		</form>
	</div>
</div>
<!-- /Block search module TOP --><?php }} ?>
