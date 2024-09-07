<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFCS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Sistema para Firma de Contratos Sicanet</h2>

        <!-- Formulario para subir archivos -->
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Selecciona un archivo PDF:</label>
                <input type="file" name="file" class="form-control" id="file" required>
            </div>
            <button type="submit" id="submitBtn" class="btn btn-primary">Subir</button>
        </form>

        <div id="alertContainer" class="mt-3"></div> <!-- Contenedor para mostrar notificaciones -->

        <hr>

        <!-- Tabla para mostrar los archivos subidos -->
        <h4>Archivos Subidos</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nombre del archivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="filesList">
                <!-- Aquí se cargarán los archivos desde fetch_files.php -->
            </tbody>
        </table>
    </div>

    <!-- Modal para visualizar PDF -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Visualización del Contrato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewer" src="" frameborder="0" style="width: 100%; height: 500px;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cargar los archivos PDF desde la base de datos
        function loadFiles() {
            $.ajax({
                url: 'fetch_files.php',
                method: 'GET',
                success: function(data) {
                    $('#filesList').html(data);
                }
            });
        }

        // Visualizar archivo PDF en el modal
        function viewPDF(file) {
            $('#pdfViewer').attr('src', 'uploads/' + file);
            $('#pdfModal').modal('show');
        }

        // Eliminar archivo
        function eliminarArchivo(id, fileName) {
            console.log("Intentando eliminar archivo con ID: " + id + " y nombre: " + fileName);  // Agrega este console.log

            if (confirm("¿Estás seguro de que deseas eliminar este archivo?")) {
                $.ajax({
                    url: 'eliminar_archivo.php',
                    method: 'POST',
                    data: {
                        id: id,
                        file_name: fileName
                    },
                    success: function(response) {
                        alert(response);
                        loadFiles(); // Recargar la lista de archivos
                    },
                    error: function() {
                        alert('Error al eliminar el archivo.');
                    }
                });
            }
        }

        // Subir archivo mediante AJAX
        $(document).ready(function() {
            loadFiles(); // Cargar archivos al inicio

            $('#uploadForm').on('submit', function(e) {
                e.preventDefault(); // Prevenir el envío normal del formulario

                // Crear un objeto FormData para enviar el archivo
                var formData = new FormData(this);

                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Mostrar mensaje de éxito o error
                        $('#alertContainer').html(
                            '<div class="alert alert-success">' + response + '</div>'
                        );
                        
                        // Ocultar el mensaje después de 3 segundos
                        setTimeout(function() {
                            $('#alertContainer').fadeOut('slow', function() {
                                $(this).html('').show(); // Limpiar el contenido y volver a mostrar el contenedor
                            });
                        }, 3000); // 3000 milisegundos = 3 segundos

                        // Limpiar el campo de archivo después de subirlo
                        $('#file').val('');

                        loadFiles(); // Recargar la lista de archivos
                    },
                    error: function() {
                        $('#alertContainer').html(
                            '<div class="alert alert-danger">Hubo un error al subir el archivo.</div>'
                        );
                        
                        // Ocultar el mensaje después de 1 segundos
                        setTimeout(function() {
                            $('#alertContainer').fadeOut('slow', function() {
                                $(this).html('').show(); // Limpiar el contenido y volver a mostrar el contenedor
                            });
                        }, 1000); // 1000 milisegundos = 1 segundos

                        // Limpiar el campo de archivo en caso de error también
                        $('#file').val('');
                    }
                });
            });
        });
    </script>
</body>
</html>