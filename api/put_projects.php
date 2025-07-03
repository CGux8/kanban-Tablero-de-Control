<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "kanban_dashboard");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "ID de proyecto no proporcionado"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["error" => "Datos JSON inválidos: " . json_last_error_msg()]);
    exit;
}

$name = $data['name'] ?? '';
$desc = $data['desc'] ?? '';
$userId = isset($data['userId']) ? (int)$data['userId'] : null;
$start = $data['start'] ?? '';
$end = $data['end'] ?? '';
$progress = isset($data['progress']) ? (int)$data['progress'] : 0;
$status = $data['status'] ?? 'todo';

if (empty($name) || !$userId || empty($start) || empty($end)) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan campos requeridos"]);
    exit;
}

$validStatuses = ['todo', 'in-progress', 'completed'];
if (!in_array($status, $validStatuses)) {
    $status = 'todo';
}

$sql = "UPDATE projects SET name = ?, `desc` = ?, userId = ?, `start` = ?, `end` = ?, progress = ?, status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(["error" => "Error al preparar la consulta: " . $conn->error]);
    exit;
}

$stmt->bind_param("ssissisi", $name, $desc, $userId, $start, $end, $progress, $status, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "id" => $id, "status" => $status]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al actualizar proyecto: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>