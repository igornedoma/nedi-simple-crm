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
require __DIR__.'/../classes/FileImport.php';
require __DIR__.'/../classes/Database.php';
require __DIR__.'/../middlewares/Auth.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();
function pdoMultiInsert($tableName, $data, $pdoObject){

    //Will contain SQL snippets.
    $rowsSQL = array();

    //Will contain the values that we need to bind.
    $toBind = array();

    //Get a list of column names to use in the SQL statement.
    $columnNames = array_keys($data[0]);

    //Loop through our $data array.
    foreach($data as $arrayIndex => $row){
        $params = array();
        foreach($row as $columnName => $columnValue){
            $param = ":" . $columnName . $arrayIndex;
            $params[] = $param;
            $toBind[$param] = $columnValue;
        }
        $rowsSQL[] = "(" . implode(", ", $params) . ")";
    }

    //Construct our SQL statement
    $sql = "REPLACE INTO `$tableName` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);

    //Prepare our PDO statement.
    $pdoStatement = $pdoObject->prepare($sql);

    //Bind our values.
    foreach($toBind as $param => $val){
        if (strpos($param, 'password')) {
            $pdoStatement->bindValue($param, password_hash($val, PASSWORD_DEFAULT));
        } else {
            $pdoStatement->bindValue($param, $val);
        }
    }

    //Execute our statement (i.e. insert the data).
    return $pdoStatement->execute();
}
$returnData = [];
$allHeaders = getallheaders();
$auth = new Auth($conn,$allHeaders);

if(!$auth->isAuthAdmin()):
    $returnData = msg(0,403,'This action is allowed only for admins!');

else:


$importer = new CsvImporter($_FILES["csv"]["tmp_name"],true);
$csv = $importer->get();
$chunks = array_chunk($csv, 50);

foreach ($chunks as $chunk) {
    pdoMultiInsert('users', $chunk, $conn);
}
$returnData = msg(1,200,'Successfully imported!');
endif;
echo json_encode($returnData);