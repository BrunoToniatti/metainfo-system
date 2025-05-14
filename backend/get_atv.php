<?php
require '../includes/conexao.php';

$sql = "SELECT atv_id, user_name, dt_atv, atv FROM users_atv";
$result = $conn->query($sql);

$atvs = [];

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $atvs[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($atvs);
?>
