<?php /* Smarty version Smarty-3.1.19, created on 2016-08-17 17:16:03
         compiled from "/var/www/html/bocatelly/themes/leo_hitechgame/modules/leomanagewidgets/views/widgets/displayfooter/widget_html.tpl" */ ?>
<?php /*%%SmartyHeaderCode:121661211657b4e22369e243-21450086%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5306f52fb699fbb479970b2b486e872f04ce07d2' => 
    array (
      0 => '/var/www/html/bocatelly/themes/leo_hitechgame/modules/leomanagewidgets/views/widgets/displayfooter/widget_html.tpl',
      1 => 1469580549,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '121661211657b4e22369e243-21450086',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'html' => 0,
    'widget_heading' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b4e2236b09f0_13382292',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b4e2236b09f0_13382292')) {function content_57b4e2236b09f0_13382292($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['html']->value)) {?>
<div class="widget-html block footer-block block nobackground">
	<?php if (isset($_smarty_tpl->tpl_vars['widget_heading']->value)&&!empty($_smarty_tpl->tpl_vars['widget_heading']->value)) {?>
	<h4 class="title_block">
		<?php echo $_smarty_tpl->tpl_vars['widget_heading']->value;?>

	</h4>
	<?php }?>
	<div class="block_content toggle-footer">
		<?php echo $_smarty_tpl->tpl_vars['html']->value;?>

	</div>
</div>
<?php }?><?php }} ?>
