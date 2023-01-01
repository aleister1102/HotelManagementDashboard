<?php

require $_SERVER['DOCUMENT_ROOT'] . "/hotel_management/src/BLL/v1/MySQLQueryStringGenerator.php";
require $_SERVER['DOCUMENT_ROOT'] . "/hotel_management/src/DAL/v1/MySQLiConnection.php";
require $_SERVER['DOCUMENT_ROOT'] . "/hotel_management/src/DTO/v1/BookingDTO.php";

use BLLv1\MySQLQueryStringGenerator;
use DALv1\MySQLiConnection;
use DTOv1\BookingDTO;

$success = true;
$message = "";
$result = [];

try {
    $connection = MySQLiConnection::instance();
} catch (Exception $e) {
    echo $e->getMessage();
}

if ($connection == null) {
    $success = false;
    $message = "Unable to connect to the database!";
} else {
    $queryString = MySQLQueryStringGenerator::danhSachPhieuThue();

    $dtoList = $connection->execQuery(
        $queryString,
        $isReading = true,
        BookingDTO::getPrototype()
    );

    $result = [];
    foreach ($dtoList as $dto) {
        $result[] = $dto->toDictionary();
    }

    $success = true;
}

$response = array(
    'success' => $success,
    'message' => $message,
    'result' => $result
);

echo json_encode($response);

?>