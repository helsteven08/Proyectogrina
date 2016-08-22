<?php /*%%SmartyHeaderCode:57452841757b5ec73b5cd17-40953814%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6e68e884b52fe86d387be7194e28143f940b478e' => 
    array (
      0 => '/var/www/html/bocatelly/themes/ap_juice/modules/leobootstrapmenu/views/templates/hook/megamenu.tpl',
      1 => 1471540207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '57452841757b5ec73b5cd17-40953814',
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57b73d2e792e64_12252894',
  'has_nocache_code' => false,
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b73d2e792e64_12252894')) {function content_57b73d2e792e64_12252894($_smarty_tpl) {?><div id="leo-megamenu" class="clearfix">
<nav id="cavas_menu"  class="sf-contener leo-megamenu">
    <div class="" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle btn-outline-inverse" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Navegación Toggle</span>
                <span class="fa fa-bars"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div id="leo-top-menu" class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav megamenu"><li class="home" >
						<a href="http://190.60.31.15/bocatelly/index.php" target="_self" class="has-category"><span class="menu-title">Casa</span></a></li><li class="" >
						<a href="" target="_self" class="has-category"><span class="menu-title">Categorías</span></a></li><li class=" parent dropdown aligned-fullwidth " >
                    <a href="" class="dropdown-toggle has-category" data-toggle="dropdown" target="_self"><span class="menu-title">En venta</span><b class="caret"></b></a><div class="dropdown-sub dropdown-menu"  style="width:850px" ><div class="dropdown-menu-inner"><div class="row"><div class="mega-col col-sm-3" > <div class="mega-col-inner "><div class="leo-widget">No hay se encuentra plantilla para el m&oacute;dulo leobootstrapmenu</div><div class="leo-widget">No hay se encuentra plantilla para el m&oacute;dulo leobootstrapmenu</div></div></div><div class="mega-col col-sm-5" > <div class="mega-col-inner "><div class="leo-widget">No hay se encuentra plantilla para el m&oacute;dulo leobootstrapmenu</div></div></div><div class="mega-col col-sm-4" > <div class="mega-col-inner "><div class="leo-widget">No hay se encuentra plantilla para el m&oacute;dulo leobootstrapmenu</div></div></div></div></div></div></li><li class="parent dropdown  aligned-left" ><a class="dropdown-toggle has-category" data-toggle="dropdown" href="" target="_self"><span class="menu-title">Caracteristicas</span><b class="caret"></b></a><div class="dropdown-menu level1"  ><div class="dropdown-menu-inner"><div class="row"><div class="mega-col col-sm-12" data-type="menu" ><div class="mega-col-inner "><ul><li class="parent dropdown-submenu  " ><a class="dropdown-toggle" data-toggle="dropdown" href=""><span class="menu-title">Café exprés</span><b class="caret"></b></a><div class="dropdown-menu level2"  ><div class="dropdown-menu-inner"><div class="row"><div class="mega-col col-sm-12" data-type="menu" ><div class="mega-col-inner "><ul><li class="  " ><a href="" target="_self><span class="menu-title">Espresso Yen</span></a></li><li class="  " ><a href="" target="_self><span class="menu-title">Americano</span></a></li><li class="  " ><a href="" target="_self><span class="menu-title">Café helado</span></a></li></ul></div></div></div></div></div></li><li class="  " ><a href="" target="_self><span class="menu-title"> Té de oolong</span></a></li><li class="  " ><a href="http://190.60.31.15/bocatelly/index.php?id_category=12&controller=category" target="_self><span class="menu-title">Espresso bebidas</span></a></li></ul></div></div></div></div></div></li></ul>
        </div>
    </div>
</nav>
</div>

<script type="text/javascript">
// <![CDATA[
	var current_link = "http://190.60.31.15/bocatelly/";
	//alert(request);
    var currentURL = window.location;
    currentURL = String(currentURL);
    currentURL = currentURL.replace("https://","").replace("http://","").replace("www.","").replace( /#\w*/, "" );
    current_link = current_link.replace("https://","").replace("http://","").replace("www.","");
    isHomeMenu = 0;
    if($("body").attr("id")=="index") isHomeMenu = 1;
    $(".megamenu > li > a").each(function() {
        menuURL = $(this).attr("href").replace("https://","").replace("http://","").replace("www.","").replace( /#\w*/, "" );
		if( (currentURL == menuURL) || (currentURL.replace(current_link,"") == menuURL) || isHomeMenu){
			$(this).parent().addClass("active");
            return false;
		}
    });
// ]]>
</script>
<script type="text/javascript">
    (function($) {
        $.fn.OffCavasmenu = function(opts) {
            // default configuration
            var config = $.extend({}, {
                opt1: null,
                text_warning_select: "Por favor seleccione uno de quitar?",
                text_confirm_remove: "¿Seguro de eliminar la fila de pie de página?",
                JSON: null
            }, opts);
            // main function
            // initialize every element
            this.each(function() {
                var $btn = $('#cavas_menu .navbar-toggle');
                var $nav = null;
                if (!$btn.length)
                    return;
                var $nav = $('<section id="off-canvas-nav" class="leo-megamenu"><nav class="offcanvas-mainnav"><div id="off-canvas-button"><span class="off-canvas-nav"></span>Cerrar</div></nav></section>');
                var $menucontent = $($btn.data('target')).find('.megamenu').clone();
                $("body").append($nav);
                $("#off-canvas-nav .offcanvas-mainnav").append($menucontent);
                $("#off-canvas-nav .offcanvas-mainnav").css('min-height',$(window).height()+30+"px");
                $("html").addClass ("off-canvas");
                $("#off-canvas-button").click( function(){
                        $btn.click();	
                } );
                $btn.toggle(function() {
                    $("body").removeClass("off-canvas-inactive").addClass("off-canvas-active");
                }, function() {
                    $("body").removeClass("off-canvas-active").addClass("off-canvas-inactive");
                });
            });
            return this;
        }
    })(jQuery);
    $(document).ready(function() {
        jQuery("#cavas_menu").OffCavasmenu();
        $('#cavas_menu .navbar-toggle').click(function() {
            $('body,html').animate({
                scrollTop: 0
            }, 0);
			if ($('html').hasClass('fp-enabled')) {
				if ($('body').hasClass('off-canvas-active'))
				{									
					$.fn.fullpage.pau();					
				}
				else
				{						
					$.fn.fullpage.con();					
				};
			};
            return false;
        });
    });
    $(document.body).on('click', '[data-toggle="dropdown"]' ,function(){
        if(!$(this).parent().hasClass('open') && this.href && this.href != '#'){
            window.location.href = this.href;
        }
    });
</script>
<?php }} ?>
