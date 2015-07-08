<?php
	include 'vp_dbconfig.php';
	$orderid = $_REQUEST['orderid'];
	$comment = $_REQUEST['comment'];

	$order = Mage::getModel("sales/order")->load($orderid, 'increment_id');
	$order->addStatusToHistory($order->getStatus(), $comment, false);
	
	if($order->save())
	{
		$order_comments = $order->getAllStatusHistory();
		$string = '';
		foreach($order_comments as $each_comment)
		{
			$temp_comment = $each_comment->getComment();
			$temp_date =  $each_comment->getCreatedAt();
			$string .= $temp_comment."|".$temp_date."<>";
		}
		$string = rtrim($string, "<>");
		echo $string;
	}
	else
	{
		echo "-1";
	}
?>