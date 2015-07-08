<?php
	include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
	include $base_path_folder."/app/etc/dbconfig.php";
	include $base_path_folder."/app/etc/mageinclusion.php";
	mysql_select_db($custom_database);
	$orderid = $_REQUEST['orderid'];
	$orderid_array = explode("_", $orderid);
?>

<html>
<head>
	<script type="text/javascript" src="jquery-latest.min.js"></script>

	<script type="text/javascript" src="printapi/challan_giftmsg_resource/js/jquery-1.10.2.js"></script>
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
	<script src='printapi/vendor-payment-challan.js'></script>

	<script>
		$(document).ready(function(){
			<?php
			for($x=0; $x<sizeof($orderid_array); $x++)
			{
				?>
				printPaymentChallanOnPDF(<?php echo "'".$orderid_array[$x]."'"; ?>);
				<?php
			}
			?>
		});
	</script>
</head>
<body>


</body>
</html>