<?php


function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

// INCLUDING DATABASE AND MAKING OBJECT
require __DIR__.'/../classes/FileImport.php';
require __DIR__.'/../classes/Database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();




$response = [];
$importer = new CsvImporter($_FILES["csv"]["tmp_name"],true);
$csv = $importer->get();

foreach($csv as $item) {


// GET DATA FORM REQUEST

$returnData = [];


// CHECKING EMPTY FIELDS
if(!isset($item['name'])
    || !isset($item['email'])
    || !isset($item['password'])
    || empty(trim($item['name']))
    || empty(trim($item['email']))
    || empty(trim($item['password']))
    ):

    $fields = ['fields' => ['name','email','password']];
    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else:

    $name = trim($item['name']);
    $email = trim($item['email']);
    $password = trim($item['password']);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
        $returnData = msg(0,422, $email . ' - Invalid Email Address!');

    elseif(strlen($password) < 8):
        $returnData = msg(0,422, $email . ' - Your password must be at least 8 characters long!');

    elseif(strlen($name) < 3):
        $returnData = msg(0,422, $email . ' - Your name must be at least 3 characters long!');

    else:
        try{

            $check_email = "SELECT `email` FROM `users` WHERE `email`=:email";
            $check_email_stmt = $conn->prepare($check_email);
            $check_email_stmt->bindValue(':email', $email,PDO::PARAM_STR);
            $check_email_stmt->execute();

            if($check_email_stmt->rowCount()):
                $returnData = msg(0,422, $email . ' - This E-mail already in use!');

            else:
                $insert_query = "INSERT INTO `users`(`name`,`email`,`password`,`role`) VALUES(:name,:email,:password,:role)";

                $insert_stmt = $conn->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($name)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':email', $email,PDO::PARAM_STR);
                $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT),PDO::PARAM_STR);
                $insert_stmt->bindValue(':role', 'user',PDO::PARAM_STR);

                $insert_stmt->execute();

                $returnData = msg(1,201, $email . ' - You have successfully registered.');

            endif;

        }
        catch(PDOException $e){
            $returnData = msg(0,500,$e->getMessage());
        }
    endif;

endif;
array_push($response, $returnData);

}

echo json_encode($response);

