<?php
include "vp_dbconfig.php";
/*
	status = 0 : nothing send
	status = 1 : only sms sent
	status = 2 : only email sent
	status = 3 : both sent
*/



$order_id = $_REQUEST['order_id'];
$status = $_REQUEST['status'];

$msgArray = array();
$mailArray = array();


$msgArray[2] = "Thank you for using FAR. The user code to view your video is shipped. Please visit http://bit.ly/1qXAjO to download the app. Make personal wishes come alive!";
$msgArray[3] = "Thank you for using FAR. The user code to view your video is delivered. Please visit http://bit.ly/1qXAjO to download the app. Make personal wishes come alive!";


$mailArray[2] = "Your order is out for shipping.";
$mailArray[3] = "Your order is delivered.";


$messageToSend = $msgArray[$status];
$emailToSend = $mailArray[$status];


$theOrder = Mage::getModel('sales/order')->load($order_id,'increment_id');
$customer_mobile = $theOrder->getBillingAddress()->getTelephone();
$customer_email = $theOrder->getBillingAddress()->getEmail();


/*
$customer_mobile = "9716251962";
$customer_email = "varunk@flaberry.com";
echo "Mobile No. is : $customer_mobile";
echo "<br>";
echo "Email ID is : $customer_email";
echo "<br><br>";
*/



$url = "http://trans.voicetree.co/GatewayAPI/rest";
$data = array(
	'method'=>'SendMessage',
	'send_to'=>$customer_mobile,
	'msg'=> $messageToSend,
	'msg_type'=>'TEXT',
	'loginid'=>'Voice123',
	'auth_scheme'=>'plain',
	'password'=>'4443700002',
	'v'=>'1.0',
	'format'=>'text',
	'mask'=>'MYOPTR'
	);
  $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_TIMEOUT, 30);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_FORBID_REUSE, 0);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
       curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       $result = curl_exec($ch);     
//SEND MESSAGE
if(stristr($result, 'success'))
{
	$msgresult = 1;
}
else
{
	if(stristr($result, 'error'))
	{
		$msgresult = 0;
	}
}


require('googleemail/class.phpmailer.php');                                      //Include PHPMailer Class/////
try
{
	$mail = new PHPMailer();                                                     //New instance, with exceptions enabled
	$mail->IsSMTP();                                                             // tell the class to use SMTP
	$mail->SMTPAuth   = true;                                                    // enable SMTP authentication
	//$mail->SMTPDebug  = true; 
	$mail->Port       = 465;                                                     // set the SMTP server port
	$mail->Host       = "smtp.gmail.com";                                        // SMTP server
	$mail->Username   = "varunk@flaberry.com";                                  // SMTP server username
	$mail->Password   = "01nanotech51";                                           // SMTP server password
	$mail->SMTPSecure = "ssl";
	//$mail->AddReplyTo("pratyooshm@floshowers.com","First Last");
	$mail->From       = "Team Flaberry";
	$mail->FromName   = "Team Flaberry";
	//$to = "pratyoosh.mahajan@gmail.com";
	$tos=explode(',',$customer_email);
	foreach($tos as $to)
	$mail->AddAddress($to);
	$mail->Subject  = $subject;
	//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 50;                                                       // set word wrap
	$mail->Body = $emailToSend;
	$mail->IsHTML(true);                                                          // send as HTML		
	if($mail->Send() == true)
	{
		$mailresult = 1;
	}
}
catch (phpmailerException $e)
{
	$mailresult = 0;
}





if($msgresult == 0 && $mailresult == 0)
{
	echo "0";
}
else
{
	if($msgresult == 1 && $mailresult == 0)
	{
		echo "1";
	}
	else
	{
		if($msgresult == 0 && $mailresult == 1)
		{
			echo "2";
		}
		else
		{
			if($msgresult == 1 && $mailresult == 1)
			{
				echo "3";
			}
		}
	}
}
?>