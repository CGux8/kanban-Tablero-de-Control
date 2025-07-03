<?php
     header('Content-Type: application/json');
     $conn = new mysqli("localhost", "root", "", "kanban_dashboard");

     if ($conn->connect_error) {
         http_response_code(500);
         echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
         exit;
     }

     $sql = "SELECT * FROM projects";
     $result = $conn->query($sql);

     $projects = [];
     if ($result) {
         while ($row = $result->fetch_assoc()) {
             $projects[] = $row;
         }
     }

     echo json_encode($projects);

     $conn->close();
     ?>