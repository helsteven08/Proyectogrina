<?php /* Smarty version Smarty-3.1.19, created on 2016-08-11 17:29:17
         compiled from "/var/www/html/ikommerce/themes/leo_hitechgame/modules/blockuserinfo/blockuserinfo.tpl" */ ?>
<?php /*%%SmartyHeaderCode:31563816757acfc3d6eaa72-67580392%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '58b4fb739258c837ecd3ecb3d3ba40e9fb90fb38' => 
    array (
      0 => '/var/www/html/ikommerce/themes/leo_hitechgame/modules/blockuserinfo/blockuserinfo.tpl',
      1 => 1469580549,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31563816757acfc3d6eaa72-67580392',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'is_logged' => 0,
    'link' => 0,
    'cookie' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57acfc3d724df3_94015560',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57acfc3d724df3_94015560')) {function content_57acfc3d724df3_94015560($_smarty_tpl) {?>
<!-- Block user information module NAV  -->
<div class="header_user_info popup-over pull-right e-scale">
	<div class="popup-title"><i class="fa fa-user"></i></div>	
	<div class="popup-content">
		<ul class="links">
			<?php if ($_smarty_tpl->tpl_vars['is_logged']->value) {?>
				<li>
					<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'View my customer account','mod'=>'blockuserinfo'),$_smarty_tpl);?>
" class="account" rel="nofollow">
						<i class="fa fa-user"></i><span><?php echo smartyTranslate(array('s'=>'Hello','mod'=>'blockuserinfo'),$_smarty_tpl);?>
, <?php echo $_smarty_tpl->tpl_vars['cookie']->value->customer_firstname;?>
 <?php echo $_smarty_tpl->tpl_vars['cookie']->value->customer_lastname;?>
</span></a>
				</li>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['is_logged']->value) {?>
				<li><a class="logout" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('index',true,null,"mylogout"), ENT_QUOTES, 'UTF-8', true);?>
" rel="nofollow" title="<?php echo smartyTranslate(array('s'=>'Log me out','mod'=>'blockuserinfo'),$_smarty_tpl);?>
">
					<i class="fa fa-unlock-alt"></i><?php echo smartyTranslate(array('s'=>'Sign out','mod'=>'blockuserinfo'),$_smarty_tpl);?>

				</a></li>
			<?php } else { ?>
				<li><a class="login" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true);?>
" rel="nofollow" title="<?php echo smartyTranslate(array('s'=>'Login to your customer account','mod'=>'blockuserinfo'),$_smarty_tpl);?>
">
					<i class="fa fa-unlock-alt"></i><?php echo smartyTranslate(array('s'=>'Sign in','mod'=>'blockuserinfo'),$_smarty_tpl);?>

				</a></li>
			<?php }?>

			<li>
				<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'My account','mod'=>'blockuserinfo'),$_smarty_tpl);?>
"><i class="fa fa-user"></i><?php echo smartyTranslate(array('s'=>'My Account','mod'=>'blockuserinfo'),$_smarty_tpl);?>
</a>
			</li>
			
			<li>
				<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Checkout','mod'=>'blockuserinfo'),$_smarty_tpl);?>
" class="last"><i class="fa fa-share"></i><?php echo smartyTranslate(array('s'=>'Checkout','mod'=>'blockuserinfo'),$_smarty_tpl);?>
</a>
			</li>

			<li>
				<a id="wishlist-total" href="<?php echo addslashes($_smarty_tpl->tpl_vars['link']->value->getModuleLink('blockwishlist','mywishlist',array(),true));?>
" title="<?php echo smartyTranslate(array('s'=>'My wishlists','mod'=>'blockuserinfo'),$_smarty_tpl);?>
">
				<i class="fa fa-heart"></i><?php echo smartyTranslate(array('s'=>'Wish List','mod'=>'blockuserinfo'),$_smarty_tpl);?>
</a>
			</li>
			<li>
				<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('products-comparison'), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Compare','mod'=>'blockuserinfo'),$_smarty_tpl);?>
" rel="nofollow">
					<i class="fa fa-compress"></i><?php echo smartyTranslate(array('s'=>'Compare','mod'=>'blockuserinfo'),$_smarty_tpl);?>

				</a>
			</li>
			
		</ul>
	</div>
</div>	<?php }} ?>
