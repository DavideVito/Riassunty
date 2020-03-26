<?php 
session_start();
header("content-type: application/json");
$arr['sessione'] = isset($_SESSION['ID']);
echo json_encode($arr);


?>