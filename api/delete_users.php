<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "kanban_dashboard");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "ID inválido"]);
    exit;
}

$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

if ($conn->affected_rows > 0) {
    echo json_encode(["message" => "Usuario eliminado"]);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Usuario no encontrado"]);
}

$stmt->close();
$conn->close();
?>