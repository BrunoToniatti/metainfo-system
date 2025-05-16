<?php
header('Content-Type: application/json');
require '../includes/conexao.php';

$sql = "SELECT atendimento_id, cliente, dt_atendimento, servico, valor, profissional, horario FROM atendimento";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo json_encode(["error" => $errors]);
    exit;
}

$atendimentos = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $row['dt_atendimento'] = $row['dt_atendimento'] instanceof DateTime ? $row['dt_atendimento']->format('Y-m-d') : null;
    $row['horario'] = $row['horario'] instanceof DateTime ? $row['horario']->format('H:i:s') : null;
    $atendimentos[] = $row;
}

echo json_encode($atendimentos);
