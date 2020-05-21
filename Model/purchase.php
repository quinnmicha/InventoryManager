<?php

session_start();

$id = filter_input(INPUT_POST, 'id');
$unitPrice=filter_input(INPUT_POST, 'unitPrice');
$purchaseAmount=filter_input(INPUT_POST, 'purchaseAmount');

array_push($_SESSION['itemId'], $id);
array_push($_SESSION['unitPrice'], $unitPrice);
array_push($_SESSION['purchaseAmount'], $purchaseAmount);

/*
$_SESSION["itemId"] = filter_input(INPUT_POST, 'id');
$_SESSION["unitPrice"] = filter_input(INPUT_POST, 'unitPrice');
$_SESSION["purchaseAmount"] = filter_input(INPUT_POST, 'purchaseAmount');

*/


?>
