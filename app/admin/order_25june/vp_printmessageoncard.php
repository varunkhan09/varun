<html>
<head>
	<script type="text/javascript" src="jquery-latest.min.js"></script>

	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/jspdf.js"></script>
	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/basic.js"></script>
	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/jspdf.debug.js"></script>
	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/libs/base64.js"></script>
	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/libs/sprintf.js"></script>

	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/libs/png_support/png.js"></script>
	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/libs/png_support/zlib.js"></script>
	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/libs/Deflate/deflate.js"></script>
	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/libs/Deflate/adler32cs.js"></script>

	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/jspdf.plugin.autoprint.js"></script>
	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/jspdf.plugin.png_support.js"></script>
	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/jspdf.plugin.addimage.js"></script>

	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jspdf/jspdf.plugin.split_text_to_size.js"></script> 

	<script type="text/javascript" src="printapi/vendor.payment.challan.js"></script> 
</head>
<body></body>



<?php
	include 'vp_dbconfig.php';
	$orderid = $_REQUEST['orderid'];
	$orderid_array = explode("_", $orderid);

for($x=0; $x<count($orderid_array)-1; $x++)
{
	$theOrder = Mage::getModel('sales/order')->loadByIncrementId($orderid_array[$x]);	
	
	$isVoiceMsg =  checkforVoiceMessage($theOrder);
	
	$gift_message_id = $theOrder->getGiftMessageId();
	$gift_message = Mage::getModel('giftmessage/message');
	$gift_message->load($gift_message_id);
	$senderName = $gift_message->getData('sender');
	$recipientName = $gift_message->getData('recipient');
	$cardMessage = $gift_message->getData('message');

	if($cardMessage == "")
	{
		$cardMessage = "Best Wishes...".PHP_EOL."Flaberry Team";
	}

	//echo "$senderName : $recipientName : $cardMessage<br>";

	$message_array = array
	(
		'order_id' => $orderid_array[$x],
		'recipientName' => str_replace("&#8203;", "", $recipientName),
		'sendorName' => str_replace("&#8203;", "", $senderName),
		'cardMessage' => str_replace("&#8203;", "", $cardMessage),
		'voiceMsg' =>  $isVoiceMsg === TRUE ? "Please call at +91-8010-386-062 from {$recipientTelephone} to listen voice message." : "",
	);

	$varun_json = json_encode($message_array);
?>
	<script>
		$(function(){
			var json = <?php echo $varun_json; ?>;
			var recipientName = json['recipientName'].trim();//+"You don't it just needs to get the PSF";;
			var sendorName = json['sendorName'].trim();
			var recipient="";
			var sendor ="";

			// var height=8.5 , width = 5.8;
			var height=8.5 , width = 5.8;
			var pdf = new jsPDF('p','in',[height, width]);

			var card_message = json['cardMessage'].trim();


			if(recipientName !== "NULL" )
			{
			recipient = recipientName;
			}

			if(sendorName !== "NULL")
			{
			sendor = sendorName;
			}


			var message_len = card_message.length;



			if(json['voiceMsg'])
			{
			var voicemsg = pdf.setFont("times", "italic").setFontSize(10).splitTextToSize(json['voiceMsg'],4.5);  
			pdf.text(0.8, 0.875, voicemsg);
			}


			if(card_message === "NULL")
			{
			pdf.setFontSize(28);
			pdf.setFont("times", "bolditalic");
			pdf.myText("Best Wishes...", {align: "center"}, width/3, height/2+1);
			pdf.setFontSize(16);
			pdf.setFont("helvetica", "normal");
			pdf.myText("Flaberry Team", {align: "center"}, width/3, height-2);
			pdf.autoPrint("card_mesage");
			pdf.output("dataurlnewwindow");
			return true;
			}






			// if message is   <= 125 character long.
			if(message_len <= 125)
			{  
			var msgFontSize=14,recpFontSize = 16;

			var recipientVerticalOffset =0;
			if(message_len > 100){
			recipientVerticalOffset=height/2 + 1.2;
			}else if(message_len > 50 && message_len <= 100){
			recipientVerticalOffset=height/2 + 1.3;
			}else if(message_len <= 50){
			recipientVerticalOffset=height/2 + 1.5;
			}

			var lines="",verticalOffset=0;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
			recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;

			verticalOffset = recipientVerticalOffset + 0.25; // inches on a 8.5 x 11 inch sheet.
			lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
			pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
			verticalOffset += (lines.length + 0.5) * msgFontSize / 72;

			recipientVerticalOffset=0;
			recipientVerticalOffset += verticalOffset+0.5;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
			pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);

			}else if(message_len > 125 && message_len <= 250){
			var msgFontSize=14,recpFontSize = 16;

			var recipientVerticalOffset=0;//height/2;
			if(message_len <= 150){
			recipientVerticalOffset =  height/2 + 1;
			}
			else if(message_len > 150 && message_len <= 200){
			recipientVerticalOffset =  height/2 + 0.75;
			}
			else if(message_len > 200){
			recipientVerticalOffset =  height/2 + 0.5;
			}

			var lines="",verticalOffset=0;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
			recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;

			verticalOffset = recipientVerticalOffset + 0.25; // inches on a 8.5 x 11 inch sheet.
			lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
			pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
			verticalOffset += (lines.length + 0.5) * msgFontSize / 72;

			recipientVerticalOffset=0;
			recipientVerticalOffset += verticalOffset+0.5;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
			pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);

			}
			else if(message_len > 250 && message_len <= 350){
			///.
			var recipientVerticalOffset= 0;//=height/2;
			var msgFontSize=14,recpFontSize = 16;
			var lines="",verticalOffset=0;

			if(message_len > 300){
			recipientVerticalOffset=height/2 + 0.35;
			}else if(message_len > 250 && message_len <= 300){
			recipientVerticalOffset=height/2 + 0.45;
			}

			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
			recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;

			verticalOffset = recipientVerticalOffset + 0.25; // inches on a 8.5 x 11 inch sheet.
			lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
			pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
			verticalOffset += (lines.length + 0.5) * msgFontSize / 72;

			recipientVerticalOffset=0;
			recipientVerticalOffset += verticalOffset+0.5;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
			pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);

			}
			else if(message_len > 350 && message_len <= 500){
			//alert(height/2);
			//  pdf.line(0.8, height/2, 5.0, height/2); 
			var recipientVerticalOffset=0;

			if(message_len >350 && message_len<=400){
			recipientVerticalOffset = height/2 +0.2;
			}else{
			recipientVerticalOffset = height/2 +0.1; /// edit +0.1 to minus // -0.15
			}

			var msgFontSize=14,recpFontSize = 16;
			var lines="",verticalOffset=0;

			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  

			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
			recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;

			verticalOffset = recipientVerticalOffset +0.2; // inches on a 8.5 x 11 inch sheet. // edit

			lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 

			pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
			verticalOffset += (lines.length + 0.5) * msgFontSize / 72; 
			recipientVerticalOffset=0;
			recipientVerticalOffset += verticalOffset+0.35; // edited 0.375
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
			pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);

			}
			else if(message_len > 500 && message_len <= 800){
			// pdf.line(0.8, height/2, 5.0, height/2); 
			///.
			var recipientVerticalOffset=0;
			var msgFontSize=14,recpFontSize = 16;
			var lines="",verticalOffset=0;

			// shift upward when in recipient have much content
			if(recipient.length <=75){
			recipientVerticalOffset=(height/2) - 0.75;
			}else if(recipient.length>75 && recipient.length <=100){
			recipientVerticalOffset=(height/2)-0.9;
			}else if(recipient.length>100 && recipient.length <=150){
			recipientVerticalOffset=(height/2) - 1.15;
			}else if(recipient.length>150){
			// rare case .
			recipientVerticalOffset=(height/2) - 1.5;
			}

			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
			recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
			verticalOffset = recipientVerticalOffset +0.1;

			lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
			pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
			verticalOffset += (lines.length + 0.5) * msgFontSize / 72;

			recipientVerticalOffset=0;
			recipientVerticalOffset += verticalOffset+0.45;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);   


			} else if(message_len > 800 && message_len <= 1000){
			/// test
			//pdf.line(0.8, height/2, 5.0, height/2); 
			var recipientVerticalOffset=0;

			if(message_len >800 && message_len<=900){
			recipientVerticalOffset = height/2-1.75 ;//0.5;
			}else{
			var recipientVerticalOffset=(height/2)-2.25;
			}

			var msgFontSize=14,recpFontSize = 16;
			var lines="",verticalOffset=0;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
			recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;

			verticalOffset = recipientVerticalOffset + 0.2;
			lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
			pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
			verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
			recipientVerticalOffset=0;
			recipientVerticalOffset += verticalOffset + 0.75;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);

			}

			else if(message_len > 1000 && message_len <= 1300){
			/////
			//pdf.line(0.8, height/2, 5.0, height/2);
			var recipientVerticalOffset=1;//(height/2) - 3 ;
			var msgFontSize=14,recpFontSize = 16;
			var lines="",verticalOffset=0;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
			recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;

			verticalOffset = recipientVerticalOffset+0.25; // inches on a 8.5 x 11 inch sheet.
			lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
			pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
			verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
			recipientVerticalOffset=0;

			if(sendor.length < 50){
			recipientVerticalOffset += verticalOffset+0.85;
			}else{
			recipientVerticalOffset += verticalOffset+0.7;
			}

			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
			pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);   

			}
			else if(message_len > 1300 && message_len <=1500){
			///.
			var recipientVerticalOffset = 0.5;
			var msgFontSize=14,recpFontSize = 16;
			var lines="",verticalOffset=0;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
			recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;

			verticalOffset = recipientVerticalOffset+0.25; // inches on a 8.5 x 11 inch sheet.
			lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
			pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
			verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
			recipientVerticalOffset=0;
			recipientVerticalOffset += verticalOffset+ 0.75;
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
			pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);   

			}else if(message_len > 1500 && message_len <=1800){
			///.
			// pdf.line(0.8, height/2, 5.0, height/2); 
			var recipientVerticalOffset = 0;
			var msgFontSize=13,recpFontSize = 16;
			var lines="",verticalOffset=0;

			if(card_message.length <=1600 && recipient.length<=50){
			recipientVerticalOffset = 1.35;
			}else if(card_message.length >1600 && card_message.length <=1700 && recipient.length<=50){
			recipientVerticalOffset = 1.5;
			}else{
			recipientVerticalOffset = 1.0;
			}

			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
			pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
			recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;

			verticalOffset = recipientVerticalOffset+0.2; 
			lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
			pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
			verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
			recipientVerticalOffset=0;

			if(sendor.length<=50){
			recipientVerticalOffset += verticalOffset +0.85;
			}else{
			recipientVerticalOffset += verticalOffset + 0.75;
			}
			var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
			pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine); 
			}else if(message_len > 1800){
			alert("Sorry ! Unable to print due to too much text content...! Thank You.");
			return false;
			}



			pdf.autoPrint("card_mesage");
			pdf.output("dataurlnewwindow");
		});
	</script>
<?php
}

	function checkforVoiceMessage($theOrder) 
	{
		mysql_select_db("floshowers");
		$id = $theOrder->getId();
		$query = "SELECT count(floshowers.sales_order_custom.id) as num_count  from floshowers.sales_order_custom WHERE floshowers.sales_order_custom.order_id = '" . $id . "' LIMIT 1";
		$result = mysql_query($query);
		//echo mysql_error();
		$row = mysql_fetch_array($result);
		mysql_select_db("operations");
		if($row['num_count'])
		{
			return TRUE;
		}
		return FALSE;
	}
?>
</html>