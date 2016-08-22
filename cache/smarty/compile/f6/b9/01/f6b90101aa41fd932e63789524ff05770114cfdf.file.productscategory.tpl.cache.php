<?php /* Smarty version Smarty-3.1.19, created on 2016-08-18 12:12:44
         compiled from "/var/www/html/bocatelly/themes/ap_juice/modules/productscategory/productscategory.tpl" */ ?>
<?php /*%%SmartyHeaderCode:50498261957b5ec8cbca6e4-41117190%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f6b90101aa41fd932e63789524ff05770114cfdf' => 
    array (
      0 => '/var/www/html/bocatelly/themes/ap_juice/modules/productscategory/productscategory.tpl',
      1 => 1471540207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '50498261957b5ec8cbca6e4-41117190',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'categoryProducts' => 0,
    'products' => 0,
    'productClassWidget' => 0,
    'productProfileDefault' => 0,
    'tpl_dir' => 0,
    'class' => 0,
    'comparator_max_item' => 0,
    'compared_products' => 0,
    'IS_RTL' => 0,
    'columnspage' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b5ec8cc154b8_25172671',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b5ec8cc154b8_25172671')) {function content_57b5ec8cc154b8_25172671($_smarty_tpl) {?>
<?php if (count($_smarty_tpl->tpl_vars['categoryProducts']->value)>0&&$_smarty_tpl->tpl_vars['categoryProducts']->value!==false) {?>
<div class="page-product-box blockproductscategory products_block block">
	<h4 class="title_1"><?php echo smartyTranslate(array('s'=>'Discover','mod'=>'productscategory'),$_smarty_tpl);?>
</h4>
	<h3 class="page-subheading productscategory_h3"><?php echo smartyTranslate(array('s'=>'Related items','mod'=>'productscategory'),$_smarty_tpl);?>
</h3>
	<div class="block_content">
		<div class="owl-row">
			<div id="productscategory_list" class="clearfix grid">
				<?php $_smarty_tpl->tpl_vars['tabname'] = new Smarty_variable('blockproductscategory', null, 0);?>
				<?php $_smarty_tpl->tpl_vars['itemsperpage'] = new Smarty_variable('4', null, 0);?>
				<?php $_smarty_tpl->tpl_vars['columnspage'] = new Smarty_variable('4', null, 0);?>
				<?php $_smarty_tpl->tpl_vars['products'] = new Smarty_variable($_smarty_tpl->tpl_vars['categoryProducts']->value, null, 0);?>

				<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
					<div class="item <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['mypLoop']['first']) {?>active<?php }?>">
						<div class="product_block ajax_block_product<?php if (isset($_smarty_tpl->tpl_vars['productClassWidget']->value)) {?> <?php echo $_smarty_tpl->tpl_vars['productClassWidget']->value;?>
<?php }?>">
							<?php if (isset($_smarty_tpl->tpl_vars['productProfileDefault']->value)&&$_smarty_tpl->tpl_vars['productProfileDefault']->value) {?>
						            <?php $_smarty_tpl->_capture_stack[0][] = array('productPath', null, null); ob_start(); ?><?php echo $_smarty_tpl->tpl_vars['tpl_dir']->value;?>
./profiles/<?php echo $_smarty_tpl->tpl_vars['productProfileDefault']->value;?>
.tpl<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
						            <?php echo $_smarty_tpl->getSubTemplate (((string)Smarty::$_smarty_vars['capture']['productPath']), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('callFromModule'=>isset($_smarty_tpl->tpl_vars['class']->value)), 0);?>

						        <?php } else { ?>
						            <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./sub/product-item/product-item.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('callFromModule'=>isset($_smarty_tpl->tpl_vars['class']->value)), 0);?>

						        <?php }?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'min_item')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'min_item'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo smartyTranslate(array('s'=>'Please select at least one product','js'=>1),$_smarty_tpl);?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'min_item'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'max_item')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'max_item'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo smartyTranslate(array('s'=>'You cannot add more than %d product(s) to the product comparison','sprintf'=>$_smarty_tpl->tpl_vars['comparator_max_item']->value,'js'=>1),$_smarty_tpl);?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'max_item'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('comparator_max_item'=>$_smarty_tpl->tpl_vars['comparator_max_item']->value),$_smarty_tpl);?>

<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('comparedProductsIds'=>$_smarty_tpl->tpl_vars['compared_products']->value),$_smarty_tpl);?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		$('#productscategory_list').owlCarousel({
			<?php if (isset($_smarty_tpl->tpl_vars['IS_RTL']->value)&&$_smarty_tpl->tpl_vars['IS_RTL']->value) {?>
		    	direction:'rtl',
		    <?php } else { ?>
		    	direction:'ltr',
		    <?php }?>
	        items : <?php echo $_smarty_tpl->tpl_vars['columnspage']->value;?>
,
	        itemsCustom : false,
            itemsDesktop : [1199,<?php echo $_smarty_tpl->tpl_vars['columnspage']->value;?>
],
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
<?php }?>
<?php }} ?>
