<?php
require 'connect.php';
$stmt = $db->query("SELECT * FROM categories ORDER BY name");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
