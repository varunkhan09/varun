<html>
<head>
	<style>
	.main_buttons
	{
		margin-left: 2%; border-radius: 6px; background-color: #009ACD; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}
	.main_buttons:hover
	{
		background-color: #00688B; cursor:pointer;
	}

	.main_buttons_red
	{
		margin-left: 2%; border-radius: 6px; background-color: #B22222; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}

	.main_buttons_red:hover
	{
		background-color: #8B1A1A; cursor:pointer;
	}

	.main_buttons_yellow
	{
		margin-left: 2%; border-radius: 6px; background-color: #EEB422; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}

	.main_buttons_yellow:hover
	{
		background-color: #CD9B1D; cursor:pointer;
	}

	.main_buttons_green
	{
		margin-left: 2%; border-radius: 6px; background-color: #6E8B3D; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}

	.main_buttons_green:hover
	{
		background-color: #556B2F; cursor:pointer;
	}

	.main_buttons_red_fire
	{
		margin-left: 2%; border-radius: 6px; background-color: #FF3030; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}

	.main_buttons_red_fire:hover
	{
		background-color: #CD2626; cursor:pointer;
	}

	.main_buttons_clicked
	{
		background-color:#CD8500;
	}

	.main_divs
	{
		float:left; display:inline; width:98%; margin:0% 0% 0% 1%; border-bottom:3px solid #00B2EE;
	}

	.main_divs_last
	{
		float:left; display:inline; width:98%; margin:0% 0% 0% 1%;
	}

	.first_div
	{
		float:left; display:inline; width:96%; margin:0% 0% 1% 2%; background-color:#F7F7F7; border:2px solid #00B2EE; border-radius: 4px;
	}

	.heading_labels
	{
		 font-size:24px; color:#00688B; font-family: Open Sans;
	}

	.data_labels_normal
	{
		font-family: 'Raleway', sans-serif;
	}

	.data_labels_bold
	{
		font-family: 'Raleway', sans-serif; font-weight:bold;
	}

	.main_buttons_without_form
	{
		border-radius: 6px; background-color: #009ACD; color: white; font-weight:bold; height:30px; border:0; font-family: 'Raleway';
	}

	.main_buttons_without_form:hover
	{
		background-color: #00688B; cursor:pointer;
	}

	.heading
	{
		 font-size:32px; color:#009ACD; font-weight:bold;
	}

	.main_div1_0
	{
		float:left; display:inline; width:30%; margin: 10px 0px 4px 0px;
	}

	.main_div1_1
	{
		 float:right; display:inline; width:70%; text-align:right; margin: 10px 0px 4px 0px;
	}

	.main_div2_1
	{
		 float:left; display:inline; width:100%; margin:15px 0px 0px 2px;
	}

	.main_div2_2_0
	{
		 float:left; display:inline; width:33%; text-align:left; margin:50px 0px 0px 0px;
	}

	.main_div2_2_1
	{
		 float:left; display:inline; width:50%; text-align:left; margin:50px 0px 0px 0px;
	}

	.main_div2_2_2
	{
		 float:left; display:inline; width:50%; text-align:left; margin:50px 0px 0px 0px;
	}

	.main_div3_1_0
	{
		float:left; display:inline; width:33%; text-align:left; margin:15px 0px 0px 0px;
	}

	.main_div3_1_1
	{
		float:left; display:inline; width:50%; text-align:left; margin:15px 0px 0px 0px;
	}

	.main_div3_1_2
	{
		float:left; display:inline; width:50%; text-align:left; margin:15px 0px 0px 0px;
	}

	.main_div2_3
	{
		float:left; display:inline; width:20%; text-align:left;
	}

	.main_div3_2
	{
		style='float:left; display:inline; width:100%; text-align:left;'
	}

	.main_div3_3
	{
		 float:left; display:inline; width:100%; text-align:left; margin:50px 0px 0px 0px;
	}

	.main_div5_1
	{
		 float:left; display:inline; width:100%; margin:5px 0px 5px 0px; border-bottom:1px solid #00B2EE; /*http://www.lncrnadb.org/static/images/largeWaiting.png*/
	}
	</style>
</head>
<body>
<br>
<center>
	<label class='heading'>Please Login to your Account</label>
	<br><br><br>
<?php
if(!isset($_REQUEST['flag']))
{
	echo "<div style='width:40%; text-align:center; float:left; margin:0% 0% 0% 30%; display:inline;'>";
	echo "<form style='margin:0px; padding:0px;' method='POST'>";
	echo "<center>";
	echo "<table style='text-align:center;'>";
	echo "<tr>";
	echo "<td><label class='heading_labels'>Login ID</label></td>";
	echo "<td><input type='textbox' name='loginvalue' required='required'></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><label class='heading_labels'>Password</label></td>";
	echo "<td><input type='password' name='passwordvalue' required='required'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td></td>";
	echo "<td><input type='submit' value='Login' class='main_buttons'></td>";
	echo "</tr>";
	echo "</table>";
	echo "</center>";
	echo "<input type='hidden' value='1' name='flag'>";
	echo "</form>";
	echo "</div>";
}
else
{
	if(isset($_REQUEST['flag']) && $_REQUEST['flag'] == "1")
	{
		//echo "I am Here...";
		include 'vp_dbconfig.php';

		$filled_pass = md5($_REQUEST['passwordvalue']);

		$query = "select passw from vendors where vendor_email='".$_REQUEST['loginvalue']."'";
		//echo $query;
		$result = mysql_query($query);
		echo mysql_error();
		$row = mysql_fetch_assoc($result);

		if(mysql_num_rows($result) != 0)
		{
			$retrieved_pass = $row['passw'];

			//echo $retrieved_pass;
			echo $filled_pass."<br>".$retrieved_pass;
			if($retrieved_pass == $filled_pass)
			{
				//echo "Yes";
				$query = "select vendor_id from vendors where vendor_email = '".$_REQUEST['loginvalue']."'";
				$result2 = mysql_query($query);
				$row2 = mysql_fetch_assoc($result2);

				session_start();
				$_SESSION['vendor_id'] = $row2['vendor_id'];
				$_SESSION['vendor_loggedin'] = "1";
				header("Location: index.php");
			}
			else
			{
				header("Location: vp_login.php?error=yes");
			}
		}
		else
		{
			header("Location: vp_login.php?error=yes");
		}
	}
	else
	{
		if($_REQUEST['error'] != "")
		{
			echo "<div style='width:40%; text-align:center; float:left; margin:0% 0% 0% 30%; display:inline;'>";
			echo "<form style='margin:0px; padding:0px;' method='POST'>";
			echo "<center>";
			echo "<table style='text-align:center;'>";
			echo "<tr>";
			echo "<td><label class='heading_labels'>Login ID</label></td>";
			echo "<td><input type='textbox' name='loginvalue' required='required'></td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td><label class='heading_labels'>Password</label></td>";
			echo "<td><input type='password' name='passwordvalue' required='required'></td>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<td></td>";
			echo "<td><input type='submit' value='Login' class='main_buttons'></td>";
			echo "</tr>";
			echo "</table>";
			echo "</center>";
			echo "<input type='hidden' value='1' name='flag'>";
			echo "</form>";
			echo "</div>";

			echo "<br><br><br>";

			echo "Invalid Information. Please Try Again.";
		}
	}
}
?>
</center>
</body>
</html>