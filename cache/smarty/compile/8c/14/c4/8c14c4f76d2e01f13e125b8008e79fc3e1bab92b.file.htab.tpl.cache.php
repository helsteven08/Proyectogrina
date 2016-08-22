<?php /* Smarty version Smarty-3.1.19, created on 2016-08-18 12:12:20
         compiled from "/var/www/html/bocatelly/modules/appagebuilder/views/templates/hook/htab.tpl" */ ?>
<?php /*%%SmartyHeaderCode:118736957957b5ec74091233-66122151%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8c14c4f76d2e01f13e125b8008e79fc3e1bab92b' => 
    array (
      0 => '/var/www/html/bocatelly/modules/appagebuilder/views/templates/hook/htab.tpl',
      1 => 1471540207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '118736957957b5ec74091233-66122151',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ap_header_config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b5ec74099569_22349254',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b5ec74099569_22349254')) {function content_57b5ec74099569_22349254($_smarty_tpl) {?>
<!-- @file modules\appagebuilder\views\templates\hook\htab -->
<?php if (isset($_smarty_tpl->tpl_vars['ap_header_config']->value)) {?>
<script type='text/javascript'>
	<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ap_header_config']->value, ENT_QUOTES, 'UTF-8', true);?>

</script>
<?php }?><?php }} ?>
