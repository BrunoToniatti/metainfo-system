<?php
$serverName = "LAPTOP-ELQUK5G2";
$connectionOptions = [
    "Database" => "barbearia",
    "TrustServerCertificate" => true,
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
