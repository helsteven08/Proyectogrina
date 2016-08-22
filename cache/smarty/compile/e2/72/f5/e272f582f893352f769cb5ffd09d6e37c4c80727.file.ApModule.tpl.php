<?php /* Smarty version Smarty-3.1.19, created on 2016-08-18 12:12:19
         compiled from "/var/www/html/bocatelly/modules/appagebuilder/views/templates/hook/ApModule.tpl" */ ?>
<?php /*%%SmartyHeaderCode:75843015157b5ec73afb294-78067494%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e272f582f893352f769cb5ffd09d6e37c4c80727' => 
    array (
      0 => '/var/www/html/bocatelly/modules/appagebuilder/views/templates/hook/ApModule.tpl',
      1 => 1471540207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '75843015157b5ec73afb294-78067494',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'apLiveEdit' => 0,
    'apContent' => 0,
    'apLiveEditEnd' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b5ec73b048c5_74249981',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b5ec73b048c5_74249981')) {function content_57b5ec73b048c5_74249981($_smarty_tpl) {?>
<!-- @file modules\appagebuilder\views\templates\hook\ApModule -->
<?php echo $_smarty_tpl->tpl_vars['apLiveEdit']->value ? $_smarty_tpl->tpl_vars['apLiveEdit']->value : '';?>

<?php echo $_smarty_tpl->tpl_vars['apContent']->value;?>

<?php echo $_smarty_tpl->tpl_vars['apLiveEditEnd']->value ? $_smarty_tpl->tpl_vars['apLiveEditEnd']->value : '';?>
<?php }} ?>
