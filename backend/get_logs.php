<?php
require '../includes/conexao.php';

$sql = "SELECT login_id, user_name, dt_access, user_id FROM users_login";
$result = $conn->query($sql);

$logs = [];

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $logs[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($logs);
?>
