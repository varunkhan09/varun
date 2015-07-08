<html>
<head>
	<script type="text/javascript" src="jquery-latest.min.js"></script>
</head>

<body>

<?php
for($x=10000; $x<=15000; $x++)
{
	?>
	<script>
	$.ajax({
		type:"POST",
		url:"vp_addbymagento.php",
		data:
		{
			orderid:"12000<?php echo $x; ?>"
		},

		success:function(){}
	});
	</script>
	<?php
}
?>