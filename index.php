<?php
include('pdo_object.php');
define('BASE_DIR', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

spl_autoload_register(function ($classname) {
	// convert the classname to lowercase
	$filename = strtolower($classname);
	if (file_exists(BASE_DIR.DS."models/{$filename}.php"))
		@ require_once BASE_DIR.DS."models/{$filename}.php";
});

$action = "";
$parameters = null;
// Read either GET or POST values using $_REQUEST variable
if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
}

// Read the request and separate data into variables
$info = explode('/', $action);
$prefix = '';

if (isset($info[0]) && $info[0] != '') {
    // Read the 'prefix'
    $prefix = array_shift($info);
}

/* * ************************** Define  the controller's method to obtain data  ****************************** */
$method = '';
// Use the array_shift method to extract the first element in $info array
if (count($info) > 0) {
    // Read the controller's method that needs to be invoked
    $method = array_shift($info);
}

/* * *************************   Define the parameters **************************************************** */

$parameters = (count($info) > 0) ? $info : null;
// Define the model
$actionlName = ucfirst($prefix) . 'Model';

// If the required class exists then create an instance of the 'model' class
if (class_exists($actionlName)) {
    $actionl = new $actionlName();
} else {
    // Invalid request
    echo json_encode([]);
    exit();
}

try {
    if (method_exists($actionl, $method)) {
        // Invoke the requested method to obtain data
        $data_set = $actionl->$method($parameters);

        // Include Access-Control directives
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT,DELETE");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials", "true");

        if (isset($data_set)) {
            echo json_encode($data_set);
        } else {
            echo json_encode([]);
        }
    } else {
        // return an empty array
        echo json_encode([]);
    }
} catch (Exception $exception) {
    // return an empty array
    echo json_encode([]);
}
?>

