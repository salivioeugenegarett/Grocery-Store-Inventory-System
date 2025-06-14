<?php
require 'connect.php';
$data = json_decode(file_get_contents('php://input'), true);
$db->prepare("DELETE FROM products WHERE category_id=?")->execute([(int)$data['id']]);
$db->prepare("DELETE FROM categories WHERE id=?")->execute([(int)$data['id']]);
echo json_encode(['status'=>'deleted']);
