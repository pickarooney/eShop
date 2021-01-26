<html>
<body>

<center>
<br><br><br><br><br><br><br><br>
<p style="font-family:verdana">
Merci, <?php echo $_POST["fname"]; ?> <?php echo $_POST["lname"]; ?>, de votre message !<br>
Nous vous recontacterons rapidement à l'adresse: <?php echo $_POST["email"]; ?>
</p>
<a href="../../index.html">
<img src="../../assets/images/proxelliance-logo-medium.jpg">
</a>

<?php 

//require_once('PHPMailerAutoload.php');
require "vendor/autoload.php";

$errors = '';
//$myemail = 'contact@proxelliance.com';
//$mypassword = 'toula2015';

if(empty($_POST['fname'])  ||
   empty($_POST['lname']) ||
   empty($_POST['email']) ||
   empty($_POST['subject']) ||
   empty($_POST['message']))
{
    $errors .= "\n Erreur: tous les champs sont requis!";
}
$name = $_POST['fname'] . ' ' . $_POST['lname'];
$email_address = $_POST['email'];
$message = 'De : ' . $name . ' (' . $email_address . ')' . PHP_EOL . 'Au sujet de : ' . $_POST['subject'] . PHP_EOL . $_POST['message']  ;
$subject = 'Demande du site Proxelliance : ' . $_POST['subject'];

/*$mail = new PHPMailer();
$mail-> isSMTP();
$mail-> SMTPAuth = true;
$mail-> AuthType = 'LOGIN';
$mail-> SMTPSecure = 'ssl';
$mail-> SMTPHost = 'ssl0.ovh.net';
$mail->Host = 'ssl://ssl0.ovh.net:465';
$mail-> SMTPDebug = 3;
$mail-> Port ='465';
$mail-> isHTML();
$mail-> UserName=$myemail;
$mail-> Password=$mypassword;	
$mail-> SetFrom = $myemail;
$mail-> Subject = $subject;
//$mail-> AddAddress($myemail);
$mail-> AddAddress('pickarooney@gmail.com');
$mail -> Body = $message;*/

$mail = new PHPMailer;
$mail->setFrom('contact@proxelliance.com', 'Contact Proxelliance');
//$mail->addAddress('contact@proxelliance.com', 'Contact Proxelliance');
$mail->addAddress('pickarooney@gmail.com', 'Richard Walsh');
$mail-> Subject = $subject;
$mail->Body = $message;




if( empty($errors))
{
 	$mail -> Send();
}
//else
//print $errors;

?>

</center>

</body>
</html>