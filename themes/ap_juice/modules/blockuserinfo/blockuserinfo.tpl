
<!-- Block user information module NAV  -->
<div class="header_user_info popup-over e-translate-top">
	<div data-toggle="dropdown" class="popup-title">
		<a href="#">
			<span>{l s='Account' mod='blockuserinfo'}</span>
			<i class="fa fa-user"></i>
		</a>
	</div>	
	<ul class="links popup-content">
		{if $is_logged}
			<li>
				<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow">
					<span>{l s='Hello' mod='blockuserinfo'}, {$cookie->customer_firstname} {$cookie->customer_lastname}</span>
				</a>
				<a class="logout" href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log me out' mod='blockuserinfo'}">
					{l s='Sign out' mod='blockuserinfo'}
				</a>
			</li>
		{else}
			<li><a class="login" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Login to your customer account' mod='blockuserinfo'}">
				{l s='Sign in' mod='blockuserinfo'}
			</a></li>
		{/if}

		<li>
			<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='My account' mod='blockuserinfo'}">
				{l s='My Account' mod='blockuserinfo'}
			</a>
		</li>
		<li class="last">
			<a href="{$link->getPageLink($order_process, true)|escape:'html':'UTF-8'}" title="{l s='Checkout' mod='blockuserinfo'}" class="last">
				{l s='Checkout' mod='blockuserinfo'}
			</a>
		</li>
		<li class="first">
			<a id="wishlist-total" class="ap-btn-wishlist" href="{$link->getModuleLink('blockwishlist', 'mywishlist', array(), true)|addslashes}" title="{l s='My wishlists' mod='blockuserinfo'}">
				<span>{l s='Wishlist' mod='blockuserinfo'}</span><span class="ap-total-wishlist ap-total"></span>
			</a>
		</li>
		<li>
			<a class="ap-btn-compare" href="{$link->getPageLink('products-comparison')|escape:'html':'UTF-8'}" title="{l s='Compare' mod='blockuserinfo'}" rel="nofollow">
				<span>{l s='Compare' mod='blockuserinfo'}</span><span class="ap-total-compare ap-total"></span>
			</a>
		</li>
	</ul>
</div>	