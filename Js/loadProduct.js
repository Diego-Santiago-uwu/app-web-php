fetch('../html/producto.html')
    .then(response => response.text())
    .then(data => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const productCard = doc.querySelector('.product-card');

        // Verifica si estás en la página de carrito
        if (window.location.pathname.indexOf('carrito.html') !== -1) {
            // Agrega la clase 'cart' al producto
            productCard.classList.add('cart');

            // Elimina los elementos que no necesitas
            const orderButton = productCard.querySelector('button');
            const productDescription = productCard.querySelector('p');
            orderButton.remove();
            productDescription.remove();

            // Agrega el elemento de cantidad
            const quantityElement = document.createElement('p');
            quantityElement.textContent = 'Cantidad: 1';
            productCard.appendChild(quantityElement);
        }

        document.querySelector('.product-container').innerHTML = productCard.outerHTML;
    });
