<?php
include_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";

class ModuleResource {
	private $module_id;
	private $module_name;
	private $module_backend_code;
	private $modules;

  	public function __construct() {
  		$this->module_id = 0;
  		$this->module_name = '';
  		$this->module_backend_code = '';
  		$this->modules = array();
   	}

   	function getResourceById($module_id){
   		$query = "SELECT * from pos_modules WHERE module_id = $module_id";
   		$result = mysql_query($query);
   		if(mysql_num_rows($result)){
   			while ($row = mysql_fetch_object($result)) {
   				$this->modules[] = $row;
   			}
   		}
   		return $this->modules;
   	}

   	public function getModuleList(){
   		$query = "SELECT * from pos_modules WHERE module_parent='self'";
   		$result = mysql_query($query);
   		if(mysql_num_rows($result)){
   			while ($row = mysql_fetch_object($result)) {
   				$this->modules[] = $row;
   			}
   		}
   		return $this->modules;
   	}

   	public function getAllModules(){
   		$query = "SELECT * from pos_modules";
   		$result = mysql_query($query);
   		if(mysql_num_rows($result)){
   			while ($row = mysql_fetch_assoc($result)) {
   				$this->modules[] = $row;
   			}
   		}
   		return $this->modules;
   	}



   	public function getSubChildModuleById($pid,$mpid){
   		$query = "SELECT * from pos_modules WHERE module_parent = $mpid AND module_main_parent = $mpid";
   		$result = mysql_query($query);
   		if(mysql_num_rows($result)){
   			while ($row = mysql_fetch_assoc($result)) {
   				$this->modules[] = $row;
   			}
   		}
   		$query = "SELECT * from pos_modules WHERE module_parent = $pid AND module_main_parent = $mpid OR module_id = $mpid ";
   		$result = mysql_query($query);
   		if(mysql_num_rows($result)){
   			while ($row = mysql_fetch_assoc($result)) {
   				$this->modules[] = $row;
   			}
   		}
   		return $this->modules;
   	}


   	public function getChildModuleById($module_id){
   		$query = "SELECT * from pos_modules WHERE module_main_parent = $module_id AND module_parent = module_main_parent";
   		$result = mysql_query($query);
   		if(mysql_num_rows($result)){
   			while ($row = mysql_fetch_assoc($result)) {
   				$this->modules[] = $row;
   			}
   		}
   		return $this->modules;
   	}

   	public function saveModuleResource($resource){
   		// check if pahle se hai to do not do any things
 
   		$module_backend_code =  $resource['module_backend_code'];
   		$chk_query =  "SELECT * from pos_modules WHERE module_backend_code = '{$module_backend_code}'";
   		$res =  mysql_query($chk_query);
   		if(mysql_num_rows($res)){
   			return -1;
   		}else{
   			$columns = '';
		    $values = '';
			foreach ($resource as $key => $value) {
				$columns = $columns . $key . ',';
				$values = $values . "'" . mysql_real_escape_string($value) . "',";
			}
			$query = "INSERT INTO pos_modules (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
		 $result = mysql_query($query);
		    if($result){
		    	return TRUE;
		    }else{
		    	return FALSE;
		    }
   		}
   	}

   	function updateModuleResource($resource, $edited_module_id){
   		$update_string = "UPDATE pos_modules SET ";
	    $count = count($resource);
	    foreach ($resource as $key => $value) {
            if($count > 1){
                $update_string = $update_string . $key." = '".mysql_real_escape_string($value)."', ";
            }elseif($count == 1){
                $update_string = $update_string . $key." = '".mysql_real_escape_string($value)."'";
            }
            $count--;
	    } 
	    $update_query = $update_string. " WHERE module_id  =  {$edited_module_id}";
	    $result = mysql_query($update_query);
	    if($result){
	    	return TRUE;
	    }else{
	    	return FALSE;
	    }
   	}

      function getRootAccessTagName(){
         $query = "SELECT module_id,module_name,module_backend_code FROM pos_modules WHERE module_parent = module_main_parent AND module_parent = 'self'";
         $result = mysql_query($query);
         $rootAccessTag = array();
         if(mysql_num_rows($result)){
            while($row = mysql_fetch_assoc($result)) {
               $rootAccessTag[] =  $row;
            }
         }
         return $rootAccessTag;
      }

      function getChildrenAccess( $root_ids){
         $data = array();
         if(is_array($root_ids) && !empty($root_ids)){
            foreach ($root_ids as $key => $id) {
               $query = "SELECT module_id as id, module_main_parent as root_parent, module_parent as parent  ,module_backend_code as access_tag, module_name as name FROM pos_modules WHERE module_parent != 'self'  AND module_main_parent = $id";
               $result = mysql_query($query);
               if(mysql_num_rows($result)>0){
                  while($row = mysql_fetch_assoc($result)) {
                     $data[] =  $row;
                  }
               }
            }
         }
         return $data;
      }

      function listAccessModulesList(){
         $root_access_tag =  $this->getRootAccessTagName();

         $resource = array();
         $root_ids =  array();
         foreach ($root_access_tag as $key => $access_tag) {
            $root_ids[] =  $access_tag['module_id'];
            $resource[] =  array(
                                 $access_tag['module_backend_code'] => array(
                                                                        'id'=> $access_tag['module_id'],
                                                                        'name'=>$access_tag['module_name'],
                                                                        'access_tag'=>$access_tag['module_backend_code']
                                                                        )
                                 );
         }
         $child_modules =  $this->getChildrenAccess($root_ids);
         return array($resource,$child_modules);
      }



      ///////////////////////////////////////////////////////////////////////

      function listUserRoleAccessModulesList($role_id){
         $root_access_tag =  $this->getUserRoleRootAccessTagName($role_id);
         $ids = ""; 
         $count = count($root_access_tag);
         foreach ($root_access_tag as $key => $access_tag) {
            if($key == $count-1){
               $ids .= $access_tag['module_id'];
            }else{
               $ids .= $access_tag['module_id'] .',';
            }  
         }
     
         $query =  "SELECT * FROM pos_role_allocated_resource WHERE resource_id IN (" . $ids . ") AND role_id = {$role_id} AND permission = 'allow'";
      
         $root_allowed = array();
         $result = mysql_query($query);
         if(mysql_num_rows($result)){
            while ($row = mysql_fetch_assoc($result)) {
               $root_allowed[] = $row['resource_id'];   
            }
         }

         $resource = array();
         $root_ids =  array();
         foreach ($root_access_tag as $key => $access_tag) {
            $resource_id = (int)$access_tag['module_id'];
            foreach ($root_allowed as $key => $root_allowed_id) {
              if($root_allowed_id == $resource_id){
                  $root_ids[] =  $access_tag['module_id'];
                  $resource[] =  array(
                           $access_tag['module_backend_code'] => array(
                                       'id'=> $access_tag['module_id'],
                                       'name'=>$access_tag['module_name'],
                                       'access_tag'=>$access_tag['module_backend_code'],
                                       'root_parent'=> $access_tag['module_main_parent'],
                                       'parent'=> $access_tag['module_parent'],
                                       'url'=> $access_tag['module_url'],
                                       )
                           );

              }
            }
         }
         $child_modules =  $this->getUserRoleChildrenAccess($root_ids,$role_id);
         return array($resource,$child_modules);
      }

       function getUserRoleRootAccessTagName($role_id){
         $query = "SELECT module_id,module_name,module_backend_code ,module_main_parent , module_parent,module_url  FROM pos_modules WHERE module_parent = module_main_parent AND module_parent = 'self'";
         $result = mysql_query($query);
         $rootAccessTag = array();
         if(mysql_num_rows($result)){
            while($row = mysql_fetch_assoc($result)) {
               $rootAccessTag[] =  $row;
            }
         }
         return $rootAccessTag;
      }

       function getUserRoleChildrenAccess( $root_ids,$role_id){
         $role_permissions = $this->get_all_allowcated_resources($role_id);
         $data = array();
         if(is_array($root_ids) && !empty($root_ids)){
            foreach ($root_ids as $key => $id) {
               $query = "SELECT module_id as id, module_url as url ,module_main_parent as root_parent, module_parent as parent  ,module_backend_code as access_tag, module_name as name FROM pos_modules WHERE module_parent != 'self'  AND module_main_parent = $id";
               $result = mysql_query($query);
               if(mysql_num_rows($result)>0){
                  while($row = mysql_fetch_assoc($result)) {
                     if (in_array($row['id'], $role_permissions)) {
                         $data[] =  $row;
                     }
                  
                  }
               }
            }
         }
         return $data;
      }
      ////////////////////////

      public function get_all_allowcated_resources($role_id){
         $sql =  "SELECT * from pos_role_allocated_resource WHERE role_id = $role_id AND permission LIKE '%allow%'";
         $result =  mysql_query($sql);
         $all_permission_on_role = array();
         if(mysql_num_rows($result)){
            while ($row = mysql_fetch_assoc($result)) {
                  $all_permission_on_role[]= $row['resource_id'];
            }
         }
         return $all_permission_on_role;
      }

}