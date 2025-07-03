<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "kanban_dashboard");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT isAdminLoggedIn FROM auth LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["isAdminLoggedIn" => (bool)$row['isAdminLoggedIn']]);
} else {
    echo json_encode(["isAdminLoggedIn" => false]);
}

$conn->close();
?>