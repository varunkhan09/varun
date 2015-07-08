<?php
//include "/var/www/html/magento/pratmagento/panel/fast/vendorpanel/fromserver_29april/global_variables.php";
//include "/var/www/varun/global_variables.php";
include $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
include $base_path_folder."/app/etc/dbconfig.php";
include $base_path_folder."/app/etc/mageinclusion.php";
mysql_select_db($magento_database);

$cat = $_REQUEST['category'];
$limit = $_REQUEST['limit'];
$start = $_REQUEST['start'];

$all_products_array = array();
$all_product_names_array = array();
$all_products_sku_array = array();
$all_products_name_single_variable = '';
$all_products_sku_single_variable = '';


if(!isset($_REQUEST['flag']))
{
	if($cat != "Full")
	{
		$sku_template = $cat."%";
		$category = Mage::getResourceModel('catalog/product_collection')->addAttributeToFilter('SKU', array('like'=>array($sku_template)))->load();
		$products = $category;
	}
	else
	{
		$products = Mage::getModel('catalog/product')->getCollection();
	}
	$products->setPage($start, $limit);
}
else
{
	if($_REQUEST['flag'] == "1")
	{
		if($_REQUEST['search_name_t'] != "")
		{
			$sku_template = "%".$_REQUEST['search_name_t']."%";
			$category = Mage::getResourceModel('catalog/product_collection')->addAttributeToFilter('Name', array('like'=>array($sku_template)))->load();
		}
		else
		{
			if($_REQUEST['search_sku_t'] != "")
			{
				$sku_template = "%".$_REQUEST['search_sku_t']."%";
				$category = Mage::getResourceModel('catalog/product_collection')->addAttributeToFilter('SKU', array('like'=>array($sku_template)))->load();
			}
		}
		$products = $category;
	}
}
/*
$product_all = $products;

foreach($product_all as $prod)
{
	$product = Mage::getModel('catalog/product')->load($prod->getId());
	$all_products_name_single_variable .= $product->getName()."_";
	$all_products_sku_single_variable .= $product->getSku()."_";
}

$all_products_name_single_variable = rtrim($all_products_name_single_variable,"_");
$all_products_sku_single_variable = rtrim($all_products_sku_single_variable,"_");
echo "<input type='hidden' id='all_names' value='$all_products_name_single_variable'>";
echo "<input type='hidden' id='all_skus' value='$all_products_sku_single_variable'>";
*/

//echo "Setting Up Page limit as : $start, $limit<br>";

foreach($products as $prod)
{
	$product = Mage::getModel('catalog/product')->load($prod->getId());
	$all_products_array[$product->getId()]['name'] = $product->getName();
	$all_products_array[$product->getId()]['sku'] = $product->getSku();
	$all_products_array[$product->getId()]['image'] = $product->getImageUrl();

	$all_product_names_array[$product->getName()] = $product->getId();
	$all_products_sku_array[$product->getSku()] = $product->getId();
}







/* DISPLAY CODE STARTS HERE */
if(!isset($_REQUEST['flag']))
{
	echo "<table style='border: 1px solid black; width:660px;' cellpadding='0' cellspacing='0'>";
	$product_id_array = array_keys($all_products_array);
	foreach($product_id_array as $product_id_array_inside_foreach)
	{
		echo "<tr>";
		echo "<td style='border:1px solid black;'><a href='' id='".$product_id_array_inside_foreach."' class='varun' onclick='return CloseMySelf(this);'>".$all_products_array[$product_id_array_inside_foreach]['name']."</td>";
		echo "<td style='border:1px solid black;'>".$all_products_array[$product_id_array_inside_foreach]['sku']."</td>";
		echo "<td style='border:1px solid black;'><img src='".$all_products_array[$product_id_array_inside_foreach]['image']."' style='height:100px; width:100px;'></a></td>";
		echo "</tr>";
	}
	echo "</table>";
}
else
{
	if($_REQUEST['flag'] == '1')
	{
		echo "<table style='border: 1px solid black; width:660px;' cellpadding='0' cellspacing='0'>";
		$product_id_array = array_keys($all_products_array);
		foreach($product_id_array as $product_id_array_inside_foreach)
		{
			echo "<tr>";
			echo "<td style='border:1px solid black;'><a href='' id='".$product_id_array_inside_foreach."' class='varun' onclick='return CloseMySelf(this);'>".$all_products_array[$product_id_array_inside_foreach]['name']."</td>";
			echo "<td style='border:1px solid black;'>".$all_products_array[$product_id_array_inside_foreach]['sku']."</td>";
			echo "<td style='border:1px solid black;'><img src='".$all_products_array[$product_id_array_inside_foreach]['image']."' style='height:100px; width:100px;'></a></td>";
			echo "</tr>";
		}
		echo "</table>";



		/*
		$entered_name = $_REQUEST['search_name_t'];
		$entered_sku = $_REQUEST['search_sku_t'];

		if($entered_name != "")
		{
			echo $all_product_names_array[$entered_name];
			echo "<table style='border: 1px solid black; width:660px;'>";
			echo "<tr>";
			echo "<td style='border:1px solid black;'><a href='' id='".$all_product_names_array[$entered_name]."' class='varun' onclick='return CloseMySelf(this);'>".$entered_name."</td>";
			echo "<td style='border:1px solid black;'>".$all_products_array[$all_product_names_array[$entered_name]]['sku']."</td>";
			echo "<td style='border:1px solid black;'><img src='".$all_products_array[$all_product_names_array[$entered_name]]['image']."' style='height:100px; width:100px;'></a></td>";
			echo "</tr>";
			echo "</table>";
		}
		else
		{
			if($entered_sku != "")
			{
				echo $all_products_sku_array[$entered_sku];
				echo "<table style='border: 1px solid black; width:660px;'>";
				echo "<tr>";
				echo "<td style='border:1px solid black;'><a href='' id='".$all_products_sku_array[$entered_sku]."' class='varun' onclick='return CloseMySelf(this);'>".$all_products_array[$all_products_sku_array[$entered_sku]]['name']."</td>";
				echo "<td style='border:1px solid black;'>".$entered_sku."</td>";
				echo "<td style='border:1px solid black;'><img src='".$all_products_array[$all_products_sku_array[$entered_sku]]['image']."' style='height:100px; width:100px;'></a></td>";
				echo "</tr>";
				echo "</table>";
			}
			else
			{
				echo "Please select at least one out of Product Name and Product SKU.";
			}
		}
		*/
	}
}
/* DISPLAY CODE ENDS HERE */






/* PAGINATION CODE STARTS HERE */
echo "<div class='pagination_div'>";
echo "<input type='button' class='pagination_buttons pagination_event1' value='<'>";
echo "<input type='number' id='current_page_number' class='pagination_textbox pagination_event2' value='$start' min='1' max='$max_page_number' required='required'>";
echo "<input type='button' class='pagination_buttons pagination_event1' value='>'>";
echo "</div>";
/* PAGINATION CODE ENDS HERE */
?>