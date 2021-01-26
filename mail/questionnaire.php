<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Proxelliance - Audit, Formation, Conseil</title>
		
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="apple-touch-icon" href="apple-touch-icon.png">
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="../../apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../../apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../../apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../../apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon-precomposed" sizes="60x60" href="../../apple-touch-icon-60x60.png" />
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="../../apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="../../apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="../../apple-touch-icon-152x152.png" />
		<link rel="icon" type="image/png" href="../../favicon-196x196.png" sizes="196x196" />
		<link rel="icon" type="image/png" href="../../favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/png" href="../../favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="../../favicon-16x16.png" sizes="16x16" />
		<link rel="icon" type="image/png" href="../../favicon-128.png" sizes="128x128" />
		<link rel='mask-icon' href='../../proxelliance.svg' color='cyan'>
		<meta name="application-name" content="&nbsp;"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="../../mstile-144x144.png" />
		<meta name="msapplication-square70x70logo" content="../../mstile-70x70.png" />
		<meta name="msapplication-square150x150logo" content="../../mstile-150x150.png" />
		<meta name="msapplication-wide310x150logo" content="../../mstile-310x150.png" />
		<meta name="msapplication-square310x310logo" content="../../mstile-310x310.png" />

		<meta http-equiv="refresh" content="10;url=http://www.proxelliance.com/" />
		
        <link rel="stylesheet" href="../../assets/css/iconfont.css">
        <link rel="stylesheet" href="../../assets/fonts/stylesheet.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <link rel="stylesheet" href="../../assets/css/jquery.fancybox.css">
        <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../assets/css/magnific-popup.css">
        <!--        <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">-->


        <!--For Plugins external css-->
        <link rel="stylesheet" href="../../assets/css/plugins.css" />
        <!--Theme custom css -->
        <link rel="stylesheet" href="../../assets/css/style.css">

        <!--Theme Responsive css-->
        <link rel="stylesheet" href="../../assets/css/responsive.css" />

        <script src="../../assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>


	    <body>
	
	

	

	<section id="intro" class="intro">
	
	<a href="../../index.html">
	<img src="../../assets/images/proxelliance-logo-medium.jpg">
	</a>
	<p style="font-family:verdana">
	Merci, <?php echo $_POST["fname"]; ?> <?php echo $_POST["lname"]; ?>, de votre message !<br>
	Nous vous recontacterons rapidement à l'adresse: <?php echo $_POST["email"]; ?>
	</p>
	<p style="font-family:verdana">
	 Vous allez être redirigé vers www.proxelliance.com
	</p>
	</section>


	<?php 

	require "vendor/autoload.php";

	$errors = '';


	if(empty($_POST['fname'])  ||
	   empty($_POST['lname']) ||
	   empty($_POST['email']) ||
	   empty($_POST['societe']))
	{
		$errors .= "\n Erreur: tous les champs sont requis!";
	}
	
	$name = $_POST['fname'] . ' ' . $_POST['lname'];
	$email_address = $_POST['email'];
	$message = 'De : ' . $name . ' (' . $email_address . ')' . PHP_EOL . 'De la société : ' . $_POST['societe'] . PHP_EOL . 'Téléphone fixe: ' . $_POST['fixe']  . PHP_EOL .
			   'Téléphone portable: ' . $_POST['portable'] . PHP_EOL . PHP_EOL . PHP_EOL .
			   'Retour de questionnaire:' . PHP_EOL . PHP_EOL . 'Quel est le contexte actuel de votre entreprise ? ' . $_POST['contexte'] . PHP_EOL . PHP_EOL .
	           'Qu\'est-ce qui a généré ce besoin de formation ? ' . $_POST['besoin'] . PHP_EOL . PHP_EOL .
			   'Combien de personnes souhaitez-vous former ? ' . $_POST['quantity'] . PHP_EOL . PHP_EOL .
			   'Quels sont les profils, fonctions et responsabilités des personnes à former ? ' . $_POST['profils'] . PHP_EOL . PHP_EOL .
			   'Quelles formations ont déjà été suivies sur le sujet par les participants ? ' . $_POST['formations'] . PHP_EOL . PHP_EOL .
			   'Quels sont les objectifs à atteindre pour les stagiaires (Quelles compétences clés à acquérir) ? ' . $_POST['objectifs'] . PHP_EOL . PHP_EOL .
			   'Quand souhaitez-vous suivre la formation ? ' . $_POST['moisform'] . PHP_EOL . PHP_EOL .
			   'Dans quel lieu géographique ? ' . $_POST['lieu'] . PHP_EOL . PHP_EOL .
			   'Ville : ' . $_POST['ville'] . PHP_EOL . PHP_EOL .
			   'Avez-vous une salle de réunion adaptée au nombre de stagiaire et équipée avec un vidéoprojecteur ? ' . $_POST['projecteur'] . PHP_EOL . PHP_EOL .
			   'Avez-vous un budget alloué pour cette formation ? ' . $_POST['budget'] . PHP_EOL . PHP_EOL .
			   'Souhaitez-vous faire financer cette formation par votre OPCA ? ' . $_POST['finance'] . PHP_EOL . PHP_EOL .
			   'Avez-vous d\'autres remarques ? ' . $_POST['commentaire'];
	$subject = 'Demande infos formation pour: ' . $_POST['societe'];


	$mail = new PHPMailer;
	$mail->setFrom('contact@proxelliance.com', 'Contact Proxelliance');
	$mail->addAddress('contact@proxelliance.com', 'Contact Proxelliance');
	//$mail->addAddress('pickarooney@gmail.com', 'Richard WALSH');
	$mail-> Subject = $subject;
	$mail->Body = $message;




	if( empty($errors))
	{
		$mail -> Send();
	}
	//else
	//print $errors;

	?>

	</body>
	
</html>