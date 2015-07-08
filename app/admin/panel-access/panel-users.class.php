<?php
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

class PanelUsers {
	private $entity_id;
	private $name;
	private $email;
	private $role_name;
   private $users;

  	public function __construct() {
  		$this->entity_id = 0;
  		$this->name = '';
  		$this->email = '';
      	$this->role_name = '';
  		$this->users = array();
   }

	public function getPanelUsers($shop_id){
		$query = "SELECT u.entity_id,u.firstname,u.lastname,u.email, s.shop_name , r.role_id, r.role_name from pos_user_entity as u, pos_shop_entity as s, pos_user_roles r where s.entity_id = u.shop_id and r.role_id = u.role_id";
		$result = mysql_query($query);
		if(mysql_num_rows($result)){
			while ($row = mysql_fetch_assoc($result)) {
				$this->users[] = $row;
			}
		}
		return $this->users;
	}

	public function saveUserResource($resource,$role_id,$users){
		$coulmns_name =  array('user_id','shop_id','resource_id','access_tag');
		$res_count =  count($resource);
		$sucess_count = 0;
		
		/*  initialize */
		$columns = '';
	    $values = '';
	    /*  initialize */

		foreach ($resource as $key => $data) {
			//$columns = '';
	      	//$values = '';
	      	foreach ($data as $key => $value) {
		        if (in_array($key, $coulmns_name)) {
		            //$columns = $columns . $key . ',';
		            //$role
		            $values = $values . "'" . mysql_real_escape_string($value) . "',";
		        } 
		    }
		    //echo $role_id;
		    //echo $values."\n";
		    //$query = "INSERT INTO pos_user_allocated_resource (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
	   		//$result =  mysql_query($query);
	   		if($result){
	   			$sucess_count = $sucess_count + 1;	
	   		}

	   		
		}
		//echo $role_id;
		//echo $values."\n";
		$query = "UPDATE `pos_role_allocated_resource` SET `permission`= 'deny' where role_id = '$role_id'";
		//echo $query;
		$result =  mysql_query($query);

		if(!$values && $result){
			return TRUE;
		}
		else if(!$result){
			return FALSE;
		}

		$update_user= '';

		foreach ($users as $key => $user) {
			 $update_user = $update_user . "'" . mysql_real_escape_string($user) . "',";
		}


		$query = "UPDATE `pos_role_allocated_resource` SET `permission`= 'allow' where role_id = '$role_id' and resource_id in (".trim($values, ',').")";
		//echo $query;
		$result =  mysql_query($query);

		$query = "UPDATE `pos_user_entity` SET `role_id` = '$role_id' where entity_id in (".trim($update_user, ',').")";
		$result =  mysql_query($query);

		//exit; 
		if($result){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}