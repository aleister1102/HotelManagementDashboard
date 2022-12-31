<?php

use Views\View;

require_once "../vendor/autoload.php";

$routes = [];

function fetchAPI($url)
{
    $response = file_get_contents($url);
    $response = json_decode($response, true);

    if ($response['success'] == true) {
        return $response['result'];
    } else {
        $message = $response['message'];
        echo $message;
    }
}

route("home", function () {
    View::renderView(
        "home",
        [
            "entries" => [
                [
                    ["value" => 'A',],
                    ["value" => 'A1.2',],
                    ["value" => '30/2/2022',],
                    ["value" => 'Nguyễn Văn A',],
                    ["value" => '0999888777',],
                    ["value" => '01010101010',],
                ],
            ]
        ]
    );
});

route("room", function () {
    $url = 'http://localhost/hotel_management/src/BLL/v2/GET/RoomList.php';
    $rooms = fetchAPI($url);

    $entries = [];
    foreach ($rooms as $index => $room) {
        $entry = [
            ["value" => $index + 1],
            ["value" => $room['MaPhong']],
            ["value" => $room['MaLoai']],
            ["value" => $room['DonGia']],
            ["value" => $room['TinhTrang']],
        ];
        $entries[] = $entry;
    }

    View::renderView("room", ["entries" => $entries]);
});

route("booking", function () {
    $action = $_GET['action'] ?? "";

    if ($action == "edit") {
        View::renderView(
            "booking",
            [
                "action" => $action,
                "fields" => [
                    "STT",
                    "Tên phòng",
                    "Loại phòng",
                    "Đơn giá",
                    "Trạng thái",
                ],
                "entries" => [
                    [
                        [
                            "value" => "1",
                        ],
                        [
                            "value" => "1403",
                            "editable" => true
                        ],
                        [
                            "value" => "Chọn loại phòng|Loại A|Loại B|Loại C",
                        ],
                        [
                            "value" => "150.000",
                            "editable" => true
                        ],
                        [
                            "value" => "Trống",
                        ],
                    ]
                ],
                "buttons" =>
                [
                    ["text" => "Lưu thay đổi"]
                ]
            ]
        );
    } else if ($action == "delete") {
        View::renderView(
            "booking",
            [
                "action" => $action,
                "fields" => [
                    "STT",
                    "Tên phòng",
                    "Loại phòng",
                    "Đơn giá",
                    "Trạng thái",
                    "Chọn"
                ],
                "entries" => [
                    [
                        ["value" => "1",],
                        ["value" => "1403",],
                        ["value" => "Loại A",],
                        ["value" => "150.000",],
                        ["value" => "Trống",],
                    ],
                    [
                        ["value" => "2",],
                        ["value" => "1403",],
                        ["value" => "Loại A",],
                        ["value" => "150.000",],
                        ["value" => "Trống",],
                    ],
                    [
                        ["value" => "2",],
                        ["value" => "1403",],
                        ["value" => "Loại A",],
                        ["value" => "150.000",],
                        ["value" => "Trống",],
                    ],
                ],
                "buttons" =>
                [
                    [
                        "text" => "Xóa các dòng đã chọn",
                        "handler" => "deleteSelectedEntries()"
                    ]
                ]
            ]

        );
    } else if ($action == "add") {
        View::renderView(
            "booking",
            [
                "action" => $action,
                "fields" => [
                    "STT",
                    "Tên phòng",
                    "Loại phòng",
                    "Đơn giá",
                    "Trạng thái",
                ],
                "entries" => [
                    [
                        ["value" => "1",],
                        ["value" => "1403", "editable" => true],
                        ["value" => "Chọn loại phòng|Loại A|Loại B|Loại C",],
                        ["value" => "150.000", "editable" => true],
                        ["value" => "Trống",],
                    ]
                ],
                "buttons" =>
                [
                    ["text" => "Thêm"],
                ]

            ]
        );
    } else if ($action == "justify") {
        View::renderView(
            "booking",
            [
                "action" => $action,
                "fields" => [
                    "STT",
                    "Loại phòng",
                    "Đơn giá",
                    "Chọn"
                ],
                "entries" => [
                    [
                        ["value" => "1",],
                        ["value" => "A",],
                        ["value" => "150.000",   "editable" => true],
                    ],
                    [
                        ["value" => "1",],
                        ["value" => "B",],
                        ["value" => "150.000", "editable" => true],
                    ],
                    [
                        ["value" => "1",],
                        ["value" => "C",],
                        ["value" => "150.000", "editable" => true],
                    ],
                    [
                        ["value" => "1",],
                        ["value" => "A",],
                        [
                            "value" => "Điền giá của loại phòng này",
                            "editable" => true
                        ],
                    ],
                ],
                "buttons" =>
                [
                    [
                        "text" => "Lưu thay đổi",
                    ]
                ]

            ]
        );
    } else {
        View::renderView(
            "booking",
            [
                "action" => "view",
                "fields" => [
                    'stt',
                    'Khách hàng',
                    'Loại khách',
                    'CMND',
                    'Địa chỉ',
                ],
                "entries" => [
                    [
                        ["value" => "1",],
                        ["value" => "1403",],
                        ["value" => "Loại A",],
                        ["value" => "150.000",],
                        ["value" => "Trống",],
                    ]
                ]
            ]
        );
    }
});

route("customer", function () {
    $url = 'http://localhost/hotel_management/src/BLL/v1/GET/CustomerList.php';
    $customers = fetchAPI($url);

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
    $url = 'http://localhost/hotel_management/src/BLL/v1/GET/BillList.php';
    $bills = fetchAPI($url);

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

    $uri = $_SERVER['REQUEST_URI']; // /hotel-management-system/UI/public/home?param=123
    $route = explode('/', $uri)[4]; // home?param=123
    $route = $route  == "" ? "home" : $route; // home?param=123
    $route = explode('?', $route)[0]; // home


    if (array_key_exists($route, $routes)) {
        $routes[$route]();
    } else {
        echo "[404] Not found 😔";
    }
}

run();
