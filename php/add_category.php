<?php
require 'connect.php';
$data = json_decode(file_get_contents('php://input'), true);
$stmt = $db->prepare("INSERT INTO categories (name) VALUES (?)");
$stmt->execute([ $data['name'] ]);
echo json_encode(['id'=>$db->lastInsertId(),'name'=>$data['name']]);
