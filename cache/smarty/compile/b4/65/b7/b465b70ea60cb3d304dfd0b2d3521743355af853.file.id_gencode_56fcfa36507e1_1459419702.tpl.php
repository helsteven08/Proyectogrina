<?php /* Smarty version Smarty-3.1.19, created on 2016-08-18 12:12:19
         compiled from "/var/www/html/bocatelly/themes/ap_juice/profiles/profile1453404375/id_gencode_56fcfa36507e1_1459419702.tpl" */ ?>
<?php /*%%SmartyHeaderCode:41890872057b5ec73a6d2b0-10539277%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b465b70ea60cb3d304dfd0b2d3521743355af853' => 
    array (
      0 => '/var/www/html/bocatelly/themes/ap_juice/profiles/profile1453404375/id_gencode_56fcfa36507e1_1459419702.tpl',
      1 => 1471540339,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '41890872057b5ec73a6d2b0-10539277',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_dir' => 0,
    'shop_name' => 0,
    'logo_url' => 0,
    'logo_image_width' => 0,
    'logo_image_height' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b5ec73a83505_48091433',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b5ec73a83505_48091433')) {function content_57b5ec73a83505_48091433($_smarty_tpl) {?>                               <a href="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop_name']->value, ENT_QUOTES, 'UTF-8', true);?>
">                                        <img class="logo img-responsive" src="<?php echo $_smarty_tpl->tpl_vars['logo_url']->value;?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop_name']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php if (isset($_smarty_tpl->tpl_vars['logo_image_width']->value)&&$_smarty_tpl->tpl_vars['logo_image_width']->value) {?> width="<?php echo $_smarty_tpl->tpl_vars['logo_image_width']->value;?>
"<?php }?><?php if (isset($_smarty_tpl->tpl_vars['logo_image_height']->value)&&$_smarty_tpl->tpl_vars['logo_image_height']->value) {?> height="<?php echo $_smarty_tpl->tpl_vars['logo_image_height']->value;?>
"<?php }?>/>                                    </a><?php }} ?>
