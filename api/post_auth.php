<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "kanban_dashboard");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$isAdminLoggedIn = isset($data['isAdminLoggedIn']) ? (bool)$data['isAdminLoggedIn'] : false;

$sql = "INSERT INTO auth (isAdminLoggedIn) VALUES (?) ON DUPLICATE KEY UPDATE isAdminLoggedIn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $isAdminLoggedIn, $isAdminLoggedIn); // Convertimos a entero (0 o 1)
$stmt->execute();

echo json_encode(["success" => true]);

$stmt->close();
$conn->close();
?>