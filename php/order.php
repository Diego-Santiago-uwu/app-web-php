<?php

$servername = "localhost";
$username = "root";
$password = "root1234";
$dbname = "eq12velas";

// Recoger los datos del formulario
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
// Recoge los demás campos aquí

// Crear la consulta SQL para insertar la nueva orden
$sql = "INSERT INTO orders (nombres, apellidos, ...) VALUES (?, ?, ...)";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Vincular los parámetros
$stmt->bind_param("ss...", $nombres, $apellidos, ...);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Devolver una respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Orden creada exitosamente']);
} else {
    // Devolver un error en formato JSON
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear la orden']);
}
?>
