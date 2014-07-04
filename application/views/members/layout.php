<?php 
/**
 * Layout for the members interface.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Member View
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo html::specialchars($site_name) ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo url::file_loc('images'); ?>themes/ci-theme/images/favicon.ico" >
    <link rel="apple-touch-icon" href="<?php echo url::file_loc('images'); ?>themes/ci-theme/images/apple-touch-icon.png" >

    <!-- Custom styles for this template -->
    <link href='<?php echo Kohana::config('core.site_protocol'); ?>://fonts.googleapis.com/css?family=Open+Sans:400italic,800italic,400,800' rel='stylesheet' type='text/css'>
    <link href='<?php echo URL::base(TRUE); ?>themes/ci-theme/css/fontello-embedded.css' rel='stylesheet' type='text/css'>
    <link href='<?php echo URL::base(TRUE); ?>themes/ci-theme/css/fontello-ie7.css' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php echo $header_block; ?>
    	<link rel="stylesheet" type="text/css" href="http://caminosdelavilla.org/media/css/jquery-ui-themeroller.css?m=1398453537&" />
<link rel="stylesheet" type="text/css" href="http://caminosdelavilla.org/media/css/global.css?m=1398453537&" />
<link rel="stylesheet" type="text/css" href="http://caminosdelavilla.org/themes/default/css/base.css?m=1398453537&" />
<link rel="stylesheet" type="text/css" href="http://caminosdelavilla.org/themes/default/css/accordion.css?m=1398453537&" />
<link rel="stylesheet" type="text/css" href="http://caminosdelavilla.org/themes/default/css/slider.css?m=1398453537&" />
<link rel="stylesheet" type="text/css" href="http://caminosdelavilla.org/themes/default/css/style.css?m=1398453537&" />
	<link rel="stylesheet" type="text/css" href="<?php echo URL::base(TRUE); ?>themes/ci-theme/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo URL::base(TRUE); ?>themes/ci-theme/css/styles.css" />
	<?php
	// Action::header_scripts_member - Additional Inline Scripts
	Event::run('ushahidi_action.header_scripts_member');
	?>
	<?php
	// Action::header_scripts - Additional Inline Scripts from Plugins
	Event::run('ushahidi_action.header_scripts');
	?>
	<script type="text/javascript" charset="utf-8">
		<?php if ($form_error): ?>
			$(document).ready(function() { $("#addedit").show(); });
		<?php endif; ?>
	</script>
	
	<script type="text/javascript" src="<?php echo URL::base(TRUE); ?>themes/ci-theme/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo URL::base(TRUE); ?>themes/ci-theme/js/lightbox-2.6.min.js?"></script>
	<script type="text/javascript" src="<?php echo URL::base(TRUE); ?>themes/ci-theme/js/scripts.js?m=1403721309&"></script>
	
</head>
<body id="page" class="dashboard" style="background:none;">

	<?php echo $header_nav; ?>

	<div class="container-fluid wg-inner-header">
		  <div class="container">
			<div class="starter-template">
			  <div class="row">
				 <div class="col-md-offset-2 col-md-8 .col-xs-12 text-center">
				  <div id="logo-home" class="stellar" data-stellar-ratio="4">
					<img src="<?php echo url::file_loc('images'); ?>themes/ci-theme/images/logo-caminosvilla-2.png" alt="Los Caminos de la Villa" class="img-responsive"/>
				  </div>
				</div>
				 </div>
			</div>
		  </div>

		</div><!-- /.container-fluid -->

		<div class="container main-content" style="margin-top:40px;">

			  <div class="row content-info">
				
				<div class="col-xs-12 col-md-10 col-md-offset-1">


					<div class="holder">
							<!-- header -->
							<div id="header" style="height:auto;">
								<?php /*
								<!-- info-nav -->
								<div class="info-nav">
									<ul>
										<li><a href="http://forums.ushahidi.com/"><?php echo Kohana::lang('ui_admin.forum');?></a></li>
									</ul>
									<div class="info-search"><?php echo form::open('members/reports', array('id' => 'info-search', 'method' => 'get')); ?><input type="text" name="k" class="info-keyword" value=""> <a href="javascript:info_search();" class="btn"><?php echo Kohana::lang('ui_admin.search');?></a><?php echo form::close(); ?></div>
									<div style="clear:both"></div>
								</div>
								<!-- title -->
								<h1><?php echo $site_name ?></h1>
								*/
								?>
								<!-- nav-holder -->
								<div class="nav-holder">
									<!-- main-nav -->
									<ul class="nav nav-tabs">
										<?php foreach($main_tabs as $page => $tab_name): ?>
											<li <?php if($this_page==$page) echo 'class="active"' ;?>><a href="<?php echo url::site(); ?>members/<?php echo $page; ?>" ><?php echo $tab_name; ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>
							<!-- content -->
							<div id="content">
								<div class="bg">
									<?php print $content; ?>
								</div>
							</div>
						</div>



				</div>
			</div>

	</div>




	

<div class="container-fluid wg-footer">

  <div class="container">
	  
	  <div class="row">

		  <div class="col-md-6 col-xs-12">
			  <div class="row">
				 <a href="index.html" title="Los Caminos de la Villa">
					  <img src="<?php echo url::file_loc('images'); ?>themes/ci-theme/images/logo-cv-small.png" alt="Los Caminos de la Villa" />
				 </a>
			  </div>

			  <div class="row wg-footer-menus">


				  <div class="col-md-6 col-xs-12">
					 <ul>
						<li><a href="<?php echo url::site(); ?>reports">Reclamos</a></li>
						<li><a href="<?php echo url::site(); ?>ci_ask">Pedido de Información</a></li>
						<li><a href="<?php echo url::site(); ?>blog">Novedades</a></li>
						<li><a href="<?php echo url::site(); ?>contact">Contactanos</a></li>
					  </ul>  
				  </div>

				  <div class="col-md-6 col-xs-12">
					  <ul>
						<li><a>Ayuda</a></li>
						<li><a href="<?php echo url::site(); ?>faq">Preguntas Frecuentes</a></li>
						<li><a href="<?php echo url::site(); ?>how">Como Funciona</a></li>
						<li><a href="<?php echo url::site(); ?>contact">Reportar Error</a></li>
					  </ul>  
				  </div>

			  </div>
		   
		  </div>

		  <div class="col-md-offset-2 col-md-2 col-sm-4 col-xs-12 text-center">
			 <div class="row">
			  <h5>Financiado por</h5>
			 </div>
			 <div class="row wg-footer-logos">
				  <a href="http://www.avina.net/esp/" target="_blank" title="#" class="who animate"><img src="<?php echo url::file_loc('images'); ?>themes/ci-theme/images/avina-logo.png" alt="Omidyar"></a> 
				  <a href="http://www.omidyar.com/" target="_blank" title="#" class="who animate"><img src="<?php echo url::file_loc('images'); ?>themes/ci-theme/images/omidyar-logo.png" alt="Avina"></a>  
			 </div>
		  </div>
		  
		  <div class="col-md-2 col-sm-4 col-xs-12 text-center">
			 <div class="row">
				  <h5>Desarrollado por</h5>
			  </div>
			  <div class="row wg-footer-logos">
				<a href="http://www.winguweb.org/" target="_blank" title="#" class="who animate"><img src="<?php echo url::file_loc('images'); ?>themes/ci-theme/images/wingu-logo.png" alt="Wingu" /></a> 
				<a href="http://www.acij.org.ar/" target="_blank" title="#" class="who animate"><img src="<?php echo url::file_loc('images'); ?>themes/ci-theme/images/acij-logo.png" alt="ACIJ" /></a>  
			  </div>
		  </div>

	  </div>

	  <div class="row wg-footer-bottom">
		  <div class="col-md-6 col-xs-12">
			  <p><a href="<?php echo url::site(); ?>about" title="#">Sobre <strong>CaminosdelaVilla.org</strong></a></p>
		  </div>

		  <div class="col-md-6 col-xs-12">
			  <p class="align-right">Diseñado por <a href="http://hechoporcosmos.com" title="Cosmos" target="_blank"><img src="<?php echo url::file_loc('images'); ?>themes/ci-theme/images/cosmos.png" alt="Hecho Por Cosmos" /></a></p>
		  </div>
		  <div class="col-md-6 col-xs-12">
	  </div>

  </div>

</div><!-- /.container-fluid -->


<script> /*
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '594472380623282',
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

  FB.Event.subscribe('auth.authResponseChange', function(response) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (response.status === 'connected') {
      esta_logeado();
    } else if (response.status === 'not_authorized') {
      // In this case, the person is logged into Facebook, but not into the app, so we call
      // FB.login() to prompt them to do so. 
      do_fb_login();
    } else {
      // In this case, the person is not logged into Facebook, so we call the login() 
      // function to prompt them to do so. Note that at this stage there is no indication
      // of whether they are logged into the app. If they aren't then they'll see the Login
      // dialog right after they log in to Facebook.  //FB.login();
      do_fb_login();
    }
  });
  };

  // Load the SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "https://connect.facebook.net/es_LA/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));


  function esta_logeado() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      $('usuario-fb-name').text(response.name);
      $('usuario-fb-picture').html('https://graph.facebook.com/'+ response.id +'/picture?type=large');
    });
  }

  function show_fb_login() {
    $('show-fb-login').show();
  }

  $('show-fb-login').click( function() {
    FB.login();
  });
*/
</script>

</body>
</html>

