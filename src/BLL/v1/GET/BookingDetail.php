<?php

require $_SERVER['DOCUMENT_ROOT'] . "/hotel_management/src/BLL/v1/MySQLQueryStringGenerator.php";
require $_SERVER['DOCUMENT_ROOT'] . "/hotel_management/src/DAL/v1/MySQLiConnection.php";
require $_SERVER['DOCUMENT_ROOT'] . "/hotel_management/src/DTO/v1/BookingDetailDTO.php";

use BLLv1\MySQLQueryStringGenerator;
use DALv1\MySQLiConnection;
use DTOv1\BookingDetailDTO;

$soPhieuThue = $_GET["SoPhieuThue"];

$success = true;
$message = "";
$result = [];

$pattern = "/^[0-9]+$/"; // INT

$isValidData = preg_match($pattern, $soPhieuThue);

if (!$isValidData) {
    $success = false;
    $message = "Invalid parameters!";
} else {
    try {
        $connection = MySQLiConnection::instance();
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    if ($connection == null) {
        $success = FALSE;
        $message = "Unable to connect to the database!";
    } else {
        $queryString = MySQLQueryStringGenerator
            ::chiTietPhieuThue(
                $soPhieuThue
            );

        $dtoList = $connection->execQuery(
            $queryString,
            $isReading = true,
            BookingDetailDTO::getPrototype()
        );

        $result = [];
        foreach ($dtoList as $dto) {
            $result[] = $dto->toDictionary();
        }

        $success = true;
    }
} // if (!$isValidData)

$response = array(
    'success' => $success,
    'message' => $message,
    'result' => $result
);

echo json_encode($response);

?>