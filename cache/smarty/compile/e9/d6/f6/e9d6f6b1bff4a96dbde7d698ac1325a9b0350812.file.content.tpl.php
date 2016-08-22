<?php /* Smarty version Smarty-3.1.19, created on 2016-08-11 17:29:31
         compiled from "/var/www/html/ikommerce/ikonoadmin2/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:196538265357acfc4b9a5ad4-50540240%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e9d6f6b1bff4a96dbde7d698ac1325a9b0350812' => 
    array (
      0 => '/var/www/html/ikommerce/ikonoadmin2/themes/default/template/content.tpl',
      1 => 1469580549,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '196538265357acfc4b9a5ad4-50540240',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57acfc4b9ab672_81084170',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57acfc4b9ab672_81084170')) {function content_57acfc4b9ab672_81084170($_smarty_tpl) {?>
<div id="ajax_confirmation" class="alert alert-success hide"></div>

<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div><?php }} ?>
