<?php

use Router\Route;
use Env\DotEnv;

// Include router class
include 'api/src/Router/Route.php';
include 'api/src/Env/EnvLoader.php';

(new DotEnv(__DIR__ . '/.env'))->load();

$env = getenv('APP_ENV');
// Define a global basepath
define('BASEPATH','/auth');

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");
header("Cross-Origin-Window-Policy: deny");


// Add base route (startpage)
Route::add('/', function() {
  echo 'Nedi Simple CRM';
});
if($env == 'dev') {
    Route::add('/phpinfo', function() {
      phpinfo();
    });

    Route::add('/adminer', function() {
      include(dirname(__FILE__).'/api/src/adminer-4.7.8-cs.php');
    }, ['get', 'post', 'delete', 'patch']);
}
Route::add('/admin', function() {
  include(dirname(__FILE__).'/crm-admin/dist/index.html');
});


// Login
Route::add('/api/login', function() {
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  include(dirname(__FILE__).'/api/src/Login.php');
}, 'post');

// CSV Import
Route::add('/api/import', function() {
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  include(dirname(__FILE__).'/api/src/CsvImport.php');
}, 'post');

// Registration
Route::add('/api/registration', function() {
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  include(dirname(__FILE__).'/api/src/Register.php');
}, 'post');

// User Edit
Route::add('/api/user-edit', function() {
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  include(dirname(__FILE__).'/api/src/UserEdit.php');
}, 'post');

// User Info
Route::add('/api/user-info', function() {
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  include(dirname(__FILE__).'/api/src/UserInfo.php');
}, 'get');

// Get User
Route::add('/api/get-user/([0-9]*)', function($userId) {
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  include(dirname(__FILE__).'/api/src/GetUser.php');
}, 'get');

// User list
Route::add('/api/user-list', function() {
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  include(dirname(__FILE__).'/api/src/UserList.php');
}, 'get');
// User delete
Route::add('/api/user-delete/([0-9]*)', function($deleteId) {
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  include(dirname(__FILE__).'/api/src/UserDelete.php');
}, 'delete');


// Add a 404 not found route
Route::pathNotFound(function($path) {
  // Do not forget to send a status header back to the client
  // The router will not send any headers by default
  // So you will have the full flexibility to handle this case
  header('HTTP/1.0 404 Not Found');
  
  echo 'Error 404 :-(<br>';
  echo 'The requested path "'.$path.'" was not found!';
});

// Add a 405 method not allowed route
Route::methodNotAllowed(function($path, $method) {
  // Do not forget to send a status header back to the client
  // The router will not send any headers by default
  // So you will have the full flexibility to handle this case
  header('HTTP/1.0 405 Method Not Allowed');
  
  echo 'Error 405 :-(<br>';
  echo 'The requested path "'.$path.'" exists. But the request method "'.$method.'" is not allowed on this path!';
});

// Run the Router with the given Basepath
Route::run(BASEPATH);

// Enable case sensitive mode, trailing slashes and multi match mode by setting the params to true
// Route::run(BASEPATH, true, true, true);
