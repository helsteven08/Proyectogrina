<?php /* Smarty version Smarty-3.1.19, created on 2016-08-18 12:12:20
         compiled from "/var/www/html/bocatelly/modules/leoslideshow/views/templates/front/leoslideshow.tpl" */ ?>
<?php /*%%SmartyHeaderCode:192927127557b5ec749659e1-76626028%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ab774b9ef0c327b029bcc93fd572982cbc8dbdbb' => 
    array (
      0 => '/var/www/html/bocatelly/modules/leoslideshow/views/templates/front/leoslideshow.tpl',
      1 => 1471540207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '192927127557b5ec749659e1-76626028',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sliderParams' => 0,
    'class_group' => 0,
    'sliders' => 0,
    'slider' => 0,
    'layer' => 0,
    'sliderImgUrl' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b5ec74b37416_66830900',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b5ec74b37416_66830900')) {function content_57b5ec74b37416_66830900($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/html/bocatelly/tools/smarty/plugins/modifier.replace.php';
?>
<?php $_smarty_tpl->tpl_vars["class_group"] = new Smarty_variable("iview-group-".((string)$_smarty_tpl->tpl_vars['sliderParams']->value['id_leoslideshow_groups']), null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['slider_class']=="boxed") {?>
	<div class="layerslider-wrapper<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['group_class']) {?> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sliderParams']->value['group_class'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?><?php if (intval($_smarty_tpl->tpl_vars['sliderParams']->value['md_width'])) {?> col-md-<?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['md_width']);?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['sm_width']) {?> col-sm-<?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['sm_width']);?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['sm_width']) {?> col-xs-<?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['xs_width']);?>
<?php }?>">
<?php }?>
<div class="bannercontainer banner-<?php echo $_smarty_tpl->tpl_vars['sliderParams']->value['slider_class'];?>
<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['group_class']) {?> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sliderParams']->value['group_class'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" style="padding: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sliderParams']->value['padding'], ENT_QUOTES, 'UTF-8', true);?>
;margin: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sliderParams']->value['margin'], ENT_QUOTES, 'UTF-8', true);?>
;">
	<div class="iview <?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
">
		<?php if ($_smarty_tpl->tpl_vars['sliders']->value) {?>
			<?php  $_smarty_tpl->tpl_vars['slider'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slider']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sliders']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['slider']->key => $_smarty_tpl->tpl_vars['slider']->value) {
$_smarty_tpl->tpl_vars['slider']->_loop = true;
?>

				<?php if ($_smarty_tpl->tpl_vars['slider']->value['video']['active']) {?>
					<!-- SLIDE VIDEO BEGIN -->
					<div data-leo_image="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['thumbnail'], ENT_QUOTES, 'UTF-8', true);?>
" data-leo_type="video" data-leo_transition="strip-right-fade,strip-left-fade"
						 data-leo_background="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['background_type'], ENT_QUOTES, 'UTF-8', true);?>
"
						 >
						<iframe src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['video']['videoURL'], ENT_QUOTES, 'UTF-8', true);?>
?title=0&amp;byline=0&amp;portrait=0;api=1&<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['video']['videoauto'], ENT_QUOTES, 'UTF-8', true);?>
" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
					</div>
					<!-- SLIDE VIDEO END -->
				<?php } else { ?>
					
					<!-- SLIDE IMAGE BEGIN -->
					<div class="slide_config"
						<?php if ($_smarty_tpl->tpl_vars['slider']->value['main_image']!='') {?> data-leo_image="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['main_image'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['slider']->value['background_color']!='') {?>data-leo_background_color="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['background_color'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>
						<?php echo $_smarty_tpl->tpl_vars['slider']->value['data_link'];?>

						<?php echo $_smarty_tpl->tpl_vars['slider']->value['data_target'];?>

						data-leo_pausetime="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['data_delay'], ENT_QUOTES, 'UTF-8', true);?>
"
						data-leo_thumbnail="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['thumbnail'], ENT_QUOTES, 'UTF-8', true);?>
"
						data-leo_background="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['background_type'], ENT_QUOTES, 'UTF-8', true);?>
"
						
						<?php if ($_smarty_tpl->tpl_vars['slider']->value['enable_custom_html_bullet']) {?>
						data-leo_bullet_description="<?php echo $_smarty_tpl->tpl_vars['slider']->value['bullet_description'];?>
"
						data-leo_bullet_class="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['bullet_class'], ENT_QUOTES, 'UTF-8', true);?>
"
						<?php }?>
						
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slider']->value['data_link'], ENT_QUOTES, 'UTF-8', true);?>

						>
						
						
						<?php if (isset($_smarty_tpl->tpl_vars['slider']->value['layersparams'])) {?>
							<?php  $_smarty_tpl->tpl_vars['layer'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['layer']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['slider']->value['layersparams']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['layer']->key => $_smarty_tpl->tpl_vars['layer']->value) {
$_smarty_tpl->tpl_vars['layer']->_loop = true;
?>
								
								<div class="tp-caption <?php if ($_smarty_tpl->tpl_vars['layer']->value['layer_link']) {?>data-link<?php }?><?php if ($_smarty_tpl->tpl_vars['layer']->value['layer_class']) {?> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_class'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" 
									 data-x="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_left'], ENT_QUOTES, 'UTF-8', true);?>
"
									 data-y="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_top'], ENT_QUOTES, 'UTF-8', true);?>
"
									 data-transition="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_transition'], ENT_QUOTES, 'UTF-8', true);?>
"
									 <?php if ($_smarty_tpl->tpl_vars['layer']->value['layer_link']) {?>onclick="event.stopPropagation();window.open('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_link'], ENT_QUOTES, 'UTF-8', true);?>
','<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_target'], ENT_QUOTES, 'UTF-8', true);?>
');"<?php }?>
									 <?php if ($_smarty_tpl->tpl_vars['layer']->value['css']) {?>style="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['css'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
									 >
									
									<?php if ($_smarty_tpl->tpl_vars['layer']->value['layer_type']=="text") {?><!-- LAYER TEXT BEGIN -->
										<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['layer']->value['layer_caption'],"_ASM_","&");?>

									<?php }?><!-- LAYER TEXT END -->


									<?php if ($_smarty_tpl->tpl_vars['layer']->value['layer_type']=="image") {?><!-- LAYER IMAGE BEGIN -->
										<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sliderImgUrl']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_content'], ENT_QUOTES, 'UTF-8', true);?>
" alt="" class="img_disable_drag"/>
									<?php }?><!-- LAYER IMAGE END -->


									<?php if ($_smarty_tpl->tpl_vars['layer']->value['layer_type']=="video") {?><!-- LAYER VIDEO BEGIN -->
										<?php if ($_smarty_tpl->tpl_vars['layer']->value['layer_video_type']=="vimeo") {?>
											<iframe src="http://player.vimeo.com/video/<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_video_id'], ENT_QUOTES, 'UTF-8', true);?>
?wmode=transparent&amp;title=0&amp;byline=0&amp;portrait=0;api=1" width="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_video_width'], ENT_QUOTES, 'UTF-8', true);?>
" height="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_video_height'], ENT_QUOTES, 'UTF-8', true);?>
" ></iframe>
										<?php } else { ?>
											<iframe src="http://www.youtube.com/embed/<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_video_id'], ENT_QUOTES, 'UTF-8', true);?>
?wmode=transparent" width="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_video_width'], ENT_QUOTES, 'UTF-8', true);?>
" height="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['layer']->value['layer_video_height'], ENT_QUOTES, 'UTF-8', true);?>
"></iframe>
										<?php }?>
									<?php }?><!-- LAYER VIDEO END -->
									
									
								</div>
							<?php } ?>
						<?php }?>
						
				</div><!-- SLIDE IMAGE END -->
				<?php }?>

			<?php } ?>
		<?php }?>
	</div>
</div>
<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['slider_class']=="boxed") {?>
	</div>
<?php }?>



<script type="text/javascript">
$( document ).ready(function() {

	jQuery(".<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
").iView({
		// COMMON
		pauseTime:<?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['delay'])===null||$tmp==='' ? '5000' : $tmp));?>
, // delay
		startSlide:<?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['start_with_slide'])===null||$tmp==='' ? '0' : $tmp));?>
,
		autoAdvance: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['autoAdvance'])===null||$tmp==='' ? 'true' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
,	// enable timer thá»�i gian auto next slide
		pauseOnHover: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['stop_on_hover'])===null||$tmp==='' ? 'false' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
,
		randomStart: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['shuffle_mode'])===null||$tmp==='' ? 'false' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
, // Ramdom slide when start

		// TIMER
		timer: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timer'])===null||$tmp==='' ? 'Pie' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
",
		timerPosition: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerPosition'])===null||$tmp==='' ? 'top-right' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
", // Top-right, top left ....
		timerX: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerX'])===null||$tmp==='' ? '10' : $tmp));?>
,
		timerY: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerY'])===null||$tmp==='' ? '10' : $tmp));?>
,
		timerOpacity: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerOpacity'])===null||$tmp==='' ? '0.5' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
,
		timerBg: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerBg'])===null||$tmp==='' ? '#000' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
",
		timerColor: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerColor'])===null||$tmp==='' ? '#EEE' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
",
		timerDiameter: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerDiameter'])===null||$tmp==='' ? '30' : $tmp));?>
,
		timerPadding: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerPadding'])===null||$tmp==='' ? '4' : $tmp));?>
,
		timerStroke: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerStroke'])===null||$tmp==='' ? '3' : $tmp));?>
,
		timerBarStroke: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerBarStroke'])===null||$tmp==='' ? '1' : $tmp));?>
,
		timerBarStrokeColor: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerBarStrokeColor'])===null||$tmp==='' ? '#EEE' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
",
		timerBarStrokeStyle: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['timerBarStrokeStyle'])===null||$tmp==='' ? 'solid' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
",
		playLabel: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['playLabel'])===null||$tmp==='' ? 'Play' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
",
		pauseLabel: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['pauseLabel'])===null||$tmp==='' ? 'Pause' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
",
		closeLabel: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['closeLabel'])===null||$tmp==='' ? 'Close' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
", // Muli language

		// NAVIGATOR controlNav
		controlNav: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['controlNav'])===null||$tmp==='' ? 'false' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
, // true : enable navigate - default:'false'
		keyboardNav: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['keyboardNav'])===null||$tmp==='' ? 'true' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
, // true : enable keybroad
		controlNavThumbs: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['controlNavThumbs'])===null||$tmp==='' ? 'false' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
, // true show thumbnail, false show number ( bullet )
		controlNavTooltip: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['controlNavTooltip'])===null||$tmp==='' ? 'true' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
, // true : hover to bullet show thumnail
		tooltipX: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['tooltipX'])===null||$tmp==='' ? '5' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
,
		tooltipY: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['tooltipY'])===null||$tmp==='' ? '-5' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
,
		controlNavHoverOpacity: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['controlNavHoverOpacity'])===null||$tmp==='' ? '0.6' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
, // opacity navigator

		// DIRECTION
		controlNavNextPrev: false, // false dont show direction at navigator
		directionNav: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['directionNav'])===null||$tmp==='' ? 'true' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
, // true  show direction at image ( in this case : enable direction )
		directionNavHoverOpacity: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['directionNavHoverOpacity'])===null||$tmp==='' ? '0.6' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
, // direction opacity at image
		nextLabel: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['nextLabel'])===null||$tmp==='' ? 'Next' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
",				// Muli language
		previousLabel: "<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['previousLabel'])===null||$tmp==='' ? 'Previous' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
", // Muli language

		// ANIMATION 
		fx: '<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['fx'])===null||$tmp==='' ? 'random' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
', // Animation
		animationSpeed: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['animationSpeed'])===null||$tmp==='' ? '500' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
, // time to change slide
//		strips: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['strips'])===null||$tmp==='' ? '20' : $tmp));?>
,
		strips: 1, // set value is 1 -> fix animation full background
		blockCols: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['blockCols'])===null||$tmp==='' ? '10' : $tmp));?>
, // number of columns
		blockRows: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['blockRows'])===null||$tmp==='' ? '5' : $tmp));?>
, // number of rows

		captionSpeed: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['captionSpeed'])===null||$tmp==='' ? '500' : $tmp));?>
, // speed to show caption
		captionOpacity: <?php echo intval((($tmp = @$_smarty_tpl->tpl_vars['sliderParams']->value['captionOpacity'])===null||$tmp==='' ? '1' : $tmp));?>
, // caption opacity
		captionEasing: 'easeInOutSine', // caption transition easing effect, use JQuery Easings effect
		customWidth: <?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['width']);?>
,
		customHtmlBullet: <?php if ($_smarty_tpl->tpl_vars['slider']->value['enable_custom_html_bullet']) {?>true<?php } else { ?>false<?php }?>,
		rtl: <?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['rtl']) {?>true<?php } else { ?>false<?php }?>,
		height:<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['height']) {?><?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['height']);?>
<?php } else { ?>780<?php }?>,

		//onBeforeChange: function(){}, // Triggers before a slide transition
		//onAfterChange: function(){}, // Triggers after a slide transition
		//onSlideshowEnd: function(){}, // Triggers after all slides have been shown
		//onLastSlide: function(){}, // Triggers when last slide is shown
		//onPause: function(){}, // Triggers when slider has paused
		//onPlay: function(){} // Triggers when slider has played

		onAfterLoad: function() 
		{
			// THUMBNAIL
			<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_height']) {?>
					$('.<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
 .iview-controlNav a img').height(<?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_height']);?>
);
					//$('.<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
 .iview-tooltip').height(<?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_height']);?>
);
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_width']) {?>
					$('.<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
 .iview-controlNav a img').width(<?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_width']);?>
);
					//$('.<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
 .iview-tooltip').width(<?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_width']);?>
);
			<?php }?>

			// BULLET
			<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_height']) {?>
					$('.<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
 .iview-tooltip div.holder div.container div img').width(<?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_width']);?>
);
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_width']) {?>
					$('.<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
 .iview-tooltip div.holder div.container div img').height(<?php echo intval($_smarty_tpl->tpl_vars['sliderParams']->value['nav_thumbnail_height']);?>
);
			<?php }?>

			// Display timer
			<?php if ($_smarty_tpl->tpl_vars['sliderParams']->value['timer_show']==1||$_smarty_tpl->tpl_vars['sliderParams']->value['timer_show']==2) {?>
					$('.<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
 .iview-timer').hide();
			<?php }?>
		},

	});
});

$( document ).ready(function(){
	$(".img_disable_drag").bind('dragstart', function() {
		return false;
	});
});

// Fix : Slide link, image cant swipe
$( document ).ready(function(){
	// step 1
	var link_event = 'click';

	// step 3
	$(".<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
 .slide_config").on("click",function(){
		
		if(link_event !== 'click'){
			link_event = 'click';
			return;
		}

		if($(this).data('link') != undefined && $(this).data('link') != '') {
			window.open($(this).data('link'),$(this).data('target'));
		}
		
	});

	// step 2
	$(".<?php echo $_smarty_tpl->tpl_vars['class_group']->value;?>
 .slide_config").on('swipe',function(){
		link_event = 'swiped';	// do not click event
	});
});
				 
</script>
<?php }} ?>
