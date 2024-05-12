// Función para agregar un producto al carrito
function addToCart(productId, quantity) {
    console.log('addToCart llamado con productId:', productId, 'y quantity:', quantity);

    // Crear el objeto de datos a enviar
    const data = 'productId=' + productId + '&quantity=' + quantity;

    // Realizar la solicitud fetch
    fetch('../php/catalogo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: data,
    })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data);
            if (data.success) {
                console.log('Producto agregado al carrito exitosamente');
            } else {
                console.error('Error al agregar el producto al carrito: ' + data.message);
            }
        });
}

// Función para manejar el evento de clic en el botón "Agregar al carrito"
function handleAddToCartClick(event) {
    console.log('handleAddToCartClick llamado');
    const button = event.target;
    const productId = Number(button.getAttribute('data-id'));
    console.log('productId:', productId);
    addToCart(productId, 1);
}

// Función para agregar los manejadores de eventos a los botones "Agregar al carrito"
function addEventListeners() {
    console.log('addEventListeners llamado');
    const addToCartButtons = document.querySelectorAll('.product-button');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', handleAddToCartClick);
    });
}

// Agregar los manejadores de eventos cuando se carga la página
console.log('Agregando manejadores de eventos');
window.addEventListener('DOMContentLoaded', function() {
    fetch('../php/product.php')
        .then(response => response.text())
        .then(data => {
            document.querySelector('.product-container').innerHTML = data;
            addEventListeners();
        });
});

