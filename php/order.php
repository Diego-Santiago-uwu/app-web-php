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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo-postal'];
    $cartid = $_POST['cartid'];

    // Calcular el total del pedido
    $sql = "SELECT SUM(product.precio * cart.quantity) AS total FROM cart INNER JOIN product ON cart.productid = product.productid WHERE cart.cartid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cartid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total = $row['total'];

    // Iniciar transacción
    $conn->begin_transaction();

    // Crear la consulta SQL para insertar la nueva orden
    $sql = "INSERT INTO orders (cartid, total, nombres, apellidos, email, telefono, calle, ciudad, estado, `codigo-postal`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("idssssssss", $cartid, $total, $nombres, $apellidos, $email, $telefono, $calle, $ciudad, $estado, $codigo_postal);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Después de insertar la orden en la tabla orders
        // Eliminar todos los datos del carrito
        $sql = "DELETE FROM cart";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            // Los datos del carrito se eliminaron correctamente
            // Confirmar transacción
            $conn->commit();
            // Devolver una respuesta en formato JSON
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Orden creada exitosamente. Todos los datos del carrito se eliminaron correctamente.']);
        } else {
            // Hubo un error al eliminar los datos del carrito
            // Revertir transacción
            $conn->rollback();
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar los datos del carrito']);
        }
    } else {
        // Hubo un error al crear la orden
        // Revertir transacción
        $conn->rollback();
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al crear la orden']);
    }
} else {
    // Mostrar el formulario si no se ha enviado nada
    echo '<form class = "formulario" id="order-form" method="POST">
        <label for="nombres">Nombres:</label>
        <input type="text" id="nombres" name="nombres" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" required>

        <label for="calle">Calle:</label>
        <input type="text" id="calle" name="calle" required>

        <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad" required>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required>

        <label for="codigo-postal">Código Postal:</label>
        <input type="text" id="codigo-postal" name="codigo-postal" required>

        <input style = "padding-top: 10px !important
                        padding-top: 10px;
                        padding-bottom: 10px;
                        margin-top: 10px;
                        "
                        type="submit" value="Realizar Pedido">

    </form>';
}
?>
