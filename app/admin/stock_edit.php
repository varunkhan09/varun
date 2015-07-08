<?php
	include $_SERVER['DOCUMENT_ROOT']."/app/module/common/header.php";
?>

	<style>
	.table
	{
		width:40%;
		text-align:center;
		margin-left: 30%;
	}

	.table_cell_1
	{
		width:70%;
	}

	.table_cell_2
	{
		width:30%;
	}

	.each_item_stock_textbox
	{
		width:60px;
	}

	.inner_table
	{
		margin-bottom:0px;
		width:80%;
		margin-left:10%;
	}

	.collapsable_row:hover
	{
		cursor:pointer;
	}
	</style>



	<div class="row stock_report_main_content_div text-center">
		<div class="container-fluid text-center">
			<input type="textbox" id="input_date_t" class="datepicker" placeholder="Select Date" value="">
			<br><br>
			<input type='button' id='load_stock_details_b' class='buttons' value='Load Stock Details'>
		</div>
	</div>


	<div id="dialog-confirm-msg"></div>


	<div id='stock_details_div' style='margin-top:40px;'></div>
</body>
</html>