window.addEventListener('DOMContentLoaded', (event) => {
    const orderForm = document.getElementById('order-form');

    if (orderForm) {
        orderForm.addEventListener('submit', function(event) {
            event.preventDefault();

            // Aquí puedes recoger los datos del formulario y enviarlos al servidor
            const formData = new FormData(orderForm);
            fetch('../php/order.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Orden creada exitosamente');
                    } else {
                        console.error('Error al crear la orden:', data.message);
                    }
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation: ', error);
                });
        });
    } else {
        console.error('No se encontró el formulario de pedido');
    }
});
