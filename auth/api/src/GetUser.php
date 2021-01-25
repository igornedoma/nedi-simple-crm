<?php


require __DIR__.'/../classes/Database.php';
require __DIR__.'/../middlewares/Auth.php';


$db_connection = new Database();
$conn = $db_connection->dbConnection();
$allHeaders = getallheaders();
$auth = new Auth($conn,$allHeaders);

http_response_code(401);
$returnData = [
    "success" => 0,
    "status" => 401,
    "message" => "Unauthorized"
];
if($auth->isAuthAdmin()){
    $returnData = $auth->fetchUser($userId);
    http_response_code(200);
}

echo json_encode($returnData);