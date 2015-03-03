<?php

	//INCLUIMOS LAS LIBRERIAS DE FB
	require_once( 'lib/Facebook/FacebookSession.php');
	require_once( 'lib/Facebook/FacebookRequest.php' );
	require_once( 'lib/Facebook/FacebookResponse.php' );
	require_once( 'lib/Facebook/FacebookSDKException.php' );
	require_once( 'lib/Facebook/FacebookRequestException.php' );
	require_once( 'lib/Facebook/FacebookRedirectLoginHelper.php');
	require_once( 'lib/Facebook/FacebookAuthorizationException.php' );
	require_once( 'lib/Facebook/GraphObject.php' );
	require_once( 'lib/Facebook/GraphUser.php' );
	require_once( 'lib/Facebook/GraphSessionInfo.php' );
	require_once( 'lib/Facebook/Entities/AccessToken.php');
	require_once( 'lib/Facebook/HttpClients/FacebookCurl.php' );
	require_once( 'lib/Facebook/HttpClients/FacebookHttpable.php');
	require_once( 'lib/Facebook/HttpClients/FacebookCurlHttpClient.php');

	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\FacebookResponse;
	use Facebook\FacebookSDKException;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookAuthorizationException;
	use Facebook\GraphObject;
	use Facebook\GraphUser;
	use Facebook\GraphSessionInfo;
	use Facebook\FacebookHttpable;
	use Facebook\FacebookCurlHttpClient;
	use Facebook\FacebookCurl;

	
	//INICIAMOS LA SESSION
	 session_start();

	//COMPROBAMOS SI EL USUARIO QUIERE CERRAR
	 if(isset($_REQUEST['logout'])){
	 	session_start();
	 	$_SESSION=array();
	 	session_destroy();
	 	header('Location: ./index.php');
	 }
	
	//CREAMOS LAS VARIABLES CON LOS DATOS QUE NOS DA LA WEB DE FB DEVELOPERS
	$app_id = '619275001551926';
	$app_secret = 'b6ff9dc1c622a8834a26bafaa1550f4a';
	$redirect_url='http://rmwebconsulting.com/apps/loginFB/';

	//INICIALIZAMOS LA APLICACION, PARA ELLO CREAMOS EL OBJ HELPER Y OBTENEMOS LA VARIABLE SESSION
	 FacebookSession::setDefaultApplication($app_id,$app_secret);
	 $helper = new FacebookRedirectLoginHelper($redirect_url);
	 $sess = $helper->getSessionFromRedirect();

	 //COMPROBAMOS SI NO EXISTE LA VARIABLE SESSION
	if(isset($_SESSION['fb_token'])){
		$sess = new FacebookSession($_SESSION['fb_token']);
		try{
			$sess->Validate($id, $secret);
		}catch(FacebookAuthorizationException $e){
			print_r($e);
		}
	}

	$loggedin = false;
	$login_url = $helper->getLoginUrl(array('email'));

	//lCERRAMOS SESION
	$logout = 'http://rmwebconsulting.com/apps/loginFB/index.php?logout=true';

	//SI LA VARIABLE SESSION EXISTE MOSTRAMOS EL NOMBRE
 	if(isset($sess)){
 		$_SESSION['fb_token']=$sess->getToken();
 		$request = new FacebookRequest($sess,'GET','/me');
		$response = $request->execute();
		$graph = $response->getGraphObject(GraphUser::classname());
		// USAMOS EL OBJ GRAPH PA MOSTRAR EL NOMBRE EL EMAIL Y LA FOTOD EL USUARIO
		$id = $graph->getId();
		$name= $graph->getName();
		$image = 'https://graph.facebook.com/'.$id.'/picture?width=300';
		$loggedin  = true;
	}
	

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login con Facebook</title>
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/slider.css">
		<link rel="stylesheet" href="css/docs.min.css">
		<!-- Important Owl stylesheet -->
		<link rel="stylesheet" href="owl-carousel/owl.carousel.css">
		<!-- Default Theme -->
		<link rel="stylesheet" href="owl-carousel/owl.theme.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<!--Icon-->
		<link rel="shortcut icon" href="https://moodle.iespuertodelacruz.es/theme/standard/favicon.ico" />
  </head>
  <body>
  	<nav class="navbar navbar-default">
			<div id="nav" class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Login Facebook</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right" >
						<?php if(!$loggedin){ ?>
						<li><a href="#">Regístrate</a></li>
						<li><a class="iniciosesion" data-toggle="modal" data-target=".bs-example-modal-sm">Inciar sesión</a></li>
						<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Ayuda<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#" style="color:red">Visita el centro de ayuda >></a></li>
									<li><a href="#">Guía para empezar</a></li>
									<li><a href="#">Ver las preguntas frecuentes</a></li>
								</ul>
							</li>
						<li><a href="#" style="color:#007a87">Publica tu anuncio</a></li>
						<?php }else{?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" style="color:#777" data-toggle="dropdown" role="button" aria-expanded="false"><img src="<?php echo $image; ?>" alt="<?php echo $name; ?>" style="width:28px" class="img-circle"> <?php echo $name; ?><span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">Panel de control</a></li>
									<li><a href="#">Tus viajes</a></li>
									<li><a href="#">Wish Lists</a></li>
									<li><a href="#">Grupos</a></li>
									<li><a href="#">Invita a tus amigos <strong style="color:#00d1c1">Nuevo</strong></a></li>
									<li><a href="#">Editar Perfil</a></li>
									<li><a href="#">Cuenta</a></li>
									<li class="divider"></li>
									<li><a href="<?php echo $logout; ?>" style="color:red">Cerrar sesion</a></li>
								</ul>
							</li>
						<li><a href="">Mensajes</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Ayuda<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#" style="color:red">Visita el centro de ayuda >></a></li>
									<li><a href="#">Ver las preguntas frecuentes</a></li>
								</ul>
							</li>
						<li><a href="#" style="color:#007a87">Publica tu anuncio</a></li>
						<?php } ?>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
    <!--SLIDER-->
		<div id="owl-demo" class="owl-carousel owl-theme">
 
			<div class="item"><img class="img-responsive" src="images/image1.jpg" ></div>
			<div class="item"><img class="img-responsive" src="images/image2.jpg" ></div>
			<div class="item"><img class="img-responsive" src="images/image3.jpg" ></div>

		</div>
		<!--/SLIDER-->
		<div class="container">
			<div class="col-md-4">
				<h2 style="text-align:center"><i class="fa fa-bed"></i></h2>
				<h4 style="text-align:center">Nuestra casa es tu casa</h4>
				<p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
			</div>
			<div class="col-md-4">
				<h2 style="text-align:center"><i class="fa fa-home"></i></h2>
				<h4 style="text-align:center">A veces los lugares más increíbles están cerca de casa</h4>
				<p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
			</div>
			<div class="col-md-4">
				<h2 style="text-align:center"><i class="fa fa-globe"></i></h2>
				<h4 style="text-align:center">Tu casa es el mundo</h4>
				<p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
			</div>
		</div>
		
		<footer class="bs-docs-footer" role="contentinfo">
			<div class="container">
				<h2 style="text-align:center">Nuestro código:</h2>
				<ul class="social">
					<li class="footer-icon"><a href="https://github.com/RafaelMartin93/LoginFacebook" target="_blank" class="social-icon" data-toggle="tooltip" data-placement="bottom" title="Código en GitHub"><i style="font-size: 24px;text-align:center" class="fa fa-github"></i></a></li>
				</ul>			
				<p>Diseñado por Rafael Martín & Eduardo Hernández.</p>
			</div>
		</footer>
		
		<!-- Modal -->
		<div  class="modal fade bs-example-modal-sm" id="miModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-body">
						<p><a href="<?php echo $login_url; ?>"><button class="btn btn-primary" id="fb"><i class="fa fa-facebook"></i> Iniciar sesión con Facebook</button></a></p>
						<p><a href="#"><button class="btn btn-primary" id="gp"><i class="fa fa-google-plus"></i> Iniciar sesión con Google</button></a></p>
						<hr/>
						<form>
							<div class="form-group">
								<input type="email" id="email" class="form-control" id="exampleInputEmail1" placeholder="Correo electrónico">
							</div>
							<div class="form-group">
								<input type="password" id="pass" class="form-control" id="exampleInputPassword1" placeholder="Contraseña">
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox"> Recordar contraseña
								</label>
							</div>
							<div class="checkbox">
								<p style="text-align:right"><a style="color:red" href="#">¿Has olvidado tu contraseña?</a></p>
							</div>							
							<p style="text-align:center"><button type="submit" class="btn btn-danger" style="width:100%" id="login">Iniciar sesión</button></p>
							<hr>
							<p>¿No tienes una cuenta? <a style="color:red" href="#">Regístrate</a></p>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<!--Scripts -->		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.js"></script>
		<script> 
			$(document).ready(function() {
 
				$("#owl-demo").owlCarousel({

						navigation : false, // Show next and prev buttons
						slideSpeed : 300,
						paginationSpeed : 400,
						singleItem:true,
						autoPlay:5000

				});
				
				
				$('[data-toggle="tooltip"]').tooltip();
				
						
			});
			
		</script>
		
  </body>
</html>


