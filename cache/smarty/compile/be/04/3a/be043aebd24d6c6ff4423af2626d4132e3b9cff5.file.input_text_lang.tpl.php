<?php /* Smarty version Smarty-3.1.19, created on 2016-08-12 15:36:59
         compiled from "/var/www/html/ikommerce/ikonoadmin2/themes/default/template/controllers/products/input_text_lang.tpl" */ ?>
<?php /*%%SmartyHeaderCode:40792594957ae336ba77132-49352942%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be043aebd24d6c6ff4423af2626d4132e3b9cff5' => 
    array (
      0 => '/var/www/html/ikommerce/ikonoadmin2/themes/default/template/controllers/products/input_text_lang.tpl',
      1 => 1469580549,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '40792594957ae336ba77132-49352942',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'languages' => 0,
    'language' => 0,
    'maxchar' => 0,
    'input_name' => 0,
    'input_class' => 0,
    'input_value' => 0,
    'required' => 0,
    'maxlength' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ae336babb966_57149078',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ae336babb966_57149078')) {function content_57ae336babb966_57149078($_smarty_tpl) {?>

<?php  $_smarty_tpl->tpl_vars['language'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['language']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['languages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['language']->key => $_smarty_tpl->tpl_vars['language']->value) {
$_smarty_tpl->tpl_vars['language']->_loop = true;
?>
	<?php if (count($_smarty_tpl->tpl_vars['languages']->value)>1) {?>
	<div class="translatable-field row lang-<?php echo $_smarty_tpl->tpl_vars['language']->value['id_lang'];?>
">
		<div class="col-lg-9">
	<?php }?>
		<?php if (isset($_smarty_tpl->tpl_vars['maxchar']->value)) {?>
		<div class="input-group">
			<span id="<?php echo $_smarty_tpl->tpl_vars['input_name']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['language']->value['id_lang'];?>
_counter" class="input-group-addon">
				<span class="text-count-down"><?php echo $_smarty_tpl->tpl_vars['maxchar']->value;?>
</span>
			</span>
			<?php }?>
			<input type="text"
			id="<?php echo $_smarty_tpl->tpl_vars['input_name']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['language']->value['id_lang'];?>
"
			class="form-control <?php if (isset($_smarty_tpl->tpl_vars['input_class']->value)) {?><?php echo $_smarty_tpl->tpl_vars['input_class']->value;?>
 <?php }?>"
			name="<?php echo $_smarty_tpl->tpl_vars['input_name']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['language']->value['id_lang'];?>
"
			value="<?php echo (($tmp = @smarty_modifier_htmlentitiesUTF8($_smarty_tpl->tpl_vars['input_value']->value[$_smarty_tpl->tpl_vars['language']->value['id_lang']]))===null||$tmp==='' ? '' : $tmp);?>
"
			onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
			onblur="updateLinkRewrite();"
			<?php if (isset($_smarty_tpl->tpl_vars['required']->value)) {?> required="required"<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['maxchar']->value)) {?> data-maxchar="<?php echo $_smarty_tpl->tpl_vars['maxchar']->value;?>
"<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['maxlength']->value)) {?> maxlength="<?php echo $_smarty_tpl->tpl_vars['maxlength']->value;?>
"<?php }?> />
			<?php if (isset($_smarty_tpl->tpl_vars['maxchar']->value)) {?>
		</div>
		<?php }?>
	<?php if (count($_smarty_tpl->tpl_vars['languages']->value)>1) {?>
		</div>
		<div class="col-lg-2">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
				<?php echo $_smarty_tpl->tpl_vars['language']->value['iso_code'];?>

				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<?php  $_smarty_tpl->tpl_vars['language'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['language']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['languages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['language']->key => $_smarty_tpl->tpl_vars['language']->value) {
$_smarty_tpl->tpl_vars['language']->_loop = true;
?>
				<li>
					<a href="javascript:tabs_manager.allow_hide_other_languages = false;hideOtherLanguage(<?php echo $_smarty_tpl->tpl_vars['language']->value['id_lang'];?>
);"><?php echo $_smarty_tpl->tpl_vars['language']->value['name'];?>
</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<?php }?>
<?php } ?>
<?php if (isset($_smarty_tpl->tpl_vars['maxchar']->value)) {?>
<script type="text/javascript">
$(document).ready(function(){
<?php  $_smarty_tpl->tpl_vars['language'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['language']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['languages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['language']->key => $_smarty_tpl->tpl_vars['language']->value) {
$_smarty_tpl->tpl_vars['language']->_loop = true;
?>
	countDown($("#<?php echo $_smarty_tpl->tpl_vars['input_name']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['language']->value['id_lang'];?>
"), $("#<?php echo $_smarty_tpl->tpl_vars['input_name']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['language']->value['id_lang'];?>
_counter"));
<?php } ?>
});
</script>
<?php }?>
<?php }} ?>