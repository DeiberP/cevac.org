document.addEventListener('DOMContentLoaded', function() {
    // Selecciona todos los formularios de eliminación
    const deleteForms = document.querySelectorAll('form[action*="eliminar"]');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Evita el envío tradicional del formulario
            
            // Muestra la alerta de confirmación
            if (confirm('¿Estás seguro de que deseas eliminar a este postulante?')) {
                const formData = new FormData(form);
                
                // Envía la solicitud mediante AJAX
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Elimina la fila de la tabla si la eliminación fue exitosa
                        const row = form.closest('tr');
                        row.remove();
                    } else {
                        alert('Error al eliminar: ' + (data.error || 'Error desconocido'));
                    }
                })
                .catch(error => {
                    alert('Error en la solicitud: ' + error.message);
                });
            }
        });
    });
});
