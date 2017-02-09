<?php 

//*** Method called when a get request is made to /api/v1/session and make a response with the contacts already created

$app->get('/session', function() {
    $db = new DbHandler(); //Creates a DbHandler's Instance

    $session = $db->getSession(); //calls the DbHandler's method 'getSession' which returns a Json with all the contacts

    $response["uid"] = $session['uid'];
    $response["phone"] = $session['phone'];
    $response["name"] = $session['name'];
    $response["contact"]=$session['contact']; //saves the info in an array

    echoResponse(200, $session); //response the data
});

//*** method called when a post request is made to  /api/v1/signup to create a new contact

$app->post('/signUp', function() use ($app) {
   
    $response = array();
    $r = json_decode($app->request->getBody()); //Get the json parameters in the request's body
    verifyRequiredParams(array('phone', 'name', 'address'),$r->customer); //verify all field were filled first
    
    $db = new DbHandler(); //Creates a DbHandler's Instance


    $phone = $r->customer->phone;
    $name = $r->customer->name;
    $address = $r->customer->address;
    
    $isUserExists = $db->getOneRecord("select 1 from registry where phone='$phone' or address='$address'"); //looks in the db for a contact with the same credentials, if it's true, there is already an user with those credentials
  
            if(!$isUserExists){

                $tabble_name = "registry";
                $column_names = array('phone', 'name', 'address');

                $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name); //Insert into the database the new table's row

                //checks the query result to make the respomse
                if ($result != NULL) {
                    $response["status"] = "success";
                    $response["message"] = "New contact created successfully";
                    $response["uid"] = $result;

                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    $_SESSION['uid'] = $response["uid"];
                    $_SESSION['phone'] = $phone;
                    $_SESSION['name'] = $name;
                    echoResponse(200, $response);
                } else {
                    $response["status"] = "error";
                    $response["message"] = "Failed to create new contact. Please try again";
                    echoResponse(201, $response);
                }            
            }else{
                $response["status"] = "error";
                $response["message"] = "An registry with the provided phone or address exists!";
                echoResponse(201, $response);
            }
            
});

//*** this method is called when an post is request to /api/v1/update to update a existing contact

$app->post('/update', function() use ($app){

    $db = new DbHandler(); //Creates a DbHandler's Instance

    $r = json_decode($app->request->getBody());//decode the body to an array

    $db->updateTable($r->customer); //calls the dbHandler method updateTable which updates and existing row

    $response["status"] = "success";
    $response["message"] = "Contact updated successfully";
    echoResponse(200, $response); //makes the response

});


//*** this method is called when an post is requested to /api/v1/delete to delete a existing contact

$app->post('/delete', function() use ($app){

    $db = new DbHandler(); //Creates a DbHandler's Instance

    $r = json_decode($app->request->getBody()); //decode the body into an array

    $db->deleteContact($r->customer); //Calls the delete method to delete an existing contact

    $response["status"] = "success";
    $response["message"] = "Contact deleted successfully";
    echoResponse(200, $response); //makes the response

});


?>
