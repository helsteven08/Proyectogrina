<?php /*%%SmartyHeaderCode:9642977657b5ec739ebb83-42576216%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9ee677f4afbd86a71b3907c13c207718efe9c3a1' => 
    array (
      0 => '/var/www/html/bocatelly/modules/appagebuilder/views/templates/hook/header.tpl',
      1 => 1471540207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9642977657b5ec739ebb83-42576216',
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b73d2e665958_33709804',
  'has_nocache_code' => false,
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b73d2e665958_33709804')) {function content_57b73d2e665958_33709804($_smarty_tpl) {?><!-- @file modules\appagebuilder\views\templates\hook\header -->
<script type='text/javascript'>
        var leoOption = {
		productNumber:1,
		productInfo:0,
		productTran:1,
		productCdown: 0,
		productColor: 0,
		homeWidth: 470,
		homeheight: 470,
	}

        $(document).ready(function(){	
            var leoCustomAjax = new $.LeoCustomAjax();
            leoCustomAjax.processAjax();
        });
	// Javascript code
</script>
<?php }} ?>
