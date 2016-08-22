<?php /* Smarty version Smarty-3.1.19, created on 2016-08-18 11:17:20
         compiled from "/var/www/html/bocatelly/ikonoadmin2/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4840965857b5df90583875-93526600%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '75480b62490d388359c83520fa63f89e63db0aa1' => 
    array (
      0 => '/var/www/html/bocatelly/ikonoadmin2/themes/default/template/content.tpl',
      1 => 1469580549,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4840965857b5df90583875-93526600',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b5df90588d81_76327858',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b5df90588d81_76327858')) {function content_57b5df90588d81_76327858($_smarty_tpl) {?>
<div id="ajax_confirmation" class="alert alert-success hide"></div>

<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div><?php }} ?>
