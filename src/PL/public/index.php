<?php

use Views\View;

require_once "../vendor/autoload.php";

//! Có thể sửa hotel_management thành tên thư mục tùy ý
define("API_ROOT", "http://localhost/hotel_management/");

$routes = [];

function fetchAPI($uri)
{
    $response = file_get_contents($uri);
    $response = json_decode($response, true);

    if ($response['success'] == true) {
        return $response['result'];
    } else {
        $message = $response['message'];
        echo $message;
    }
}

function getRoomTypes($editable = false)
{
    $uri = API_ROOT . 'src/BLL/v1/GET/RoomCategoryList.php';
    $roomTypes = fetchAPI($uri);

    $entries = [];
    foreach ($roomTypes as $index => $roomType) {
        $entries[] = [
            ["value" => $index + 1],
            ["value" => $roomType['MaLoai'], "editable" => $editable],
            ["value" => $roomType['SoLuongPhong'], "editable" => $editable],
            ["value" => $roomType['DonGia'], "editable" => $editable],
            ["value" => $roomType['LuongKhachToiDa'], "editable" => $editable],
        ];
    }
    return $entries;
}

function makeRoomTypeOptions()
{
    $roomTypes = getRoomTypes();
    $options = array_map(function ($roomType) {
        return $roomType[1]['value'];
    }, $roomTypes);
    return $options;
}

function getRooms($editable = false)
{
    $uri = API_ROOT . 'src/BLL/v2/GET/RoomList.php';
    $rooms = fetchAPI($uri);

    $entries = [];
    foreach ($rooms as $index => $room) {
        $entries[] = [
            ["value" => $index + 1],
            ["value" => $room['MaPhong'], "editable" => $editable],
            ["value" => $room['MaLoai'], "options" => makeRoomTypeOptions()],
            ["value" => $room['DonGia'], "editable" => $editable],
            ["value" => $room['TinhTrang'], "editable" => $editable],
        ];
    }
    return $entries;
}

function getBookings($editable = false)
{
    $uri =  API_ROOT . 'src/BLL/v1/GET/BookingList.php';
    $bookings = fetchAPI($uri);

    //? Trường 'ngày bắt đầu thuê' và 'số ngày thuê' đem qua chi tiết thuê
    $entries = [];
    foreach ($bookings as $index => $booking) {
        $entries[] = [
            ["value" => $index + 1],
            ["value" => $booking['SoPhieuThue'], "editable" => $editable],
            ["value" => $booking['ID_KhachHang'], "editable" => $editable],
            ["value" => $booking['MaPhong'], "editable" => $editable],
        ];
    }
    return $entries;
}

route("home", function () {
    $uri =  API_ROOT . 'src/BLL/v1/GET/BookingList.php';
    $bookings = fetchAPI($uri);

    $entries = [];
    foreach ($bookings as $index => $booking) {
        if ($index > 5) break;
        $entry = [
            ["value" => $index + 1],
            ["value" => $booking['SoPhieuThue']],
            ["value" => $booking['ID_KhachHang']],
            ["value" => $booking['NgayBatDauThue']],
            ["value" => $booking['SoNgayThue']],
            ["value" => $booking['MaPhong']],
        ];
        $entries[] = $entry;
    }

    View::renderView("home", ["entries" => $entries]);
});

route("room", function () {
    $action = $_GET['action'] ?? "view";

    if ($action == "edit") {
        View::renderView("room", [
            "action" => $action,
            "entries" => getRooms(true),
            "buttons" => [
                ["text" => "Lưu thay đổi"],
            ],
        ]);
    } else if ($action == "delete") {
        View::renderView("room", [
            "action" => $action,
            "entries" => getRooms(),
            "buttons" =>
            [
                ["text" => "Xóa các dòng đã chọn", "handler" => "deleteSelectedEntries()"],
                ["text" => "Lưu thay đổi",],
            ]
        ]);
    } else if ($action == "add") {
        View::renderView("room", [
            "action" => $action,
            "entries" => getRooms(),
            "buttons" =>
            [
                ["text" => "Thêm"],
            ]
        ]);
    } else if ($action == "justify") {
        View::renderView("room", [
            "action" => $action,
            "entries" => getRoomTypes(true),
            "buttons" =>
            [
                [
                    "text" => "Xóa các dòng đã chọn",
                    "handler" => "deleteSelectedEntries()"
                ],
                ["text" => "Lưu thay đổi"],
            ]
        ]);
    } else {
        View::renderView("room", [
            "action" => $action,
            "entries" => getRooms(),
        ]);
    }
});

route("booking", function () {
    $action = $_GET['action'] ?? "view";

    if ($action == "edit") {
        View::renderView("booking", [
            "action" => $action,
            "entries" => getBookings(true),
            "buttons" => [
                ["text" => "Lưu thay đổi"],
            ]
        ]);
    } else if ($action == "delete") {
        View::renderView("booking", [
            "action" => $action,
            "entries" => getBookings(),
            "buttons" =>
            [
                [
                    "text" => "Xóa các dòng đã chọn",
                    "handler" => "deleteSelectedEntries()"
                ],
                [
                    "text" => "Lưu thay đổi",
                ],
            ]
        ]);
    } else if ($action == "add") {
        View::renderView("booking", [
            "action" => $action,
            "entries" => getBookings(),
            "buttons" =>
            [
                ["text" => "Thêm"],
            ]
        ]);
    } else if ($action == "justify") {
        View::renderView("booking", ["action" => $action]);
    } else {
        View::renderView("booking", [
            "action" => $action,
            "entries" => getBookings(),
        ]);
    }
});

route("customer", function () {
    $uri =  API_ROOT .  'src/BLL/v1/GET/CustomerList.php';
    $customers = fetchAPI($uri);

    $entries = [];
    foreach ($customers as $index => $customer) {
        $entry = [
            ["value" => $index + 1],
            ["value" => $customer['IDKhachHang']],
            ["value" => $customer['LoaiKhach']],
            ["value" => $customer['HoTen']],
            ["value" => $customer['SoDienThoai']],
            ["value" => $customer['CMND']],
        ];
        $entries[] = $entry;
    }

    View::renderView("customer", ["entries" => $entries]);
});

route("bill", function () {
    $uri =  API_ROOT . 'src/BLL/v1/GET/BillList.php';
    $bills = fetchAPI($uri);

    $entries = [];
    foreach ($bills as $index => $bill) {
        $entry = [
            ["value" => $index + 1],
            ["value" => $bill['SoHoaDon']],
            ["value" => $bill['NgayThanhToan']],
            ["value" => $bill['TriGia'] ?? "Chưa có"],
        ];
        $entries[] = $entry;
    }

    View::renderView("bill", ["entries" => $entries]);
});

route("report", function () {
    View::renderView("report");
});

route("form", function () {
    $formType = $_GET['type'] ?? "";

    if ($formType == 'report') {
        $month = $_GET['month'] ?? "";

        View::renderView("form", [
            "type" => "revenue-report",
            "month" => $month,
            "fields" =>
            [
                'STT',
                'Loại phòng',
                'Doanh thu',
                'Tỷ lệ',
            ],
            "entries" =>
            [
                [
                    ["value" => '1',],
                    ["value" => 'A',],
                    ["value" => '150.000.000',],
                    ["value" => '33.3%',],
                ],
                [
                    ["value" => '1',],
                    ["value" => 'A',],
                    ["value" => '150.000.000',],
                    ["value" => '33.3%',],
                ],
                [
                    ["value" => '1',],
                    ["value" => 'A',],
                    ["value" => '150.000.000',],
                    ["value" => '33.3%',],
                ],
            ]
        ]);
        View::renderView("form", [
            "type" => "room-report",
            "month" => $month,
            "fields" =>
            [
                'STT',
                'Phòng',
                'Số ngày thuê',
                'Tỷ lệ',
            ],
            "entries" =>
            [
                [
                    ["value" => '1',],
                    ["value" => 'A1.2',],
                    ["value" => '11',],
                    ["value" => '33.3%',],
                ],
                [
                    ["value" => '1',],
                    ["value" => 'A1.2',],
                    ["value" => '11',],
                    ["value" => '33.3%',],
                ],
                [
                    ["value" => '1',],
                    ["value" => 'A1.2',],
                    ["value" => '11',],
                    ["value" => '33.3%',],
                ],
            ]
        ]);
    } else {
        View::renderView("form", [
            "type" => $formType
        ]);
    }
});

// Hàm thêm route
function route(string $path, callable $callback)
{
    global $routes;
    $routes[$path] = $callback;
}

// Hàm chạy route dựa trên request uri
function run()
{
    global $routes;

    $uri = $_SERVER['REQUEST_URI'];
    $route = explode('/', $uri);
    $route = end($route);
    $route = $route  == "" ? "home" : $route;
    $route = explode('?', $route)[0];


    if (array_key_exists($route, $routes)) {
        $routes[$route]();
    } else {
        echo "[404] Not found 😔";
    }
}

run();
