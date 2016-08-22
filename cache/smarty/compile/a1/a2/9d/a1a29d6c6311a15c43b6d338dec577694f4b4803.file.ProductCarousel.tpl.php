<?php /* Smarty version Smarty-3.1.19, created on 2016-08-18 12:12:19
         compiled from "/var/www/html/bocatelly/themes/ap_juice/modules/appagebuilder/views/templates/hook/view-producthome/ProductCarousel.tpl" */ ?>
<?php /*%%SmartyHeaderCode:191043168557b5ec73ca6390-51122925%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a1a29d6c6311a15c43b6d338dec577694f4b4803' => 
    array (
      0 => '/var/www/html/bocatelly/themes/ap_juice/modules/appagebuilder/views/templates/hook/view-producthome/ProductCarousel.tpl',
      1 => 1471540207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '191043168557b5ec73ca6390-51122925',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'carouselName' => 0,
    'apLiveEdit' => 0,
    'products' => 0,
    'itemsperpage' => 0,
    'mproducts' => 0,
    'productClassWidget' => 0,
    'scolumn' => 0,
    'profile' => 0,
    'apLiveEditEnd' => 0,
    'comparator_max_item' => 0,
    'compared_products' => 0,
    'formAtts' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b5ec73d0b746_52594322',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b5ec73d0b746_52594322')) {function content_57b5ec73d0b746_52594322($_smarty_tpl) {?>
<!-- @file modules\appagebuilder\views\templates\hook\ProductCarousel -->
<div class="carousel slide" id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['carouselName']->value, ENT_QUOTES, 'UTF-8', true);?>
">
	<?php echo $_smarty_tpl->tpl_vars['apLiveEdit']->value ? $_smarty_tpl->tpl_vars['apLiveEdit']->value : '';?>

    <?php if (count($_smarty_tpl->tpl_vars['products']->value)>$_smarty_tpl->tpl_vars['itemsperpage']->value) {?>
        <a class="carousel-control left" href="#<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['carouselName']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['carouselName']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-slide="next">&rsaquo;</a>
    <?php }?>
    <div class="carousel-inner">
        <?php $_smarty_tpl->tpl_vars['mproducts'] = new Smarty_variable(array_chunk($_smarty_tpl->tpl_vars['products']->value,$_smarty_tpl->tpl_vars['itemsperpage']->value), null, 0);?>
        <?php  $_smarty_tpl->tpl_vars['products'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['products']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mproducts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['products']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['products']->key => $_smarty_tpl->tpl_vars['products']->value) {
$_smarty_tpl->tpl_vars['products']->_loop = true;
 $_smarty_tpl->tpl_vars['products']->index++;
 $_smarty_tpl->tpl_vars['products']->first = $_smarty_tpl->tpl_vars['products']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['mypLoop']['first'] = $_smarty_tpl->tpl_vars['products']->first;
?>
            <div class="item <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['mypLoop']['first']) {?>active<?php }?>">
                <ul class="product_list row grid <?php if (isset($_smarty_tpl->tpl_vars['productClassWidget']->value)) {?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productClassWidget']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
                <?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['product']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['product']->iteration=0;
 $_smarty_tpl->tpl_vars['product']->index=-1;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
 $_smarty_tpl->tpl_vars['product']->iteration++;
 $_smarty_tpl->tpl_vars['product']->index++;
 $_smarty_tpl->tpl_vars['product']->first = $_smarty_tpl->tpl_vars['product']->index === 0;
 $_smarty_tpl->tpl_vars['product']->last = $_smarty_tpl->tpl_vars['product']->iteration === $_smarty_tpl->tpl_vars['product']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['first'] = $_smarty_tpl->tpl_vars['product']->first;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['iteration']++;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['last'] = $_smarty_tpl->tpl_vars['product']->last;
?>
                    <li class="col-sp-12 <?php if (!(($_smarty_tpl->getVariable('smarty')->value['foreach']['products']['iteration']-1) % 5)) {?>product-default-li<?php }?> ajax_block_product product_block <?php echo $_smarty_tpl->tpl_vars['scolumn']->value;?>
 <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['products']['first']) {?>first_item<?php } elseif ($_smarty_tpl->getVariable('smarty')->value['foreach']['products']['last']) {?>last_item<?php }?>">
                        <?php if (isset($_smarty_tpl->tpl_vars['profile']->value)&&$_smarty_tpl->tpl_vars['profile']->value) {?>
                            <?php $_smarty_tpl->tpl_vars["tplPath"] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./profiles/".((string)$_smarty_tpl->tpl_vars['profile']->value).".tpl", null, 0);?>
                            <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tplPath']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                        <?php } else { ?>
                            <?php echo $_smarty_tpl->getSubTemplate ('./ProductItem.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                        <?php }?>
                    </li>
                <?php } ?>
                </ul>
            </div>		
        <?php } ?>
    </div>
	<?php echo $_smarty_tpl->tpl_vars['apLiveEditEnd']->value ? $_smarty_tpl->tpl_vars['apLiveEditEnd']->value : '';?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'min_item')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'min_item'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo smartyTranslate(array('s'=>'Please select at least one product','mod'=>'appagebuilder','js'=>1),$_smarty_tpl);?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'min_item'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'max_item')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'max_item'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo smartyTranslate(array('s'=>'You cannot add more than %d product(s) to the product comparison','mod'=>'appagebuilder','sprintf'=>$_smarty_tpl->tpl_vars['comparator_max_item']->value,'js'=>1),$_smarty_tpl);?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'max_item'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('comparator_max_item'=>$_smarty_tpl->tpl_vars['comparator_max_item']->value),$_smarty_tpl);?>

<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('comparedProductsIds'=>$_smarty_tpl->tpl_vars['compared_products']->value),$_smarty_tpl);?>

</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['carouselName']->value, ENT_QUOTES, 'UTF-8', true);?>
').each(function(){
        $(this).carousel({
            pause: 'hover',
            interval: <?php echo intval($_smarty_tpl->tpl_vars['formAtts']->value['interval']);?>

        });
    });
});
</script><?php }} ?>
