<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];

        // Verificar si ya existe un archivo con el mismo nombre en la base de datos
        $stmt = $conn->prepare("SELECT * FROM pdf_files WHERE file_name = ?");
        $stmt->bind_param("s", $fileName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Si ya existe un archivo con el mismo nombre
            echo "Ya existe un archivo con el mismo nombre. Por favor, elige otro archivo.";
        } else {
            // Mover el archivo a la carpeta de uploads
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($fileName);

            if (move_uploaded_file($fileTmpName, $uploadFile)) {
                // Insertar en la base de datos
                $stmt = $conn->prepare("INSERT INTO pdf_files (file_name, uploaded_on) VALUES (?, NOW())");
                $stmt->bind_param("s", $fileName);

                if ($stmt->execute()) {
                    echo "El archivo se ha subido correctamente.";
                } else {
                    echo "Error al guardar el archivo en la base de datos.";
                }
            } else {
                echo "Error al mover el archivo a la carpeta de uploads.";
            }
        }
        
        $stmt->close();
    } else {
        echo "No se ha subido ningún archivo o hubo un error al subir el archivo.";
    }
}

$conn->close();
?>