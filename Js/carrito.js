// Función para manejar el evento de clic en el botón de eliminar
function handleDeleteButtonClick(event) {
    const button = event.target;
    const productId = Number(button.getAttribute('data-id'));

    const data = new URLSearchParams();
    data.append('productId', productId);
    data.append('action', 'delete');

    fetch('../php/carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: data,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                console.error('Error al eliminar el producto del carrito: ' + data.message);
            }
        });
}

// Función para manejar el evento de clic en el botón de incremento de cantidad
function handleIncreaseQuantityClick(event) {
    const button = event.target;
    const productId = Number(button.getAttribute('data-id'));

    const data = new URLSearchParams();
    data.append('productId', productId);
    data.append('action', 'increase');

    fetch('../php/carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: data,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                console.error('Error al incrementar la cantidad del producto: ' + data.message);
            }
        });
}

// Función para manejar el evento de clic en el botón de disminución de cantidad
function handleDecreaseQuantityClick(event) {
    const button = event.target;
    const productId = Number(button.getAttribute('data-id'));

    const data = new URLSearchParams();
    data.append('productId', productId);
    data.append('action', 'decrease');

    fetch('../php/carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: data,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                console.error('Error al disminuir la cantidad del producto: ' + data.message);
            }
        });
}

// Función para agregar los manejadores de eventos a los botones
function addEventListeners() {
    const deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', handleDeleteButtonClick);
    });

    const increaseQuantityButtons = document.querySelectorAll('.quantity-button[data-action="increase"]');
    increaseQuantityButtons.forEach(button => {
        button.addEventListener('click', handleIncreaseQuantityClick);
    });

    const decreaseQuantityButtons = document.querySelectorAll('.quantity-button[data-action="decrease"]');
    decreaseQuantityButtons.forEach(button => {
        button.addEventListener('click', handleDecreaseQuantityClick);
    });
}

// Agregar los manejadores de eventos cuando se carga la página
window.addEventListener('DOMContentLoaded', function() {
    fetch('../php/carrito.php')
        .then(response => response.text())
        .then(data => {
            document.querySelector('.product-container').innerHTML = data;
            addEventListeners();
        });
});

// Agregar los manejadores de eventos cuando se carga la página
window.addEventListener('DOMContentLoaded', function() {
    fetch('../php/carrito.php')
        .then(response => response.text())
        .then(data => {
            document.querySelector('.product-container').innerHTML = data;
            addEventListeners();

            // Agregar el manejador de eventos al botón de comprar
            const comprarButton = document.querySelector('.comprar-button');
            if (comprarButton) {
                comprarButton.addEventListener('click', function() {
                    // Redirigir a orders.html cuando se haga clic en el botón de comprar
                    window.location.href = 'http://localhost/eq12/html/orders.html';
                });
            } else {
                console.error('No se encontró el botón de comprar');
            }
        });
});

function handleComprarButtonClick() {
    fetch('../php/order.php', {
        method: 'POST',
        // Agrega aquí los datos que necesitas enviar
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            try {
                return JSON.parse(data);
            } catch (err) {
                console.error('Error al parsear la respuesta como JSON:', err);
                console.log('Respuesta del servidor:', data);
            }
        })
        .then(json => {
            if (json && json.success) {
                console.log('Orden creada exitosamente');
            } else {
                console.error('Error al crear la orden:', json.message);
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation: ', error);
        });
}
