<?php
$servername = "localhost";
$username = "root";
$password = "root1234";
$dbname = "eq12velas";

// Inicializar variables
$rows = [];
$totalPrice = 0;

// Crear conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para obtener los productos del carrito
$sql = "SELECT product.productid, product.imagen, product.nombreproduct, product.precio, cart.quantity FROM cart INNER JOIN product ON cart.productid = product.productid";
$result = $conn->query($sql);

// Verificar el método de la solicitud HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar si se enviaron los parámetros necesarios
    if (isset($_POST['productId']) && isset($_POST['action'])) {
        $productId = $_POST['productId'];
        $action = $_POST['action'];

        // Acciones para el método POST
        if ($action === 'delete') {
            $sql = "DELETE FROM cart WHERE productid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente']);

        // Incrementar la cantidad de un producto en el carrito
        } elseif ($action === 'increase') {
            $sql = "UPDATE cart SET quantity = quantity + 1 WHERE productid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Cantidad incrementada exitosamente']);

        // Decrementar la cantidad de un producto en el carrito
        } elseif ($action === 'decrease') {
            $sql = "UPDATE cart SET quantity = quantity - 1 WHERE productid = ? AND quantity > 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();


            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Cantidad disminuida exitosamente']);

             // Si la cantidad es 1, eliminar el producto del carrito
            } else {
                $sql = "DELETE FROM cart WHERE productid = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $productId);
                $stmt->execute();
                echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente']);
            }
        }
    }

//En caso de que cargue por primera vez la pagina para poder observar lo que es el formualrio
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Si hay productos en el carrito, generar el HTML para cada producto
    if ($result->num_rows > 0) {

        // Generar el HTML del producto
        while($row = $result->fetch_assoc()) {
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
                            echo '<span class="cantidad" style="
                                                             font-size: 20px;
                                                             padding-left: 5px;
                                                             padding-right: 5px;
                                                             padding-bottom: 5px;
                                                         ">' . $row['quantity'] . '</span>';
                            echo '<button type="button" class="quantity-button" data-id="' . $row['productid'] . '" data-action="increase">+</button>';

                        // Calcular el precio total
                        echo '<p class="pieza-price" style="
                                                            font-size: 30px;
                                                            padding-top: 1px;
                                                            background-color: #f1f315;
                                                            ">
                            Total: $' . $row['precio'] * $row['quantity'] . '</p>';

                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }

    // Realizar la consulta a la base de datos
    $result = $conn->query($sql);

     if ($result->num_rows > 0) {
         // Almacenar los resultados en un array y generar el HTML de cada producto
         while($row = $result->fetch_assoc()) {
             $rows[] = $row;
             $totalPrice += $row['precio'] * $row['quantity'];
             // ...
             // Generar el HTML del producto
             // ...
         }
     }

    // Mostrar el precio total y el botón de comprar

   echo '<p class="total-price">Precio total: $' . $totalPrice . '</p>';
   echo '<div class="comprar-button">';

   echo '<button type="button" class="comprar-button" style="
                                                            align-items: center !important;
                                                            padding-bottom: 10px !important;
                                                            " onclick="window.location.href=\'http://localhost/eq12/html/orders.html\'">
                                                            ¡ Comprar ahora ! </button>';
   echo '</div>';

    } else {
        echo "El carrito está vacío";
    }
} else {
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
