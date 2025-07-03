<?php
// Habilitar el registro de errores
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/php_errors.log');
error_reporting(E_ALL);

header('Content-Type: application/json');

// Depuración inicial
file_put_contents(dirname(__FILE__) . '/debug.log', "Iniciando post_projects.php\n", FILE_APPEND);

$conn = new mysqli("localhost", "root", "", "kanban_dashboard");

if ($conn->connect_error) {
    http_response_code(500);
    file_put_contents(dirname(__FILE__) . '/debug.log', "Error de conexión: " . $conn->connect_error . "\n", FILE_APPEND);
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

file_put_contents(dirname(__FILE__) . '/debug.log', "Conexión exitosa a la base de datos\n", FILE_APPEND);

$data = json_decode(file_get_contents("php://input"), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    file_put_contents(dirname(__FILE__) . '/debug.log', "Error en JSON: " . json_last_error_msg() . "\n", FILE_APPEND);
    echo json_encode(["error" => "Datos JSON inválidos: " . json_last_error_msg()]);
    exit;
}

file_put_contents(dirname(__FILE__) . '/debug.log', "Datos recibidos: " . print_r($data, true) . "\n", FILE_APPEND);

$name = $data['name'] ?? '';
$desc = $data['desc'] ?? '';
$userId = isset($data['userId']) ? (int)$data['userId'] : null;
$start = $data['start'] ?? '';
$end = $data['end'] ?? '';
$progress = isset($data['progress']) ? (int)$data['progress'] : 0;
$status = $data['status'] ?? 'todo';

if (empty($name) || !$userId || empty($start) || empty($end)) {
    http_response_code(400);
    file_put_contents(dirname(__FILE__) . '/debug.log', "Faltan campos requeridos\n", FILE_APPEND);
    echo json_encode(["error" => "Faltan campos requeridos"]);
    exit;
}

file_put_contents(dirname(__FILE__) . '/debug.log', "Datos validados: name=$name, desc=$desc, userId=$userId, start=$start, end=$end, progress=$progress, status=$status\n", FILE_APPEND);

// Validar el status
$validStatuses = ['todo', 'in-progress', 'completed'];
if (!in_array($status, $validStatuses)) {
    $status = 'todo'; // Valor por defecto
}

$sql = "INSERT INTO projects (name, `desc`, userId, `start`, `end`, progress, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    http_response_code(500);
    file_put_contents(dirname(__FILE__) . '/debug.log', "Error al preparar la consulta: " . $conn->error . "\n", FILE_APPEND);
    echo json_encode(["error" => "Error al preparar la consulta: " . $conn->error]);
    exit;
}

file_put_contents(dirname(__FILE__) . '/debug.log', "Consulta preparada: $sql\n", FILE_APPEND);

$stmt->bind_param("ssissis", $name, $desc, $userId, $start, $end, $progress, $status);

if ($stmt->execute()) {
    $newProjectId = $conn->insert_id;
    $newProject = [
        "id" => $newProjectId,
        "name" => $name,
        "desc" => $desc,
        "userId" => $userId,
        "start" => $start,
        "end" => $end,
        "progress" => $progress,
        "status" => $status
    ];
    file_put_contents(dirname(__FILE__) . '/debug.log', "Proyecto creado con ID: $newProjectId\n", FILE_APPEND);
    echo json_encode($newProject);
} else {
    http_response_code(500);
    file_put_contents(dirname(__FILE__) . '/debug.log', "Error al crear proyecto: " . $stmt->error . "\n", FILE_APPEND);
    echo json_encode(["error" => "Error al crear proyecto: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>