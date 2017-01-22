<?php
if (!function_exists("tw")) require "functions.php";
if (!class_exists("Heater")) require "classHeater.php";
function temp($var) {
    global $params;
    return $params[$var]." &deg;C";
}
$params=getParams();
$heater=new Heater();
$params["BurnerState"]=$heater->getBurnerState();
echo json_encode($params);
?>
