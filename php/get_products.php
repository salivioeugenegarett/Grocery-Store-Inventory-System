<?php
require 'connect.php';
$cat = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
if($cat>0) {
  $stmt = $db->prepare("SELECT * FROM products WHERE category_id=? ORDER BY id DESC");
  $stmt->execute([$cat]);
} else {
  $stmt = $db->query("SELECT * FROM products ORDER BY id DESC");
}
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
