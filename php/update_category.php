<?php
require 'connect.php';
$data = json_decode(file_get_contents('php://input'), true);
$stmt = $db->prepare("UPDATE categories SET name=? WHERE id=?");
$stmt->execute([ $data['name'], (int)$data['id'] ]);
echo json_encode(['status'=>'ok']);
