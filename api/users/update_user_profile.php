<?php
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/core.php';
include_once '../config/database.php';
include_once 'user.php';
include_once '../auth/jwt_operations/jwt_operations.php';

$database = new Database();
$db = $database->getConnection();
$jwt = new JWTOperations();
$isUserVerified = $jwt->verifyUser();

if($db && $isUserVerified){

    // check JWT and authenticate user 

    $user = new User($db);
     // get post data
    $data = json_decode(file_get_contents("php://input"));
     // set user data
    //$user->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    $user->password = filter_var($data->password, FILTER_SANITIZE_STRING);  
    $user->name = filter_var($data->name, FILTER_SANITIZE_STRING); 
    $user->phone_number = $data->phone_number;
    $user->address = filter_var($data->address, FILTER_SANITIZE_STRING);
    $user->organization = filter_var($data->organization, FILTER_SANITIZE_STRING);
    $user->updated_at = date('Y-m-d H:i:s');
    $user_id = $jwt->get_user_id();        // get user id from jwt token
    //$user->udpdate_profile($user_id);


}