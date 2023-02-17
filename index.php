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
//$database->add($data);
//$database->add($data2);
//$database->add($data2);
//dd($database->update(2, $dataNew));
//$database->delete(3);
//dd($database->readFile());


$columns2 = [
    "id",
    "user_name",
];

$export = new JsonTableExporter($pathToStorage, "users", $columns2);
dd($export->export());
$export->exportToFile("newUsers");





function dd($var, $die = false) {
    if ($die = false){
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }else{
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        die();
    }
}