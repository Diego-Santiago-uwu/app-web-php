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

$sql = "SELECT productid, imagen, nombreproduct, descripcion, precio FROM product";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Salida de cada fila
  while($row = $result->fetch_assoc()) {
      echo '<div class="product-card">';
          echo '<h2 class="product-name">' . $row['nombreproduct'] . '</h2>';
          echo '<img src="../img/' . $row['imagen'] . '" alt="' . $row['nombreproduct'] . '" class="product-img">';
          echo '<p class="product-desc">' . $row['descripcion'] . '</p>';
          echo '<p class="product-price">Precio: $' . $row['precio'] . '</p>';
          echo '<button type="button" class="product-button" data-id="' . $row['productid'] . '">Agregar al carrito</button>';
      echo '</div>';
  }
} else {
  echo "0 results";
}
$conn->close();
?>
