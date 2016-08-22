<?php /* Smarty version Smarty-3.1.19, created on 2016-08-18 13:14:02
         compiled from "/var/www/html/bocatelly/modules/appagebuilder/views/templates/admin/guide.tpl" */ ?>
<?php /*%%SmartyHeaderCode:88757301857b5faea33f922-77038301%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f05b0d780e9174ee47f92cf9a80d499000e8a0c' => 
    array (
      0 => '/var/www/html/bocatelly/modules/appagebuilder/views/templates/admin/guide.tpl',
      1 => 1471540207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '88757301857b5faea33f922-77038301',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'step' => 0,
    'url1' => 0,
    'url2' => 0,
    'url3' => 0,
    'next_step' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b5faea399cf4_67948602',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b5faea399cf4_67948602')) {function content_57b5faea399cf4_67948602($_smarty_tpl) {?>
<!-- @file modules\appagebuilder\views\templates\admin\guide -->
<div class="alert alert-onboarding"><div class="" id="onboarding-starter">
	<div class="row">
		<div class="col-md-12">
			<h3><?php echo smartyTranslate(array('s'=>'Getting Started with AP PAGE BUILDER','mod'=>'appagebuilder'),$_smarty_tpl);?>
</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4 col-md-3 col-md-offset-2">
			<div class="onboarding-step step-first <?php if ($_smarty_tpl->tpl_vars['step']->value==1) {?>active step-in-progress<?php } elseif ($_smarty_tpl->tpl_vars['step']->value>1) {?>active step-success<?php } else { ?>step-todo<?php }?>"></div>
		</div>
		<div class="col-xs-4 col-md-3">
			<div class="onboarding-step <?php if ($_smarty_tpl->tpl_vars['step']->value==2) {?>active step-in-progress<?php } elseif ($_smarty_tpl->tpl_vars['step']->value>2) {?>active step-success<?php } else { ?>step-todo<?php }?>"></div>
		</div>
		<div class="col-xs-4 col-md-3">
			<div class="onboarding-step step-final <?php if ($_smarty_tpl->tpl_vars['step']->value==3) {?>active step-in-progress<?php } else { ?>step-todo<?php }?>"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4 col-md-3 col-md-offset-2 text-center">
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url1']->value, ENT_QUOTES, 'UTF-8', true);?>
" style=""><?php echo smartyTranslate(array('s'=>'Configure Module','mod'=>'appagebuilder'),$_smarty_tpl);?>
</a>
		</div>
		<div class="col-xs-4 col-md-3 text-center">
			<?php if ($_smarty_tpl->tpl_vars['step']->value>=2) {?>
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url2']->value, ENT_QUOTES, 'UTF-8', true);?>
" style="">
			<?php } else { ?>
			<a style=" color:gray; text-decoration:none ">
			<?php }?>
				<?php echo smartyTranslate(array('s'=>'Add profile','mod'=>'appagebuilder'),$_smarty_tpl);?>
</a>
		</div>
		<div class="col-xs-4 col-md-3 text-center">
			<?php if ($_smarty_tpl->tpl_vars['step']->value>=3) {?>
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url3']->value, ENT_QUOTES, 'UTF-8', true);?>
" style="">
			<?php } else { ?>
			<a style=" color:gray; text-decoration:none ">
			<?php }?><?php echo smartyTranslate(array('s'=>'Add widget','mod'=>'appagebuilder'),$_smarty_tpl);?>
</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-lg-8 <?php if ($_smarty_tpl->tpl_vars['step']->value!=1) {?>hidden<?php }?>">
			<h4><?php echo smartyTranslate(array('s'=>'Establish default parameters','mod'=>'appagebuilder'),$_smarty_tpl);?>
</h4>
			<p><?php echo smartyTranslate(array('s'=>'This module provides a way to generate more website templates homepage, quickly changing your website, the list displays the product, multiple widgets with many selling feature for your shop (all in one).','mod'=>'appagebuilder'),$_smarty_tpl);?>
</p>
		</div>
		<div class="col-lg-8 <?php if ($_smarty_tpl->tpl_vars['step']->value!=2) {?>hidden<?php }?>">
			<h4><?php echo smartyTranslate(array('s'=>'Create new a profile','mod'=>'appagebuilder'),$_smarty_tpl);?>
</h4>
			<p><?php echo smartyTranslate(array('s'=>'You can create multiple profiles, only one profile, the default will be triggered when the website is published. Each profile you can customize the interface to change independently of each other website, JavaScript and Css code to be edited directly here should become more flexible.','mod'=>'appagebuilder'),$_smarty_tpl);?>
</p>
			<p><?php echo smartyTranslate(array('s'=>'How to create a new profile screen: From the menu on the left "Ap Manage Profiles" to the next screen lists Profile; Click the button "Add new +"','mod'=>'appagebuilder'),$_smarty_tpl);?>
</p>
		</div>
		<div class="col-lg-8 <?php if ($_smarty_tpl->tpl_vars['step']->value!=3) {?>hidden<?php }?>">
			<h4><?php echo smartyTranslate(array('s'=>'Add new widgets and modules to build Web sites in a profile.','mod'=>'appagebuilder'),$_smarty_tpl);?>
</h4>
			<p><?php echo smartyTranslate(array('s'=>'This module also supply many widgets to build features for sales, intuitive user interface flexibility. Also integrate your existing modules in this module, you only need to build a site built just here and become faster page.','mod'=>'appagebuilder'),$_smarty_tpl);?>
</p>
			<p><?php echo smartyTranslate(array('s'=>'How to create a new profile screen: From the menu on the left "Ap Manage Profiles" to the next screen lists Profile; click the button "View" in the list of profiles.','mod'=>'appagebuilder'),$_smarty_tpl);?>
</p>
		</div>
		<div class="col-lg-4 onboarding-action-container">
			<a class="btn btn-default btn-lg quick-start-button pull-right" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['next_step']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php if ($_smarty_tpl->tpl_vars['step']->value==3) {?>&done=1<?php }?>">
				<?php if ($_smarty_tpl->tpl_vars['step']->value==3) {?>
					<i class="icon icon-check icon-lg"></i>
					<?php echo smartyTranslate(array('s'=>'DONE  ','mod'=>'appagebuilder'),$_smarty_tpl);?>

				<?php } else { ?>
					<?php echo smartyTranslate(array('s'=>'I\'m done, take me to next step  ','mod'=>'appagebuilder'),$_smarty_tpl);?>

					<i class="icon icon-angle-right icon-lg"></i>
				<?php }?>
			</a>
			<?php if ($_smarty_tpl->tpl_vars['step']->value<3) {?>
				<a class="btn btn-lg quick-start-button pull-right" href="<?php if ($_smarty_tpl->tpl_vars['step']->value==1) {?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url1']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php } else { ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url2']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?>&skip=1"><?php echo smartyTranslate(array('s'=>'Skip  ','mod'=>'appagebuilder'),$_smarty_tpl);?>
</a>
			<?php }?>
		</div>
	</div>
</div></div><?php }} ?>
