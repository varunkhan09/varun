<?php
class VendorItems {
	private $vendor_id;
	private $shop_id;
	private $vendorItems;

  	public function __construct() {
  		$this->vendor_id = 0;
  		$this->shop_id = 0;
  		$this->vendorItems = array();
   	}

   	public function saveVendorsItems($vendor_id,$shop_id,$items_data){
	//echo $vendor_id."=".$shop_id;
    	$iterator = new ArrayIterator( $items_data );
    	$cache = new CachingIterator( $iterator );
	    foreach ( $cache as $value ){
	    	$key = $cache->key();
	    	$key_part =  explode('_', $key);

	    	// key = entity_id+ id ; here id value mapped with pos_vendor_item_price table
	    	$entity_id = $key_part[0];
	    	$id =  (int) $key_part[1];
	    	$price = $cache->current();
	    	if(!$id){
	    		// when $id = 0; it's mean new entry 
	    		$query = "INSERT INTO pos_vendor_item_price (product_id, vendor_shop_id,self_shop_id,price ) VALUES ";
	    		$query .= "(".$entity_id.",{$vendor_id},{$shop_id}," .$price.")";
	    		$result = mysql_query( $query);
	    		//if any error stop operation and exist with error message.
	    		if(!$result){
	    			break;
	    			return false;
	    		}

	    	}else{
	    		$query = " INSERT INTO pos_vendor_item_price (id,product_id, vendor_shop_id,self_shop_id,price ) VALUES ";
	    		$query .= "(".$id.",{$entity_id},{$vendor_id},{$shop_id}," .$price.")";
	    		$query .=  " ON DUPLICATE KEY UPDATE price = VALUES(price) ";
	    		$result = mysql_query( $query);
	    		if(!$result){
	    			break;
	    			return false;
	    		}
	    	}
	    }
	    return true;
   	}
}
