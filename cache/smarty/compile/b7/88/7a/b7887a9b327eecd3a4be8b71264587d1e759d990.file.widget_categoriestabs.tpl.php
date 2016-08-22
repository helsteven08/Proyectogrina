<?php /* Smarty version Smarty-3.1.19, created on 2016-08-17 17:16:03
         compiled from "/var/www/html/bocatelly/themes/leo_hitechgame/modules/leomanagewidgets/views/widgets/widget_categoriestabs.tpl" */ ?>
<?php /*%%SmartyHeaderCode:197330417857b4e22333b3e2-13235124%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b7887a9b327eecd3a4be8b71264587d1e759d990' => 
    array (
      0 => '/var/www/html/bocatelly/themes/leo_hitechgame/modules/leomanagewidgets/views/widgets/widget_categoriestabs.tpl',
      1 => 1469580549,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '197330417857b4e22333b3e2-13235124',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'myTab' => 0,
    'widget_heading' => 0,
    'leocategories' => 0,
    'category' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b4e223373609_11088007',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b4e223373609_11088007')) {function content_57b4e223373609_11088007($_smarty_tpl) {?>

<!-- MODULE Block specials -->
<div id="<?php echo $_smarty_tpl->tpl_vars['myTab']->value;?>
" class="exclusive leocategorytab products_block block">
	<?php if (isset($_smarty_tpl->tpl_vars['widget_heading']->value)&&!empty($_smarty_tpl->tpl_vars['widget_heading']->value)) {?>
	<h4 class="title_block">
		<?php echo $_smarty_tpl->tpl_vars['widget_heading']->value;?>

	</h4>
	<?php }?>
	<?php if (!empty($_smarty_tpl->tpl_vars['leocategories']->value)) {?>
		<ul class="nav nav-tabs">
		  <?php  $_smarty_tpl->tpl_vars['category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['leocategories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['category']->key => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->_loop = true;
?>
			<li><a href="#<?php echo $_smarty_tpl->tpl_vars['myTab']->value;?>
<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" data-toggle="tab"><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
</a></li>
		  <?php } ?>
		</ul>
	<?php }?>
	<div class="block_content">	
		<?php if (!empty($_smarty_tpl->tpl_vars['leocategories']->value)) {?>
			<!-- <ul class="nav nav-tabs">
			  <?php  $_smarty_tpl->tpl_vars['category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['leocategories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['category']->key => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->_loop = true;
?>
				<li><a href="#<?php echo $_smarty_tpl->tpl_vars['myTab']->value;?>
<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" data-toggle="tab"><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
</a></li>
			  <?php } ?>
			</ul> -->
			<div class="tab-content">
			<?php  $_smarty_tpl->tpl_vars['category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['leocategories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['category']->key => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->_loop = true;
?>
				<div class="tab-pane" id="<?php echo $_smarty_tpl->tpl_vars['myTab']->value;?>
<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
">
					<?php $_smarty_tpl->tpl_vars['products'] = new Smarty_variable($_smarty_tpl->tpl_vars['category']->value['products'], null, 0);?>  
					<?php $_smarty_tpl->tpl_vars["tabname"] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['myTab']->value)."_".((string)$_smarty_tpl->tpl_vars['category']->value['id']), null, 0);?> 
					<?php echo $_smarty_tpl->getSubTemplate ('./products.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				</div>
			<?php } ?>
			</div>
		<?php }?>
	</div>
</div>
<!-- /MODULE Block specials -->
<script type="text/javascript">

$(document).ready(function() {
    $('#<?php echo $_smarty_tpl->tpl_vars['myTab']->value;?>
 .carousel').each(function(){
        $(this).carousel({
            pause: true,
            interval: false
        });
    });
    
    $("#<?php echo $_smarty_tpl->tpl_vars['myTab']->value;?>
 .nav-tabs li", this).first().addClass("active");
    $("#<?php echo $_smarty_tpl->tpl_vars['myTab']->value;?>
 .tab-content .tab-pane", this).first().addClass("active");
});

</script>
 <?php }} ?>
