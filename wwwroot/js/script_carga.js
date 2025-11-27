window.addEventListener('load', () => {
    // Seleccionamos los elementos HTML
    const loadingScreen = document.querySelector('.loading-screen');
    const mainContent = document.getElementById('main-content');

    // Retardo opcional para ver la animación, puedes eliminarlo si no lo necesitas
    setTimeout(() => {
        // Añade la clase para que la pantalla de carga se desvanezca
        loadingScreen.classList.add('fade-out');

        // Escucha cuándo termina la transición de opacidad
        loadingScreen.addEventListener('transitionend', () => {
            // Elimina la pantalla de carga del DOM después de que se desvanezca
            loadingScreen.style.display = 'none';
        });

        // Muestra el contenido principal de la página
        mainContent.classList.remove('hidden');

    }, 1000); // 1000ms = 1 segundo de retardo
});