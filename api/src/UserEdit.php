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
$data = json_decode(file_get_contents("php://input"));
$returnData = [];


if(!$auth->isAuthAdmin()):
    $returnData = msg(0,403,'This action is allowed only for admins!');
else:

// CHECKING EMPTY FIELDS
if(!isset($data->name)
    || !isset($data->email)
    || !isset($data->password)
    || !isset($data->role)
    || empty(trim($data->name))
    || empty(trim($data->email))
    || empty(trim($data->password))
    || empty(trim($data->role))
    ):

    $fields = ['fields' => ['name','email','password','role']];
    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else:

    $name = trim($data->name);
    $email = trim($data->email);
    $password = trim($data->password);
    $role = trim($data->role);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
        $returnData = msg(0,422,'Invalid Email Address!');

    elseif(strlen($password) < 8):
        $returnData = msg(0,422,'Your password must be at least 8 characters long!');

    elseif(strlen($name) < 3):
        $returnData = msg(0,422,'Your name must be at least 3 characters long!');

    elseif( !in_array($role, array("user", "admin"))):
            $returnData = msg(0,422,'You are allowed to choose role between user and admin');

    else:
        try{

            $check_email = "SELECT `email` FROM `users` WHERE `email`=:email";
            $check_email_stmt = $conn->prepare($check_email);
            $check_email_stmt->bindValue(':email', $email,PDO::PARAM_STR);
            $check_email_stmt->execute();

            if(!$check_email_stmt->rowCount()):
                $returnData = msg(0,422, 'This E-mail doesn`t exists!');

            else:
                $insert_query = "UPDATE `users` SET name = :name, password = :password, role = :role WHERE email = :email";

                $insert_stmt = $conn->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($name)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT),PDO::PARAM_STR);
                $insert_stmt->bindValue(':role', $role ,PDO::PARAM_STR);
                $insert_stmt->bindValue(':email', $email,PDO::PARAM_STR);
                $insert_stmt->execute();

                $returnData = msg(1,201,'Your user update was successful.');

            endif;

        }
        catch(PDOException $e){
            $returnData = msg(0,500,$e->getMessage());
        }
    endif;

endif;

endif;
echo json_encode($returnData);