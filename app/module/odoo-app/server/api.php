<?php
require_once $_SERVER['DOCUMENT_ROOT']."/global_variables.php";
require_once $_SERVER['DOCUMENT_ROOT']."/app/etc/dbconfig.php";
require_once 'rest-api/rest.inc.php';

class API extends REST {

    
    // const DB_SERVER = "localhost";
    // const DB_USER = "root";
    // const DB_PASSWORD = "password";
    // const DB = "vendorshop";

    public $data = "";
    private $db = NULL;
    private $mysqli = NULL;
    private $query_string = NULL;
    private $parse_url_data = array();

    private $db_server ="";
    private $db_user ="";
    private $db_password ="";
    private $db_database ="";



    public function __construct($vendorshop_database,$server,$user,$password) {
        parent::__construct(); 
        if(isset($vendorshop_database)){
            $this->db_database = $vendorshop_database;
        }
        if(isset($server)){
            $this->db_server = $server;
        }
        if(isset($user)){
            $this->db_user = $user;
        }
         if(isset($password)){
            $this->db_password = $password;

        }
        // Init parent contructor
        $this->dbConnect();     // Initiate Database connection
        $this->query_string = $_SERVER['QUERY_STRING'];
    }

    private function dbConnect() {
        // commented by amarkant Dated : 11-06-2015
        // $this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
        
         $this->mysqli = new mysqli($this->db_server, $this->db_user, $this->db_password, $this->db_database);
    }

    /*
     * Dynmically call the method based on the query string
     */

    public function processApi() {
        $this->parse_url_data = explode("&", $this->query_string);
        //$func = strtolower(trim(str_replace("/", "", $_REQUEST['x'])));
        $func = strtolower(trim($this->parse_url_data['0']));

        if ((int) method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            $this->response('', 404); // If the method not exist with in this class "Page not found".
        }
    }

    //deliveryTypes


    public function deliveryTypes() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select * from fleet_deliverytype";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }




    public function areas() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select * from fleet_areas";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }




    public function additional_log() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $additional_log = json_decode(file_get_contents("php://input"), true);
        $column_names = array('order_id',
            'vehicle_id',
            'vehicle',
            'delivery_rate',
            'delivery_type',
            'delivery_type_id',
            'driver',
            'driver_id',
            'location',
            'date',
            'location_id', 
            'notes',);


        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) {
             if($desired_key == "date"){
                $d = date('Y-m-d', strtotime($additional_log[$desired_key]));
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $d . "',";
            }else{
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $additional_log[$desired_key] . "',";
            } 
        }


        $query = "INSERT INTO fleet_additional_log (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
        if (!empty($additional_log)) {
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $success = array("status" => "Success", "msg" => "Created Successfully.");
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); //"No Content" status
        }
    }



    /**
     * To get models list 
     */
    public function vehiclesmodels() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

	//echo $_SESSION['loggedin']['user']['shop_id'];


        $query = "select * from fleet_vehicles_models";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }

            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function vmodel() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['id'];
        if ($id > 0) {
            $query = "SELECT * FROM fleet_vehicles_models where id = $id";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = $r->fetch_assoc();
                $this->response($this->json($result), 200); // send user details
            }
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function vehiclebyId() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['id'];
        if ($id > 0) {
            $query = "SELECT * FROM fleet_vehicles_models,fleet_vehicles where fleet_vehicles.model_id = fleet_vehicles_models.id AND fleet_vehicles.vid = $id";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = $r->fetch_assoc();

                // edited here.
                // convert date formate here before going to dispaly

                //var_dump($result);

               // exit();

                $result['acquisition_date'] =  date("m/d/Y",strtotime($result['acquisition_date']));


                $this->response($this->json($result), 200); // send user details
            }
        }
        $this->response('', 204); // If no records "No Content" status
    }


    // To save new vehicles in fleet_vehicle table
    public function savevehicle() {

        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $vehicle = json_decode(file_get_contents("php://input"), true);

        $column_names = array('model_id', 'licence_plate', 'tags', 'driver_id', 'driver_location', 'chassis_no', 'last_odometer', 'acquisition_date', 'car_value', 'seat_no', 'door_no', 'color', 'transmission', 'fueltype', 'ccarbon_dioxide', 'horsepower', 'horsepower_taxation', 'power');
        $keys = array_keys($vehicle);
        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) {
            if($desired_key == "acquisition_date"){
                $d = date('Y-m-d', strtotime($vehicle[$desired_key]));
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $d . "',";
            }else{
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $vehicle[$desired_key] . "',";
            } 
        }

        // To add shop id , of user in fleet_vehicle table

        if(isset($_SESSION['loggedin']['user']['shop_id'])){
            $columns = $columns . "shop_id" . ',';
            $values = $values . "'" . $_SESSION['loggedin']['user']['shop_id'] . "',";
        }

        // not empty
        if (!empty($vehicle['id'])) {



            // update only when driver id is same else create new entry
            $date = date('Y-m-d', strtotime($vehicle['acquisition_date']));
            $query = "UPDATE fleet_vehicles SET seat_no= '" . $vehicle['seat_no'] .
                    "' , tags = '" . $vehicle['tags'] .
                    "' , driver_id = '" . $vehicle['driver_id'] .
                    "' , driver_location = '" . $vehicle['driver_location'] .
                    "' , licence_plate = '" . $vehicle['licence_plate'] .
                    "' , chassis_no = '" . $vehicle['chassis_no'] .
                    "' , acquisition_date = '" . $date .
                    "' , car_value = '" . $vehicle['car_value'] .
                    "' , seat_no = '" . $vehicle['seat_no'] .
                    "' , door_no = '" . $vehicle['door_no'] .
                    "' , color = '" . $vehicle['color'] .
                    "' , transmission = '" . $vehicle['transmission'] .
                    "' , fueltype = '" . $vehicle['fueltype'] .
                    "' , ccarbon_dioxide = '" . $vehicle['ccarbon_dioxide'] .
                    "' , horsepower = '" . $vehicle['horsepower'] .
                    "' , horsepower_taxation = '" . $vehicle['horsepower_taxation'] .
                    "' , power = '" . $vehicle['power'] .
                    "' , last_odometer = '" . $vehicle['last_odometer'] .
                    "' WHERE  fleet_vehicles.vid ='" . $vehicle['id'] . "'";

             // print_r($vehicle)   ;
             
             // echo $query  ;

             // exit();  


            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $success = array('status' => 'Success');
            $this->response($this->json($success), 200);
        } else {
            // check flag column name
            $col_name = array('licence_plate');
            $check_flag = true;
            foreach ($col_name as $col) {
                if (!empty($vehicle[$col])) {
                    // check for duplicate, and set flag
                    $query = "SELECT * FROM fleet_vehicles WHERE {$col} LIKE '%{$vehicle[$col]}%'";
                    $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
                    if ($r->num_rows > 0) {
                        $check_flag = FALSE;
                        break;
                    }
                } else {
                    $check_flag = FALSE;
                    break;
                }
            }

            if ($check_flag === FALSE) {
                // return and display a error message with some code.
                $status = array(
                    'data' => $vehicle,
                    'duplicate' => $col_name,
                    'status' => "Duplicate or empty entry might be so rejected ."
                );
                $this->response($this->json($status), 409);
            } else {
                // if passed then save

                $query = "INSERT INTO fleet_vehicles (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
                if (!empty($vehicle)) {
                    $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
                    $success = array(
                        'status' => "Success",
                        "msg" => "Created Successfully.",
                        "data" => $vehicle,
                        'query' => $query,
                        'col' => $columns,
                        'val' => $values,
                        'vehicle' => $vehicle,
                        'keys' => $keys
                    );
                    $this->response($this->json($success), 200);
                } else {
                    $this->response('', 204); //"No Content" status
                }
            }
        }
    }

    /***edit vehicle* */

    public function editvehicle() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $vehicle = json_decode(file_get_contents("php://input"), true);
        $column_names = array(
            'model_id', 'licence_plate', 'tags', 'driver_id', 'driver_location', 'chassis_no',
            'last_odometer', 'acquisition_date', 'car_value', 'seat_no', 'door_no', 'color', 'transmission',
            'fueltype', 'ccarbon_dioxide', 'horsepower', 'horsepower_taxation', 'power');

        $keys = array_keys($vehicle);
        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) {
             if($desired_key == "acquisition_date"){
                $d = date('Y-m-d', strtotime($vehicle[$desired_key]));
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $d . "',";
            }else{
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $vehicle[$desired_key] . "',";
            }  
        }
        $query = "Update fleet_vehicles  set (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
        if (!empty($vehicle)) {
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $success = array(
                'status' => "Success",
                "msg" => "Created Successfully.",
                "data" => $vehicle,
                'query' => $query,
                'col' => $columns,
                'val' => $values,
                'vehicle' => $vehicle,
                'keys' => $keys
            );
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); //"No Content" status
        }
    }

    /*     * *edit vehicle* */

    public function transmissions() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $query = "select * from fleet_transmission";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function transmissionById() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['id'];
        if ($id > 0) {
            $query = "SELECT * FROM fleet_transmission WHERE tid = $id";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = $r->fetch_assoc();
                $this->response($this->json($result), 200); // send user details
            }
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function fuels() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select * from fleet_fuels";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function fueltypeById() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['id'];
        if ($id > 0) {
            $query = "SELECT * FROM fleet_fuels WHERE fid = $id";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = $r->fetch_assoc();
                $this->response($this->json($result), 200); // send user details
            }
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function vehicles() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select * FROM fleet_vehicles , fleet_vehicles_models WHERE fleet_vehicles.model_id = fleet_vehicles_models.id and fleet_vehicles.shop_id=".$_SESSION['loggedin']['user']['shop_id'];
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function vehicles_status() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select * FROM fleet_vehicles_status";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    ///////-DRIVER-/////////////


    public function savedriver() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $driver = json_decode(file_get_contents("php://input"), true);

        if ($driver['did']) {
            $query = "UPDATE fleet_driver SET name= '" . $driver['name'] .
                    "' , zipcode = '" . $driver['zipcode'] .
                    "' , title = '" . $driver['title'] .
                    "' , company = '" . $driver['company'] .
                    "' , tags = '" . $driver['tags'] .
                    "' , street1 = '" . $driver['street1'] .
                    "' , street2 = '" . $driver['street2'] .
                    "' , city = '" . $driver['city'] .
                    "' , state = '" . $driver['state'] .
                    "' , job_profile = '" . $driver['job_profile'] .
                    "' , country = '" . $driver['country'] .
                    "' , website = '" . $driver['website'] .
                    "' , phoneno = '" . $driver['phoneno'] .
                    "' , mobileno = '" . $driver['mobileno'] .
                    "' , fax = '" . $driver['fax'] .
                    "' , email = '" . $driver['email'] .
                    "' WHERE  driver.did ='" . $driver['did'] . "'";

            if (!empty($driver)) {
                $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
                $success = array(
                    'status' => "Success",
                    "msg" => "Created Successfully.",
                    "data" => $driver
                );
                $this->response($this->json($success), 200);
            } else {
                $this->response('', 204); //"No Content" status
            }
        } else {
            $column_names = array(
                'title', 'name', 'company', 'tags', 'street1', 'street2', 'city',
                'state', 'zipcode', 'job_profile', 'country', 'website', 'phoneno', 'mobileno',
                'fax', 'email');

            $keys = array_keys($driver);
            $columns = '';
            $values = '';

            foreach ($column_names as $desired_key) {
                if (!in_array($desired_key, $keys)) {
                    $$desired_key = '';
                } else {
                    $$desired_key = $driver[$desired_key];
                }
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $$desired_key . "',";
            }

            $query = "INSERT INTO fleet_driver (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
            if (!empty($driver)) {
                $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
                $success = array(
                    "status" => "Success",
                    "msg" => "Created Successfully.",
                    "col" => $columns,
                    "value" => $values,
                    "key" => $keys
                );
                $this->response($this->json($success), 200);
            } else {
                $this->response('', 204); //"No Content" status
            }
        }
    }

    public function driver() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['id'];
        if ($id) {
           // $query = "select * from fleet_driver, where did = $id";
            $query = "SELECT fleet_driver.did,fleet_driver.name, fleet_driver.lastname,fleet_driver.city,
                             fleet_driver.state,fleet_driver.job_profile,fleet_driver.street1,fleet_driver.street2,
                             fleet_driver.zipcode,fleet_driver.country,fleet_driver.email,fleet_driver.phoneno,fleet_driver.mobileno,
                             pos_shop_entity.shop_name,pos_shop_entity.website_url
                        FROM fleet_driver, pos_shop_entity WHERE fleet_driver.did = $id AND pos_shop_entity.entity_id = fleet_driver.shop_id LIMIT 1";
            $result = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($result->num_rows > 0) {
                $result = $result->fetch_assoc();
                $this->response($this->json($result), 200); // send user details
            }
        }
        $this->response('', 204); // If no records "No Content" status    
    }

    public function drivers() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select * FROM fleet_driver where shop_id=".$_SESSION['loggedin']['user']['shop_id'];
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function vehicle_driver() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $id = (int) $this->_request['id'];
        if ($id) {
            $query = "select * FROM fleet_driver, fleet_vehicles where fleet_vehicles.driver_id = fleet_driver.did AND  fleet_vehicles.vid = $id";
            $result = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($result->num_rows > 0) {
                $result = $result->fetch_assoc();
                $this->response($this->json($result), 200); // send user details
            }
        }
        $this->response('', 204); // If no records "No Content" status  
    }

    public function delete_driver() {
        if ($this->get_request_method() != "DELETE") {
            $this->response('', 406);
        }

        $driver = json_decode(file_get_contents("php://input"), true);
        $value = array_values($driver);
        $key = array_keys($driver);

        $query = "DELETE FROM fleet_driver WHERE did = $value[0]";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r === TRUE) {
            $success = array('status' => "Success", "msg" => "Successfully one record deleted.");
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204);
        }
        // $this->response($this->json(array($value[0],$key[0])), 200);
    }

    ///////////////////
    ///// BRAND MODEL ENTRY /////

    public function savemodel() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $model = json_decode(file_get_contents("php://input"), true);

        if ($model['id']) {
            $query = "UPDATE fleet_vehicles_models SET model_brand= '" . $model['model_brand'] . "' , model_name = '" . $model['model_name'] . "' WHERE  vehicles_models.id ='" . $model['id'] . "'";
            if (!empty($model)) {
                $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
                $success = array('status' => "Success", "msg" => "Created Successfully.", "data" => $model);
                $this->response($this->json($success), 200);
            } else {
                $this->response('', 204); //"No Content" status
            }
        } else {
            $column_names = array('model_brand', 'model_name', 'image_url');
            $keys = array_keys($model);
            $columns = '';
            $values = '';

            foreach ($column_names as $desired_key) {
                if (!in_array($desired_key, $keys)) {
                    $$desired_key = '';
                } else {
                    $$desired_key = $model[$desired_key];
                }
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $$desired_key . "',";
            }

            $query = "INSERT INTO fleet_vehicles_models (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
            if (!empty($model)) {
                $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
                $success = array("status" => "Success", "msg" => "Created Successfully.");
                $this->response($this->json($success), 200);
            } else {
                $this->response('', 204); //"No Content" status
            }
        }
    }

    public function deletemodel() {
        if ($this->get_request_method() != "DELETE") {
            $this->response('', 406);
        }

        $model = json_decode(file_get_contents("php://input"), true);
        $value = array_values($model);
        $key = array_keys($model);

        $query = "DELETE FROM fleet_vehicles_models WHERE id = $value[0]";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r === TRUE) {
            $success = array('status' => "Success", "msg" => "Successfully one record deleted.");
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204);
        }
        // $this->response($this->json(array($value[0],$key[0])), 200);
    }

    ///// BRAND MODEL ENTRY END ////
    ////// ODOMETER ENTRY START ////
    public function odometerbyvid() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $vid = (int) $this->_request['id'];

        if ($vid) {
            $query = "SELECT o.id, o.vid, o.edited_ref, "
                    . "o.op_odometer,o.cls_odometer,o.comment, o.checkbox, o.date, o.odometervalue, o.unit, o.created_at, "
                    . "vm.model_brand ,vm.model_name, vm.image_url,vh.licence_plate "
                    . "from fleet_odometer as o,fleet_vehicles_models as vm,fleet_vehicles as vh "
                    . "WHERE vh.model_id= vm.id AND vh.vid={$vid} AND o.vid = {$vid} order by created_at";

            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = array();
                while ($row = $r->fetch_assoc()) {
                    $result[] = $row;
                }
                $this->response($this->json($result), 200);
            }
            $this->response('', 204); // If no records "No Content" status
        }
    }

    public function odometerEntry() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $oid = (int) $this->_request['id'];

        if ($oid) {
            $query = "SELECT * FROM fleet_odometer WHERE  id = {$oid}";

            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = array();
                while ($row = $r->fetch_assoc()) {
                    $result[] = $row;
                }
                $this->response($this->json($result), 200);
            }
            $this->response('', 204); // If no records "No Content" status
        }
    }

    public function getVehiclesToOdometer() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $query = "SELECT m.id,m.model_brand,m.model_name,m.image_url, v.vid , v.licence_plate from fleet_vehicles_models as m, fleet_vehicles as v where v.model_id = m.id ";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function updateOdometer() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $odometer = json_decode(file_get_contents("php://input"), true);
        if ($odometer['update_vid'] == $odometer['vid']) {
            // make new entry in database and put {editedRef} value to the new inserted record
            $column_names = array('vid', 'checkbox', 'date', 'op_odometer', 'cls_odometer', 'odometervalue', 'unit', 'comment', 'edited_ref');
            $columns = '';
            $values = '';
            foreach ($column_names as $desired_key) {

                if($desired_key == "date"){
                    $d = date('Y-m-d', strtotime($odometer[$desired_key]));
                    $columns = $columns . $desired_key . ',';
                    $values = $values . "'" . $d . "',";
                }else{
                    $columns = $columns . $desired_key . ',';
                    $values = $values . "'" . $odometer[$desired_key] . "',";
                }  

                // $columns = $columns . $desired_key . ',';
                // $values = $values . "'" . $odometer[$desired_key] . "',";
            }
            $query = "INSERT INTO fleet_odometer (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
            if (!empty($odometer)) {
                $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
                $success = array("status" => "Success", "msg" => "Created Successfully.");
                $this->response($this->json($success), 200);
            } else {
                $this->response('', 204); //"No Content" status
            }
        }
    }

    public function saveOdometer() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $odometer = json_decode(file_get_contents("php://input"), true);

        $column_names = array('vid', 'checkbox', 'date', 'op_odometer', 'cls_odometer', 'odometervalue', 'unit');

        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) {
            if($desired_key == "date"){
                $d = date('Y-m-d', strtotime($odometer[$desired_key]));
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $d . "',";
            }else{
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $odometer[$desired_key] . "',";
            }  
        }


        $query = "INSERT INTO fleet_odometer (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
        if (!empty($odometer)) {
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $success = array("status" => "Success", "msg" => "Created Successfully.");
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); //"No Content" status
        }
    }

    ////// ODOMETER ENTRY END //////
    ////// Vehicle cost entry ///////


    public function cost_type_by_id() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $id = (int) $this->_request['id'];
        if ($id > 0) {
            $query = "SELECT * FROM fleet_vehicle_cost_type where id = $id";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = $r->fetch_assoc();
                $this->response($this->json($result), 200); // send user details
            }
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function get_vehicle_cost_type() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select * FROM fleet_vehicle_cost_type";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function save_vehicle_cost() {

        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $vehicle_cost = json_decode(file_get_contents("php://input"), true);
        $column_names = array('vehicle_id', 'cost_type_id', 'cost', 'date');


        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) {
            if($desired_key == "date"){
                $d = date('Y-m-d', strtotime($vehicle_cost[$desired_key]));
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $d . "',";
            }else{
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $vehicle_cost[$desired_key] . "',";
            }
        }

         $query = "INSERT INTO fleet_vehicle_cost (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
        if (!empty($vehicle_cost)) {
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $success = array("status" => "Success", "msg" => "Created Successfully.");
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); //"No Content" status
        }
    }

    public function vehicle_cost_detail_by_id() {

        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $vehicle_id = (int) $this->_request['id'];

        if ($vehicle_id) {
            $query = "SELECT vm.id as mid, CONCAT(vm.model_brand,'/',vm.model_name,'/',v.licence_plate) as vehicle_model_name ,"
                    . "vc.vcid, vct.type,vc.cost,vc.date "
                    . "FROM fleet_vehicles_models as vm, "
                    . "fleet_vehicle_cost as vc , fleet_vehicle_cost_type as vct , fleet_vehicles as v "
                    . "WHERE vm.id= v.model_id AND vct.id = vc.cost_type_id AND v.vid=vc.vehicle_id AND vc.vehicle_id={$vehicle_id}";


            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = array();
                while ($row = $r->fetch_assoc()) {
                    $result[] = $row;
                }
                $this->response($this->json($result), 200);
            }
            $this->response('', 204); // If no records "No Content" status
        }
    }

    public function save_vehicle_cost_type() {

        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $vehicle_cost_type = json_decode(file_get_contents("php://input"), true);
        $column_names = array('type');

        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) {
            $columns = $columns . $desired_key . ',';
            $values = $values . "'" . $vehicle_cost_type[$desired_key] . "',";
        }


        $query = "INSERT INTO fleet_vehicle_cost_type (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
        if (!empty($vehicle_cost_type)) {
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $success = array("status" => "Success", "msg" => "Created Successfully.");
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); //"No Content" status
        }
    }

    ////// Vehicle fuel entry ///////


    public function vehicle_fuel_detail_by_id() {

        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $vehicle_id = (int) $this->_request['id'];

        if ($vehicle_id) {
            $query = "SELECT vm.id as mid, CONCAT(vm.model_brand,'/',vm.model_name,'/',v.licence_plate) as vehicle_model_name, "
                    . "d.name as purchaser , d.did as driver_id , vf.notes,vf.liter, vf.date, vf.rate FROM fleet_vehicles_models as vm,"
                    . "fleet_driver as d, fleet_vehicle_fuel as vf,fleet_vehicles as v "
                    . "WHERE d.did = vf.purchaser AND vm.id = v.model_id AND v.vid = vf.vehicle_id AND vf.vehicle_id = {$vehicle_id}";

            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                $result = array();
                while ($row = $r->fetch_assoc()) {
                    $result[] = $row;
                }
                $this->response($this->json($result), 200);
            }
            $this->response('', 204); // If no records "No Content" status
        }
    }

    public function vehicle_fuels() {

        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select * FROM fleet_vehicles , fleet_vehicles_models WHERE fleet_vehicles.model_id = fleet_vehicles_models.id and fleet_vehicles.shop_id=".$_SESSION['loggedin']['user']['shop_id'];
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status



        // if ($this->get_request_method() != "GET") {
        //     $this->response('', 406);
        // }
        
        // $query = "SELECT vm.id as model_id , vm.model_brand, vm.model_name, v.licence_plate,v.driver_id, v.vid ,v.shop_id FROM fleet_vehicles_models as vm, fleet_vehicles as v WHERE v.model_id = vm.id";
        
        // $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        // if ($r->num_rows > 0) {
        //     $result = array();
        //     while ($row = $r->fetch_assoc()) {
        //         $result[] = $row;
        //     }
        //     $temp = array();
        //     $temp =  $result;
        //     $shop = 0;

        //     if(isset($_SESSION['loggedin']['user']['shop_id'])){
        //         $shop =  (int)$_SESSION['loggedin']['user']['shop_id'];
        //     }

        //     if(!empty($result)){
        //         foreach ($result as $res) {
        //            $s =  (int) $res['shop_id'];
        //            if($s  && $s === $shop){
        //                 $temp[] =  $res; 
        //            }

        //         } 
        //     }

        //     $this->response($this->json($temp), 200);
        // }
        // $this->response('', 204); // If no records "No Content" status
    }

    public function save_vehicle_fuels() {

        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $vehicle_fuels = json_decode(file_get_contents("php://input"), true);
        $column_names = array('vehicle_id', 'liter', 'date', 'purchaser', 'rate', 'totalcost', 'odometer', 'invoice_reference', 'supplier', 'notes');

        $columns = '';
        $values = '';

        foreach ($column_names as $desired_key) {
            if($desired_key == "date"){
                $d = date('Y-m-d', strtotime($vehicle_fuels[$desired_key]));
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $d . "',";
            }else{
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $vehicle_fuels[$desired_key] . "',";
            }
        }

        $query = "INSERT INTO fleet_vehicle_fuel (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
        if (!empty($vehicle_fuels)) {
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $success = array("status" => "Success", "msg" => "Created Successfully.");
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); //"No Content" status
        }
    }

    // Vehicle Service.

    public function vehicle_service_byId() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $vehicle_id = (int) $this->_request['id'];

        $query = "SELECT * FROM fleet_vehicle_service where vehicle_id= {$vehicle_id} ";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = array();
            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function save_vehicle_service() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $vehicle_service = json_decode(file_get_contents("php://input"), true);
        $column_names = array('vehicle_id', 'cost_type_id', 'purchaser_id', 'purchaser', 'supplier', 'invoice_reference', 'notes', 'totalprice', 'date', 'odometer');

        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) {

            if($desired_key == "date"){
                $d = date('Y-m-d', strtotime($vehicle_service[$desired_key]));
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $d . "',";
            }else{
                $columns = $columns . $desired_key . ',';
                $values = $values . "'" . $vehicle_service[$desired_key] . "',";
            }
        }


        $query = "INSERT INTO fleet_vehicle_service (" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
        if (!empty($vehicle_service)) {
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $success = array("status" => "Success", "msg" => "Created Successfully.");
            $this->response($this->json($success), 200);
        } else {
            $this->response('', 204); //"No Content" status
        }
    }

    public function fetch_additional_logs() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select distinct vid from fleet_vehicles where shop_id=".$_SESSION['loggedin']['user']['shop_id']." order by created_at desc";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();
            $resultantData = array();

            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }

            foreach ($result as $key => $record) {

                $resultantData[$key]['vid'] = $record['vid'];
                // To get vehicle details
                $q = "SELECT vm.model_brand, vm.model_name, v.licence_plate FROM fleet_vehicles_models as vm, fleet_vehicles as v WHERE v.model_id = vm.id AND v.vid ='" . $record['vid'] . "' order by v.created_at desc LIMIT 1";
                $r = $this->mysqli->query($q) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $res = array();
                    while ($row = $r->fetch_assoc()) {
                        $res[] = $row;
                    }
                    $resultantData[$key]['titleData'] = $res;
                }

                // To get additional log 
                $sql = "SELECT * FROM fleet_additional_log WHERE vehicle_id = {$record['vid']}";
                $r = $this->mysqli->query($sql) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $addLogData = array();
                    while ($row = $r->fetch_assoc()) {
                        $addLogData[] = $row;
                    }
                    $resultantData[$key]['addLogData'] = $addLogData;
                }
            }
            $this->response($this->json($resultantData), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /*
     * Vehicle fuel log listing 
     */

    public function vehicleFuelLogToList() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select distinct vid from fleet_vehicles where shop_id=".$_SESSION['loggedin']['user']['shop_id']." order by created_at desc";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = array();
            $resultantData = array();

            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }

            foreach ($result as $key => $record) {
                $resultantData[$key]['vid'] = $record['vid'];

                // To get vehicle details
                $q = "SELECT vm.model_brand, vm.model_name, v.licence_plate FROM fleet_vehicles_models as vm, fleet_vehicles as v WHERE v.model_id = vm.id AND v.vid ='" . $record['vid'] . "' order by v.created_at desc LIMIT 1";
                $r = $this->mysqli->query($q) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $res = array();
                    while ($row = $r->fetch_assoc()) {
                        $res[] = $row;
                    }
                    $resultantData[$key]['titleData'] = $res;
                }

                // To get fuel log 
                $sql = "SELECT * FROM fleet_vehicle_fuel,fleet_driver WHERE fleet_driver.did = fleet_vehicle_fuel.purchaser AND vehicle_id = {$record['vid']}";
                $r = $this->mysqli->query($sql) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $fuelLogData = array();
                    while ($row = $r->fetch_assoc()) {
                        $fuelLogData[] = $row;
                    }
                    $resultantData[$key]['fuelLogData'] = $fuelLogData;
                }
            }
            $this->response($this->json($resultantData), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function vehicleServiceLogToList() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select distinct vid from fleet_vehicles where shop_id=".$_SESSION['loggedin']['user']['shop_id']." order by created_at desc";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = array();
            $resultantData = array();

            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }

            foreach ($result as $key => $record) {
                $resultantData[$key]['vid'] = $record['vid'];

                // To get vehicle details
                $q = "SELECT vm.model_brand, vm.model_name, v.licence_plate FROM fleet_vehicles_models as vm, fleet_vehicles as v WHERE v.model_id = vm.id AND v.vid ='" . $record['vid'] . "' order by v.created_at desc LIMIT 1";
                $r = $this->mysqli->query($q) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $res = array();
                    while ($row = $r->fetch_assoc()) {
                        $res[] = $row;
                    }
                    $resultantData[$key]['titleData'] = $res;
                }

                // To get fuel log 
                $sql = "SELECT * FROM fleet_vehicle_service,fleet_vehicle_cost_type,fleet_driver WHERE fleet_vehicle_cost_type.id = fleet_vehicle_service.cost_type_id AND fleet_driver.did = fleet_vehicle_service.purchaser_id AND vehicle_id = {$record['vid']}";
                $r = $this->mysqli->query($sql) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $serviceLogData = array();
                    while ($row = $r->fetch_assoc()) {
                        $serviceLogData[] = $row;
                    }
                    $resultantData[$key]['serviceLogData'] = $serviceLogData;
                }
            }
            $this->response($this->json($resultantData), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    /////////////cost///////////////.

    public function vehicleCostLogToList() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select distinct vid from fleet_vehicles where shop_id=".$_SESSION['loggedin']['user']['shop_id']." order by created_at desc";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = array();
            $resultantData = array();

            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }

            foreach ($result as $key => $record) {
                $resultantData[$key]['vid'] = $record['vid'];

                // To get vehicle details
                $q = "SELECT vm.model_brand, vm.model_name, v.licence_plate FROM fleet_vehicles_models as vm, fleet_vehicles as v WHERE v.model_id = vm.id AND v.vid ='" . $record['vid'] . "' order by v.created_at desc LIMIT 1";
                $r = $this->mysqli->query($q) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $res = array();
                    while ($row = $r->fetch_assoc()) {
                        $res[] = $row;
                    }
                    $resultantData[$key]['titleData'] = $res;
                }

                // To get fuel log 
                $sql = "SELECT * FROM fleet_vehicle_cost, fleet_vehicle_cost_type WHERE fleet_vehicle_cost.cost_type_id = fleet_vehicle_cost_type.id AND fleet_vehicle_cost.vehicle_id = {$record['vid']}";
                $r = $this->mysqli->query($sql) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $costLogData = array();
                    while ($row = $r->fetch_assoc()) {
                        $costLogData[] = $row;
                    }
                    $resultantData[$key]['costLogData'] = $costLogData;
                }
            }
            $this->response($this->json($resultantData), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    public function vehicleOdometerLogToList() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $query = "select distinct vid from fleet_vehicles where shop_id=".$_SESSION['loggedin']['user']['shop_id']." order by created_at desc";
        $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
        if ($r->num_rows > 0) {
            $result = array();
            $resultantData = array();

            while ($row = $r->fetch_assoc()) {
                $result[] = $row;
            }

            foreach ($result as $key => $record) {
                $resultantData[$key]['vid'] = $record['vid'];

                // To get vehicle details
                $q = "SELECT vm.model_brand, vm.model_name, v.licence_plate FROM fleet_vehicles_models as vm, fleet_vehicles as v WHERE v.model_id = vm.id AND v.vid ='" . $record['vid'] . "' order by v.created_at desc LIMIT 1";
                $r = $this->mysqli->query($q) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $res = array();
                    while ($row = $r->fetch_assoc()) {
                        $res[] = $row;
                    }
                    $resultantData[$key]['titleData'] = $res;
                }

                // To get fuel log 
                $sql = "SELECT * FROM fleet_odometer WHERE  fleet_odometer.vid = {$record['vid']}";
                $r = $this->mysqli->query($sql) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    $odometerLogData = array();
                    while ($row = $r->fetch_assoc()) {
                        $odometerLogData[] = $row;
                    }
                    $resultantData[$key]['odometerLogData'] = $odometerLogData;
                }
            }
            $this->response($this->json($resultantData), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    // Report 

    public function all_drivers_report_data() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $search = json_decode(file_get_contents("php://input"), true);
        $recordData = array();

        $from_date =  date("Y-m-d", strtotime($search['from_date']));
        $to_date =  date("Y-m-d", strtotime($search['to_date']));
        if (empty($search['did']) && empty($search['vid']) && !empty($search['from_date']) && !empty($search['to_date'])) {
            $query = "SELECT DISTINCT did FROM fleet_driver where shop_id=".$_SESSION['loggedin']['user']['shop_id'];
            
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $did_result = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $did_result[] = $row['did'];
                }
            }

            /*
             * $ids = join(',',$galleries);  
             * $sql = "SELECT * FROM galleries WHERE id IN ($ids)";
             */
            $dids = join(',', $did_result);
            $query = "SELECT o.date,o.op_odometer,o.cls_odometer,o.comment,o.edited_ref,d.name FROM fleet_odometer as o,fleet_driver as d,fleet_vehicles as v WHERE o.vid = v.vid AND d.did = v.driver_id AND v.driver_id IN ({$dids}) AND (o.date BETWEEN '{$from_date}' AND '{$to_date}')";

            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $odometer = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $odometer[] = $row;
                }
            }

            $query = "SELECT * FROM fleet_additional_log WHERE driver_id IN ({$dids}) AND (date BETWEEN '{$from_date}' AND '{$to_date}')";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $additional = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $additional[] = $row;
                }
            }

            $fuel = array();
            foreach ($did_result as $d) {
                $query = "SELECT * FROM fleet_vehicle_fuel,fleet_driver WHERE fleet_driver.did=  {$d} AND purchaser = {$d} AND (date BETWEEN '{$from_date}' AND '{$to_date}')";
                $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
                if ($r->num_rows > 0) {
                    while ($row = $r->fetch_assoc()) {
                        $fuel[] = $row;
                    }
                }
            }

            $query = "SELECT * FROM fleet_vehicle_service , fleet_vehicle_cost_type WHERE ( fleet_vehicle_cost_type.id = fleet_vehicle_service.cost_type_id ) AND purchaser_id IN ({$dids}) AND (date BETWEEN '{$from_date}' AND '{$to_date}')";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $service = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $service[] = $row;
                }
            }
        }


        $recordData['additional'] = !empty($additional) ? $additional : array();
        $recordData['odometer'] = !empty($odometer) ? $odometer : array();
        $recordData['fuel'] = !empty($fuel) ? $fuel : array();
        $recordData['service'] = !empty($service) ? $service : array();
        $this->response($this->json($recordData), 200);
    }



    // individual 
    public function report_data() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $search = json_decode(file_get_contents("php://input"), true);

        
        $from_date =  date("Y-m-d", strtotime($search['from_date']));
        $to_date =  date("Y-m-d", strtotime($search['to_date']));

        // if both then.
        if (is_numeric($search['vid']) && is_numeric($search['did'])) {
            $recordData = array();
            $odometer = array();
            $additional = array();
            $fuel = array();
            $service = array();


            $query = "SELECT o.date,o.op_odometer,o.cls_odometer,o.comment,o.edited_ref ,d.name FROM fleet_odometer as o,fleet_driver as d WHERE d.did={$search['did']} AND o.vid = {$search['vid']} AND (o.date BETWEEN '{$from_date}' AND '{$to_date}')";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $odometer[] = $row;
                }
            }


            $query = "SELECT * FROM fleet_additional_log WHERE driver_id={$search['did']} AND vehicle_id = {$search['vid']} AND (date BETWEEN '{$from_date}' AND '{$to_date}')";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $additional[] = $row;
                }
            }

            $query = "SELECT f.date,f.liter,f.rate,f.totalcost,f.purchaser,f.notes,d.name  "
                    . "FROM fleet_vehicle_fuel as f,fleet_driver as d "
                    . "WHERE d.did = f.purchaser AND f.purchaser = {$search['did']} AND vehicle_id = {$search['vid']} AND (date BETWEEN '{$from_date}' AND '{$to_date}')";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $fuel[] = $row;
                }
            }

            $query = "SELECT s.date,s.notes,s.totalprice,s.purchaser, c.type FROM fleet_vehicle_service as s, fleet_vehicle_cost_type as c "
                    . "WHERE s.cost_type_id = c.id AND s.purchaser_id= {$search['did']} AND  s.vehicle_id = {$search['vid']} "
                    . "AND (s.date BETWEEN '{$from_date}' AND '{$to_date}')";

            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $service[] = $row;
                }
            }

            $recordData['additional'] = !empty($additional) ? $additional : array();
            $recordData['odometer'] = !empty($odometer) ? $odometer : array();
            $recordData['fuel'] = !empty($fuel) ? $fuel : array();
            $recordData['service'] = !empty($service) ? $service : array();

            $this->response($this->json($recordData), 200);
        } elseif (is_numeric($search['did']) && empty($search['vid'])) {
            // Driver wise
            /**
             *  Case 2.
             *  When vehicle is empty field but only driver is present.
             * 
             */
            $recordData = array();
            $query = "SELECT o.date,o.op_odometer,o.cls_odometer,o.comment,o.edited_ref,d.name FROM fleet_odometer as o,fleet_driver as d,fleet_vehicles as v WHERE o.vid = v.vid AND d.did = v.driver_id AND v.driver_id = {$search['did']} AND (o.date BETWEEN '{$from_date}' AND '{$to_date}')";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $odometer = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $odometer[] = $row;
                }
            }


            $query = "SELECT * FROM fleet_additional_log WHERE driver_id = {$search['did']} AND (date BETWEEN '{$from_date}' AND '{$to_date}')";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $additional = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $additional[] = $row;
                }
            }


            $query = "SELECT * FROM fleet_vehicle_fuel,fleet_driver WHERE fleet_driver.did=  {$search['did']} AND purchaser = {$search['did']} AND (date BETWEEN '{$from_date}' AND '{$to_date}')";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $fuel = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $fuel[] = $row;
                }
            }

            $query = "SELECT * FROM fleet_vehicle_service , fleet_vehicle_cost_type WHERE ( fleet_vehicle_cost_type.id = fleet_vehicle_service.cost_type_id ) AND purchaser_id = {$search['did']} AND (date BETWEEN '{$from_date}' AND '{$to_date}')";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $service = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $service[] = $row;
                }
            }

            $recordData['additional'] = !empty($additional) ? $additional : array();
            $recordData['odometer'] = !empty($odometer) ? $odometer : array();
            $recordData['fuel'] = !empty($fuel) ? $fuel : array();
            $recordData['service'] = !empty($service) ? $service : array();

            $this->response($this->json($recordData), 200);

        } elseif (empty($search['did']) && is_numeric($search['vid'])) {

            $recordData = array();

            $query = "SELECT o.date,o.op_odometer,o.cls_odometer,o.comment,o.edited_ref ,d.name "
                    . "FROM fleet_odometer as o,fleet_driver as d, fleet_vehicles as v "
                    . "WHERE v.vid = o.vid AND v.driver_id = d.did AND o.vid = {$search['vid']} "
                    . "AND (o.date BETWEEN '{$from_date}' AND '{$to_date}')";


            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $odometer[] = $row;
                }
            }

            $query = "SELECT * FROM fleet_additional_log WHERE vehicle_id = {$search['vid']} AND (date BETWEEN '{$from_date}' AND '{$to_date}') ";
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $additional = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $additional[] = $row;
                }
            }

            $query = "SELECT f.date,f.liter,f.rate,f.totalcost,f.purchaser,f.notes,d.name  "
                    . "FROM fleet_vehicle_fuel as f,fleet_driver as d "
                    . "WHERE d.did = f.purchaser AND vehicle_id = {$search['vid']} AND (date BETWEEN '{$from_date}' AND '{$to_date}')";

            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $fuel = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $fuel[] = $row;
                }
            }

            $query = "SELECT s.date,s.notes,s.totalprice,s.purchaser, c.type "
                    . "FROM fleet_vehicle_service as s, fleet_vehicle_cost_type as c "
                    . "WHERE s.cost_type_id = c.id "
                    . "AND  s.vehicle_id = {$search['vid']} "
                    . "AND (s.date BETWEEN '{$from_date}' AND '{$to_date}')";

            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $service = array();
            if ($r->num_rows > 0) {
                while ($row = $r->fetch_assoc()) {
                    $service[] = $row;
                }
            }

            $recordData['additional'] = !empty($additional) ? $additional : array();
            $recordData['odometer'] = !empty($odometer) ? $odometer : array();
            $recordData['fuel'] = !empty($fuel) ? $fuel : array();
            $recordData['service'] = !empty($service) ? $service : array();
            $this->response($this->json($recordData), 200);
        }
    }

    /*
     * 	Encode array into JSON
     */
    public function json($data) {
        if (is_array($data)) {
            return json_encode($data);
        }
    }
}

// Initiiate Library
$api = new API($vendorshop_database,$server,$user,$password);
$api->processApi();

//print_r($api->deliveryTypes());







    // $date = convertLocalTimezoneToGMT(date('Y-m-d H:i:s'), ini_get('date.timezone'));
    // $columns = $columns . 'created_at' . ',';
    // $values = $values . "'" . $date . "',";
    // $gmttime = date('Y-m-d H:i:s');
    // $local_timezone = ini_get('date.timezone');
    // if(empty($local_timezone)){
    //     date_default_timezone_set("Asia/Kolkata");
    // }else{
    //     date_default_timezone_set($local_timezone);
    // }
    // $local = date("Y-m-d h:i:s A");
    // date_default_timezone_set("GMT");
    // $gmt = date("Y-m-d h:i:s A");
    // // System time zone
    // $system_timezone = date_default_timezone_get();
    // date_default_timezone_set($system_timezone);
    // $diff = (strtotime($gmt) - strtotime($local));
    // $date = new DateTime($gmttime);
    // $date->modify("+$diff seconds");
    // $timestamp = $date->format("Y-m-d H:i:s");
    