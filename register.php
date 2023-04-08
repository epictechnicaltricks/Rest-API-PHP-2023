<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__ . '/classes/Database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

function msg($success, $status, $message, $extra = [])
{
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}

// DATA FORM REQUEST
//$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

elseif (
    !   isset($_REQUEST['customer_name'])
    || !isset($_REQUEST['customer_emailid'])
    || !isset($_REQUEST['customer_password'])
    || !isset($_REQUEST['customer_mobileno'])
    || !isset($_REQUEST['customer_address'])

    || empty(trim($_REQUEST['customer_name']))
    || empty(trim($_REQUEST['customer_emailid']))
    || empty(trim($_REQUEST['customer_password']))
    || empty(trim($_REQUEST['customer_mobileno']))
    || empty(trim($_REQUEST['customer_address']))



) :

    $fields = ['fields' => ['customer_name', 'customer_emailid', 'customer_password', 'customer_mobileno', 'customer_address']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :

    $customer_name = trim($_REQUEST['customer_name']);
    $customer_emailid = trim($_REQUEST['customer_emailid']);
    $customer_password = trim($_REQUEST['customer_password']);
    $customer_mobileno = trim($_REQUEST['customer_mobileno']);
    $customer_address = trim($_REQUEST['customer_address']);


    if (!filter_var($customer_emailid, FILTER_VALIDATE_EMAIL)) :
        $returnData = msg(0, 422, 'Invalid customer_emailid Address!');

    elseif (strlen($customer_password) < 8) :
        $returnData = msg(0, 422, 'Your customer_password must be at least 8 characters long!');

    elseif (strlen($customer_name) < 3) :
        $returnData = msg(0, 422, 'Your customer_name must be at least 3 characters long!');

    else :
        try {

            $check_customer_emailid = "SELECT `customer_emailid` FROM `cnz_customerregister` WHERE `customer_emailid`=:customer_emailid";
            $check_customer_emailid_stmt = $conn->prepare($check_customer_emailid);
            $check_customer_emailid_stmt->bindValue(':customer_emailid', $customer_emailid, PDO::PARAM_STR);
            $check_customer_emailid_stmt->execute();

            if ($check_customer_emailid_stmt->rowCount()) :
                $returnData = msg(0, 422, 'This E-mail already in use!');

            else :
                $insert_query = "INSERT INTO `cnz_customerregister`(`customer_name`,`customer_emailid`,`customer_password`,`customer_mobileno`,`customer_address`) VALUES(:customer_name,:customer_emailid,:customer_password,:customer_mobileno,:customer_address)";

                $insert_stmt = $conn->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':customer_name', htmlspecialchars(strip_tags($customer_name)), PDO::PARAM_STR);
                $insert_stmt->bindValue(':customer_emailid', $customer_emailid, PDO::PARAM_STR);
                $insert_stmt->bindValue(':customer_password',  password_hash($customer_password, PASSWORD_DEFAULT), PDO::PARAM_STR);
                $insert_stmt->bindValue(':customer_mobileno',htmlspecialchars(strip_tags($customer_mobileno)), PDO::PARAM_STR);
                $insert_stmt->bindValue(':customer_address', htmlspecialchars(strip_tags($customer_address)), PDO::PARAM_STR);
                $insert_stmt->execute();

                $returnData = msg(1, 200, 'You have successfully registered.');

            endif;
        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
        }
    endif;
endif;

echo json_encode($returnData);