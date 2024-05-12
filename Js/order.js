window.addEventListener('DOMContentLoaded', function() {
    fetch('../php/order.php')
        .then(response => response.text())
        .then(data => {
            document.querySelector('.order-container').innerHTML = data;

            // Obtén el formulario
            const form = document.getElementById('order-form');

            // Verifica si el formulario existe
            if (form) {
                // Obtén el botón de envío
                const submitButton = form.querySelector('input[type="submit"]');

                // Verifica si el botón de envío existe
                if (submitButton) {
                    // Agrega un controlador de eventos al botón de envío
                    submitButton.addEventListener('click', function(event) {
                        // Previene la acción por defecto del botón de envío
                        event.preventDefault();
                        console.log('El botón de envío ha sido accionado');
                    });
                } else {
                    console.log('No se encontró el botón de envío');
                }
            } else {
                console.log('No se encontró el formulario');
            }
        });
});
