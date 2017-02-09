<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();
    }
    /**
     * Fetching single record to certify that a contact with those credential doesnt exist
     */
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();    
    }
    /**
     * Creating new contact method
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
            
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
        foreach($column_names as $desired_key){ // Check the obj received. If blank insert the same into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else{
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }
        $query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
            } else {
            return NULL;
        }
    }

    /**
     * Method called when the dash is loaded to reload the existing contacts
     */

public function getSession(){
    if (!isset($_SESSION)) {
        session_start();
    }
        $sess = array();
        $sess["uid"] = $_SESSION['uid'];
        $sess["name"] = $_SESSION['name'];
        $sess["email"] = $this ->readContact();
        $sess["contact"] = $this ->readContact(); //looks for all the existing contacts
    
    return $sess; 
}

    /**
     * function called when an update is called, it makes the corresponding query to update the corresponding table's row
     */
  public function updateTable($obj){
    $uid=$obj->uid;
    $name = $obj->name;
    $phone = $obj->phone;
    $address=$obj->address;
    $mysqli = $this->conn;
    $query="update registry set name='$name', phone='$phone', address='$address' where uid='$uid'";
    $result = $mysqli->query($query) or die($mysqli->error.__LINE__); //insert the query into mysql database's table
    
}

    /**
     * function called when a delete is called, it makes the corresponding query to delete the registry
     */
public function deleteContact($obj){

    $uid=$obj->uid;
    $mysqli = $this->conn;
    $query="delete from registry where uid='$uid'";
    $result = $mysqli->query($query) or die($mysqli->error.__LINE__);

    $result = $mysqli->affected_rows;
    return json_encode($result);

}

    /**
     * this method is the responsible for preload all the contacts that are shown... 
     */

public function readContact(){

    $mysqli = $this->conn;

    $query="select * from registry";
    $result = $mysqli->query($query) or die($mysqli->error.__LINE__);

    $arr = array();
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;  
        }
    }
    return $json_response = json_encode($arr);

}

}

?>
