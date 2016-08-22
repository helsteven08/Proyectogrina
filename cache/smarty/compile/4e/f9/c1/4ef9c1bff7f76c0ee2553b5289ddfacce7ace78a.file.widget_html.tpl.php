<?php /* Smarty version Smarty-3.1.19, created on 2016-08-11 17:29:17
         compiled from "/var/www/html/ikommerce/themes/leo_hitechgame/modules/leomanagewidgets/views/widgets/displayfooter/widget_html.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21432564857acfc3db8edb2-39175071%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4ef9c1bff7f76c0ee2553b5289ddfacce7ace78a' => 
    array (
      0 => '/var/www/html/ikommerce/themes/leo_hitechgame/modules/leomanagewidgets/views/widgets/displayfooter/widget_html.tpl',
      1 => 1469580549,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21432564857acfc3db8edb2-39175071',
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
  'unifunc' => 'content_57acfc3db9a2d7_51236282',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57acfc3db9a2d7_51236282')) {function content_57acfc3db9a2d7_51236282($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['html']->value)) {?>
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
