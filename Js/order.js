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

                        // Obtén los datos del formulario
                        const nombres = document.getElementById('nombres').value;
                        const apellidos = document.getElementById('apellidos').value;
                        const email = document.getElementById('email').value;
                        const telefono = document.getElementById('telefono').value;
                        const calle = document.getElementById('calle').value;
                        const ciudad = document.getElementById('ciudad').value;
                        const estado = document.getElementById('estado').value;
                        const codigoPostal = document.getElementById('codigo-postal').value;

                        // Crea el contenido del archivo de texto
                        const contenido = `Nombres: ${nombres}\nApellidos: ${apellidos}\nEmail: ${email}\nTeléfono: ${telefono}\nCalle: ${calle}\nCiudad: ${ciudad}\nEstado: ${estado}\nCódigo Postal: ${codigoPostal}`;

                        // Crea un objeto Blob con el contenido del archivo
                        const blob = new Blob([contenido], {type: 'text/plain'});

                        // Crea una URL para el objeto Blob
                        const url = URL.createObjectURL(blob);

                        // Crea un enlace y haz clic en él para descargar el archivo
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = 'datos.txt';
                        link.click();

                        // Libera la URL del objeto Blob
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
