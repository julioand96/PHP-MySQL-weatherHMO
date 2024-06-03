<?php

$sname= "localhost";
$uname= "julioand96";
$password = "andrees1";

$db_name = "weather_hmo";

$conn_weather = mysqli_connect($sname, $uname, $password, $db_name);

if (!$conn_weather) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
