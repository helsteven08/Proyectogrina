<?php /* Smarty version Smarty-3.1.19, created on 2016-08-11 17:32:19
         compiled from "/var/www/html/ikommerce/ikonoadmin2/themes/default/template/helpers/list/list_action_preview.tpl" */ ?>
<?php /*%%SmartyHeaderCode:91548275657acfcf3e1fdf1-59524237%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '520b8dd67b63b2b138a4d163925a16d52addbad0' => 
    array (
      0 => '/var/www/html/ikommerce/ikonoadmin2/themes/default/template/helpers/list/list_action_preview.tpl',
      1 => 1469580549,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '91548275657acfcf3e1fdf1-59524237',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'href' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57acfcf3e26275_17463064',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57acfcf3e26275_17463064')) {function content_57acfcf3e26275_17463064($_smarty_tpl) {?>
<a href="<?php echo $_smarty_tpl->tpl_vars['href']->value;?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>
" target="_blank">
	<i class="icon-eye"></i> <?php echo $_smarty_tpl->tpl_vars['action']->value;?>

</a>
<?php }} ?>
