<?php

namespace BLLv2;

class MySQLQueryStringGenerator
{
    public static function danhSachPhong()
    {
        $queryString = "call v2_sp_danhSachPhong();";
        return $queryString;
    }
}

?>