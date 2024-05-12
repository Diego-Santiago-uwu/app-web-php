<?php

//Establecemos datos para la conexion
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


//Funcion que nos permite agregar productos al carrito
function addToCart($conn, $productId, $quantity) {

    // Primero, verifica si el producto ya está en el carrito
    $sql = "SELECT quantity FROM cart WHERE productid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // El producto ya está en el carrito, actualiza la cantidad
        $row = $result->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;
        $sql = "UPDATE cart SET quantity = ? WHERE productid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $newQuantity, $productId);
    } else {
        // El producto no está en el carrito, inserta una nueva entrada
        $sql = "INSERT INTO cart (productid, quantity) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $productId, $quantity);
    }
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error: " . $stmt->error);
        return false;
    }
}

//Manejar la respuesta del metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Buscamos por productId y Cantidad
    if (isset($_POST['productId']) && isset($_POST['quantity'])) {
        $productId = $_POST['productId'];
        $quantity = $_POST['quantity'];
        addToCart($conn, $productId, $quantity);

        // Devolver una respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito exitosamente']);
    } else {
        // Devolver un error en formato JSON
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud']);
    }
}

//Manejar la respuesta del metodo GET  que renderiza el html
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
$sql = "SELECT product.productid, product.imagen, product.nombreproduct, product.precio, cart.quantity FROM cart INNER JOIN product ON cart.productid = product.productid";    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Salida de cada fila
        while($row = $result->fetch_assoc()) {

            //Contenido html de cada producto
            echo '<div class="product-card">';
                echo '<button type="button" class="delete-button" data-id="' . $row['productid'] . '">X</button>';
                echo '<div class="product-img-container">';
                    echo '<img src="../img/' . $row['imagen'] . '" alt="' . $row['nombreproduct'] . '" class="product-img">';
                echo '</div>';
                echo '<div class="product-info">';
                    echo '<h2 class="product-name">' . $row['nombreproduct'] . '</h2>';
                    echo '<p class="product-price">Precio: $' . $row['precio'] . '</p>';
                    echo '<div class="product-quantity">';
                        echo '<button type="button" class="quantity-button" data-id="' . $row['productid'] . '" data-action="decrease">-</button>';
                        echo '<span>' . $row['quantity'] . '</span>';
                        echo '<button type="button" class="quantity-button" data-id="' . $row['productid'] . '" data-action="increase">+</button>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    } else {
        echo "El carrito está vacío";
    }
} else {
    // Devolver un error en formato JSON
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
