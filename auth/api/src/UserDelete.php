<?php

function msg($success,$status,$message,$extra = []){
    http_response_code($status);
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

// INCLUDING DATABASE AND MAKING OBJECT
require __DIR__.'/../classes/Database.php';
require __DIR__.'/../middlewares/Auth.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$allHeaders = getallheaders();
$auth = new Auth($conn,$allHeaders);

// GET DATA FORM REQUEST
//$data = json_decode(file_get_contents("php://input"));

$returnData = [];


if(!$auth->isAuthAdmin()):
    $returnData = msg(0,403,'This action is allowed only for admins!');
else:

        try{

            $check_id = "SELECT `id` FROM `users` WHERE `id`=:id";
            $check_id_stmt = $conn->prepare($check_id);
            $check_id_stmt->bindValue(':id', $deleteId,PDO::PARAM_STR);
            $check_id_stmt->execute();

            if(!$check_id_stmt->rowCount()):
                $returnData = msg(0,422, 'This User doesn`t exists!');

            else:
                $delete_query = "DELETE FROM `users` WHERE id = :id";

                $delete_stmt = $conn->prepare($delete_query);

                // DATA BINDING

                $delete_stmt->bindValue(':id', $deleteId,PDO::PARAM_STR);
                $delete_stmt->execute();

                $returnData = msg(1,200,'User was deleted.');

            endif;

        }
        catch(PDOException $e){
            $returnData = msg(0,500,$e->getMessage());
        }
endif;

echo json_encode($returnData);