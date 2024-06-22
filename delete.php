<?php

include_once('connects.php');

$id = $_POST['id'] ?? null;

if(!$id){
    header('Location: customers.php');
    exit;
}

$statement = $conn->prepare("DELETE FROM customer WHERE customer_id = :id");
$statement -> bindValue(':id', $id);
$statement -> execute();

header('Location: customers.php');

?>