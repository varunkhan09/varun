<?php
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

class Vendor {
	private $firstname;
	private $lastname;
	private $entity_id;
	private $shop_id;
	private $shop_name;

	private $vendors;

  	public function __construct() {
  		$this->firstname = "";
  		$this->lastname = "";
  		$this->entity_id = 0;
  		$this->shop_id = 0;
  		$this->shop_name = '';
  		$this->vendors = array();
   	}

   	/*
   	public function getVendorsList(){
   		$query = "SELECT u.firstname, u.lastname, u.entity_id,u.shop_id FROM pos_user_entity as u , pos_shop_entity as s,pos_user_validate as v WHERE u.entity_id  = s.user_id AND  u.entity_id = v.user_id AND v.is_validated = 1 AND u.is_adminpanel = 1 AND u.is_active = 1";
		$result = mysql_query($query);
		if(mysql_num_rows($result)){
			while ($row = mysql_fetch_assoc($result)) {
				$vendor = new stdClass();
				$vendor->firstname = $row['firstname'];
				$vendor->lastname = $row['lastname'];
				$vendor->entity_id = $row['entity_id'];
				$vendor->shop_id = $row['shop_id'];
				$this->vendors[] = $vendor;
			
			}
		}
		return $this->vendors;
   	}

*/


   		public function getShopVendorsList($shop_id){
   			if(!is_null($shop_id)){
   				$vshops = array($shop_id);
	   			$query = "SELECT vendor_shop_id FROM pos_associated_vendors_entity WHERE vendor_shop_id IS NOT NULL AND self_shop_id = $shop_id ";
	   			$result = mysql_query($query);
	   			if(mysql_num_rows($result)){
	   				while ($row = mysql_fetch_assoc($result)) {
	   					$vshops[] =  $row['vendor_shop_id'];
	   				}
	   			}

	   			$uvshops = array_unique($vshops);
	   			foreach ($uvshops as $shop_id) {
	   				$query = "SELECT u.firstname, u.lastname, u.entity_id,s.shop_name,s.entity_id as shop_id FROM pos_user_entity as u , pos_shop_entity as s WHERE u.entity_id  = s.user_id AND  s.entity_id = $shop_id AND u.is_active = 1";
					$re = mysql_query($query);
					if(mysql_num_rows($re)){
						while ($r = mysql_fetch_assoc($re)) {
							$vendor = new stdClass();		
							$vendor->firstname = $r['firstname'];
							$vendor->lastname = $r['lastname'];
							$vendor->entity_id = $r['entity_id'];
							$vendor->shop_id = $r['shop_id'];
							$vendor->shop_name = $r['shop_name'];
							$this->vendors[] = $vendor;		
						}
					}
	   			}
	   			//print_r( $this->vendors);
	   			//exit();
	   			return $this->vendors;
   			}
   		}
}