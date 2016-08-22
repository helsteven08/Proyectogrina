{*
 *  Leo Prestashop SliderShow for Prestashop 1.6.x
 *
 * @package   leosliderlayer
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}
<div class="blog-item">
	<div class="blog-meta">
		{if $config->get('listing_show_created','1')}
			<span class="blog-created">
				{strtotime($blog.date_add)|date_format:"%d %B %Y"}
			</span>
		{/if}
	
		{if $config->get('listing_show_author','1')&&!empty($blog.author)}
			<span class="blog-author">
				<span>{l s='By' mod='leoblog'}</span>
				<a href="{$blog.author_link|escape:'html':'UTF-8'}" title="{$blog.author}">{$blog.author}</a> 
			</span>
		{/if}
		
		{if $config->get('listing_show_category','1')}
			<span class="blog-cat"> 
				<span> {l s='In' mod='leoblog'}:</span> 
				<a href="{$blog.category_link|escape:'html':'UTF-8'}" title="{$blog.category_title|escape:'html':'UTF-8'}">{$blog.category_title}</a>
			</span>
		{/if}
		
		{if isset($blog.comment_count)&&$config->get('listing_show_counter','1')}	
			<span class="blog-ctncomment">
				<span > {l s='Comment' mod='leoblog'}</span> 
				{$blog.comment_count}
			</span>
		{/if}

		{if $config->get('listing_show_hit','1')}	
			<span class="blog-hit">
				<span> {l s='Hit' mod='leoblog'}</span> 
				{$blog.hits}
			</span>
		{/if}
	</div>
	<div class="blog-info">
		{if $blog.image && $config->get('listing_show_image',1)}
			<div class="blog-image">
				<img class="img-responsive" src="{$blog.preview_url}" title="{$blog.title}"/>
			</div>
		{/if}
		{if $config->get('listing_show_title','1')}
			<h4 class="name"><a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title}">{$blog.title}</a></h4>
		{/if}
		{if $config->get('listing_show_description','1')}
			<div class="blog-shortinfo">
				{$blog.description|strip_tags:'UTF-8'|truncate:150:'...'}		
			</div>
		{/if}
		{if $config->get('listing_show_readmore',1)}
			<div class="read-more">
				<a href="{$blog.link}" title="{$blog.title|escape:'html':'UTF-8'}" class="button">{l s='Read more >' mod='leoblog'}</a>
			</div>
		{/if}
	</div>
</div>