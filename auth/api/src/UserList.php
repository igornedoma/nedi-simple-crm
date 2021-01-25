<?php


function msg($success,$status,$message,$extra = []){
    http_response_code($status);
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

require __DIR__.'/../classes/Database.php';
require __DIR__.'/../middlewares/Auth.php';


$db_connection = new Database();
$conn = $db_connection->dbConnection();
$allHeaders = getallheaders();
$auth = new Auth($conn,$allHeaders);

$returnData = [];

if(!$auth->isAuthAdmin()):
    $returnData = msg(0,403,'This action is allowed only for admins!');
else:

try{
            $fetch_users = "SELECT `id`,`name`,`email`,`role` FROM `users`";
            $query_stmt = $conn->prepare($fetch_users);
            $query_stmt->execute();

            if($query_stmt->rowCount()):
                $row = $query_stmt->fetchAll(PDO::FETCH_ASSOC);
                $returnData = [
                    'success' => 1,
                    'status' => 200,
                    'users' => $row
                ];
            else:
                return null;
            endif;
        }
        catch(PDOException $e){
            return null;
        }

endif;
echo json_encode($returnData);