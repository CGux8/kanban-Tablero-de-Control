<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "kanban_dashboard");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$name = $conn->real_escape_string($data['name'] ?? '');

if (empty($name)) {
    http_response_code(400);
    echo json_encode(["error" => "El nombre es requerido"]);
    exit;
}

$sql = "INSERT INTO users (name) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();

$response = ["id" => $conn->insert_id, "name" => $name];
echo json_encode($response);

$stmt->close();
$conn->close();
?>