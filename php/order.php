<?php

$servername = "localhost";
$username = "root";
$password = "root1234";
$dbname = "eq12velas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Recoger los datos del formulario
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$calle = $_POST['calle'];
$ciudad = $_POST['ciudad'];
$estado = $_POST['estado'];
$codigo_postal = $_POST['codigo-postal'];
// Recoge los demás campos aquí

// Crear la consulta SQL para insertar la nueva orden
$sql = "INSERT INTO orders (nombres, apellidos, email, telefono, calle, ciudad, estado, `codigo-postal`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Vincular los parámetros
$stmt->bind_param("ssssssss", $nombres, $apellidos, $email, $telefono, $calle, $ciudad, $estado, $codigo_postal);

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
