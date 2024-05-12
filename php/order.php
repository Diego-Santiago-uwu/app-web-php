<?php
$servername = "localhost";
$username = "root";
$password = "root1234";
$dbname = "eq12velas";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   // Collect form data
   $nombres = $_POST['nombres'];
   $apellidos = $_POST['apellidos'];
   $email = $_POST['email'];
   $telefono = $_POST['telefono'];
   $calle = $_POST['calle'];
   $ciudad = $_POST['ciudad'];
   $estado = $_POST['estado'];
   $codigo_postal = $_POST['codigo-postal'];
   $cartid = $_POST['cartid'];

   // Calculate the total of the order
   $sql = "SELECT SUM(product.precio * cart.quantity) AS total FROM cart INNER JOIN product ON cart.productid = product.productid";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
       $row = $result->fetch_assoc();
       $total = $row['total'];
   }

   // Get product details
   $sql = "SELECT product.productid, product.nombreproduct, product.precio, cart.quantity FROM product INNER JOIN cart ON product.productid = cart.productid";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {


       $contenido = "\nProductos:\n";

       while ($row = $result->fetch_assoc()) {
           $contenido .= "Producto: " . $row['nombreproduct'] . ", Cantidad: " . $row['quantity'] . "\n";
       }

       $contenido .= "\nTotal a Pagar: $total\n";

        $contenido =
            "Nombres: $nombres\n
            Apellidos: $apellidos\n
            Email: $email\n
            Teléfono: $telefono\n
            Calle: $calle\n
            Ciudad: $ciudad\n
            Estado: $estado\n
            Código Postal: $codigo_postal\n" . $contenido;

   }

    // After creating and closing the file
    $archivo = fopen("../datos.txt", "w");
    fwrite($archivo, $contenido);
    fclose($archivo);

    // Set headers for download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($archivo).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($archivo));

    // Clean output buffer
    ob_clean();
    flush();

    // Send file to client
    readfile($archivo);

    // Delete file from server after sending
    unlink($archivo);

    // Start transaction
    $conn->begin_transaction();

    // Create SQL query to insert new order
    $sql = "INSERT INTO orders (cartid, total, nombres, apellidos, email, telefono, calle, ciudad, estado, `codigo-postal`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare query
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("idssssssss", $cartid, $total, $nombres, $apellidos, $email, $telefono, $calle, $ciudad, $estado, $codigo_postal);

    // Execute query
    if ($stmt->execute()) {
        // After inserting order into orders table
        // Delete all cart data
        $sql = "DELETE FROM cart";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            // Cart data deleted successfully
            // Commit transaction
            $conn->commit();
            // Return response in JSON format
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Order created successfully. All cart data deleted successfully.']);
        } else {
            // Error deleting cart data
            // Rollback transaction
            $conn->rollback();
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error deleting cart data']);
        }
    } else {
        // Error creating order
        // Rollback transaction
        $conn->rollback();
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error creating order']);
    }
} else {
    // Show form if nothing has been submitted
    echo '<form class="formulario" id="order-form" method="POST">
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
