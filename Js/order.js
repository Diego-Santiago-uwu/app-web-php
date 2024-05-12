window.addEventListener('DOMContentLoaded', function() {
    fetch('../php/order.php')
        .then(response => response.text())
        .then(data => {
            document.querySelector('.order-container').innerHTML = data;

            const form = document.getElementById('order-form');

            if (form) {
                const submitButton = form.querySelector('input[type="submit"]');

                if (submitButton) {
                    submitButton.addEventListener('click', function(event) {
                        event.preventDefault();
                        console.log('El botón de envío ha sido accionado');

                        const nombres = document.getElementById('nombres').value;
                        const apellidos = document.getElementById('apellidos').value;
                        const email = document.getElementById('email').value;
                        const telefono = document.getElementById('telefono').value;
                        const calle = document.getElementById('calle').value;
                        const ciudad = document.getElementById('ciudad').value;
                        const estado = document.getElementById('estado').value;
                        const codigoPostal = document.getElementById('codigo-postal').value;

                        const contenido = `Nombres: ${nombres}\nApellidos: ${apellidos}\nEmail: ${email}\nTeléfono: ${telefono}\nCalle: ${calle}\nCiudad: ${ciudad}\nEstado: ${estado}\nCódigo Postal: ${codigoPostal}`;

                        const blob = new Blob([contenido], {type: 'text/plain'});

                        const url = URL.createObjectURL(blob);

                        // Crear un objeto FormData para enviar los datos del formulario
                        const formData = new FormData(form);

                        // Hacer una solicitud fetch al servidor
                        fetch('../php/order.php', {
                            method: 'POST',
                            body: formData,
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Orden creada exitosamente. Los datos del carrito se eliminaron correctamente.');
                                } else {
                                    console.error('Error al crear la orden y/o eliminar los datos del carrito:', data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error al hacer la solicitud fetch:', error);
                            });

                        const link = document.createElement('a');
                        link.href = url;
                        link.download = 'datos.txt';
                        link.click();

                        URL.revokeObjectURL(url);
                    });
                } else {
                    console.log('No se encontró el botón de envío');
                }
            } else {
                console.log('No se encontró el formulario');
            }
        });
});
