<?php
include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$change = $data['change'] ?? null;

if ($id === null || $change === null) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing parameters']);
  exit;
}

$stmt = $db->prepare("UPDATE products SET quantity = quantity + :change WHERE id = :id");
$stmt->execute([':change' => $change,':id' => $id]);

echo json_encode(['success' => true]);
?>
