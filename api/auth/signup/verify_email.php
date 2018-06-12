<?php
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Kolkata');

include_once '../../config/core.php';
include_once '../../config/database.php';
include_once '../../users/user.php';

$database = new Database();
$db = $database->getConnection();

if($db){

     $user = new User($db);

    // get post data
    $data = json_decode(file_get_contents("php://input"));
    

    // set user data
    $user->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);

    // Check if user email present previously or not
    if($user->check_email()){
        echo json_encode(["message" =>'e-mail not present']);
    } else {
        echo json_encode(["message" =>'e-mail already present']);
        http_response_code(405);
    }

}