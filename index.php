<?php
require_once(__DIR__ . "/autoload.php");

use App\Classes\JsonDatabase;
use App\Classes\JsonTableExporter;

$columns = [
    "user_name",
    "first_name",
    "last_name",
];

$data = [
    "user_name" => "raf88",
    "first_name"=>"Rafael",
    "last_name"=>"Mor",
];

$data2 = [
    "user_name" => "dikla96",
    "first_name"=>"David",
    "last_name"=>"Cohen",
];

$dataNew = [
    "user_name" => "dikla96",
    "first_name"=>"Dikla",
    "last_name"=>"Cohen",
];

$tableName = "users";
$pathToStorage = $_SERVER["DOCUMENT_ROOT"] . "/App/Storage/";

$database = new JsonDatabase($pathToStorage, "users", $columns);
$database->add($data);
$database->add($data2);
$database->add($data2);
$database->delete(3);
$database->update(2, $dataNew);

$columns2 = [
    "id",
    "user_name",
];

$export = new JsonTableExporter($pathToStorage, "users", $columns2);
$export->exportToFile("newUsers");
