window.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('order-form');

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            console.log('El formulario ha sido enviado');

            const formData = new FormData(form);

            fetch('../php/order.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => {
                    if (response.ok) {
                        console.log('Orden creada exitosamente. Los datos del carrito se eliminaron correctamente.');
                        window.location.href = '../';
                    } else {
                        console.error('Error al crear la orden y/o eliminar los datos del carrito:', response.statusText);
                    }
                })
                .catch(error => {
                    console.error('Error al hacer la solicitud fetch:', error);
                });
        });
    } else {
        console.log('No se encontró el formulario');
    }
});
