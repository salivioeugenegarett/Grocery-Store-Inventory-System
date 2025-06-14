<?php
require 'connect.php';
$data = json_decode(file_get_contents('php://input'), true);
$stmt = $db->prepare("DELETE FROM products WHERE id=?");
$stmt->execute([(int)$data['id']]);
echo json_encode(['status'=>'deleted']);
