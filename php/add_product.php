<?php
require 'connect.php';
$data = json_decode(file_get_contents('php://input'), true);

$stmt = $db->prepare("INSERT INTO products (name, quantity, price, size, category_id) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([
  $data['name'],
  (int)$data['quantity'],
  (float)$data['price'],
  $data['size'],
  (int)$data['category_id']
]);

echo json_encode(['id' => $db->lastInsertId()]);
