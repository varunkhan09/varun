<?php
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

class Item {
	private $item_name;
	private $entity_id;
	private $item_parent;
	private $item_sub_category_parent;
	private $items;
	private $price;
	private $id;


  	public function __construct() {
  		$this->item_name = "";
  		$this->entity_id = 0;
  		$this->price = null;
  		$this->item_parent = 0;
  		$this->id = 0;
  		$this->item_sub_category_parent = 0;
  		$this->items = array();
   	}


   	public function getItemsList(){
   		$query =  "SELECT * FROM pos_item_entity WHERE is_active = 1 ORDER BY entity_id ASC";
		$result = mysql_query($query);
		if(mysql_num_rows($result)){
			while ($row = mysql_fetch_assoc($result)) {
				$item = new stdClass();
				$item->item_name = $row['item_name'];
				$item->price = null;
				$item->id = 0;
				$item->entity_id = $row['entity_id'];
				$item->item_parent = $row['item_parent'];
				$item->item_sub_category_parent = $row['item_sub_category_parent'];
				$this->items[] = $item;
			}
		}
		return $this->items;
   	}

   	public function getItemsListByVendorId($vendor_id){

	if(isset($_SESSION['loggedin']['user']['shop_id'])){
                $shop_id =  (int) $_SESSION['loggedin']['user']['shop_id'];
        }


   		$query = "SELECT i.entity_id, i.item_name ,i.item_parent,i.item_sub_category_parent, v.id ,v.price FROM pos_item_entity as i LEFT JOIN pos_vendor_item_price as v ON i.entity_id = v.product_id AND i.is_active = 1 AND v.vendor_shop_id = $vendor_id and v.self_shop_id = $shop_id ORDER BY i.entity_id ASC";

   		$result = mysql_query($query);
		if(mysql_num_rows($result)){
			while ($row = mysql_fetch_assoc($result)) {
				$item = new stdClass();
				$item->item_name = $row['item_name'];
				$item->id = $row['id'];
				$item->price = $row['price'];
				$item->entity_id = $row['entity_id'];
				$item->item_parent = $row['item_parent'];
				$item->item_sub_category_parent = $row['item_sub_category_parent'];
				$this->items[] = $item;
			}
		}
		return $this->items;
   	}

}
