<?php
require 'connect.php';
$data = json_decode(file_get_contents('php://input'), true);

$stmt = $db->prepare("UPDATE products SET name=?, quantity=?, price=?, size=? WHERE id=?");
$stmt->execute([
  $data['name'],
  (int)$data['quantity'],
  (float)$data['price'],
  $data['size'],
  (int)$data['id']
]);

echo json_encode(['status' => 'ok']);
