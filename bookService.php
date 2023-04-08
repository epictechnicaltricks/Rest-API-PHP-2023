<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Database.php';
require __DIR__.'/AuthMiddleware.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$auth = new Auth($conn, $allHeaders);

echo json_encode($auth->Booked(
    $_REQUEST['booked_user_id'],
    $_REQUEST['booked_order_id'],
    $_REQUEST['booked_user_time'],
    $_REQUEST['booked_user_date'],
    $_REQUEST['booked_user_type'],
    $_REQUEST['bookservice_categoryid'],
    $_REQUEST['bookservice_categoryname'],
    $_REQUEST['bookservice_qty'],
    $_REQUEST['bookservice_defaultdescription'],
    $_REQUEST['bookservice_description'],
    $_REQUEST['bookservice_location'],
    $_REQUEST['bookservice_contactperson'],
    $_REQUEST['bookservice_mobileno'],
    $_REQUEST['bookservice_areapincode'],
    $_REQUEST['bookservice_emailid'],
    $_REQUEST['bookservice_order_type'],
    $_REQUEST['bookservice_paymentmode']));