<?php
include 'db_connection.php';

if (isset($_POST['id']) && isset($_POST['file_name'])) {
    $id = $_POST['id'];
    $fileName = $_POST['file_name'];

    error_log("Recibido ID: $id, Nombre del archivo: $fileName");  // Esto se imprimirá en el log de errores de PHP

    // Eliminar el archivo de la base de datos
    $delete = $conn->prepare("DELETE FROM pdf_files WHERE id = ?");
    $delete->bind_param("i", $id);

    if ($delete->execute()) {
        // Eliminar el archivo del servidor
        $filePath = 'uploads/' . $fileName;
        if (!file_exists($filePath)) {
            echo "El archivo no existe.";
            exit;
        }
        if (unlink($filePath)) {
            echo "Archivo eliminado con éxito.";
        } else {
            echo "Error al eliminar el archivo del servidor.";
        }
    } else {
        echo "Error al eliminar el archivo de la base de datos: " . $conn->error;
    }
    
    $delete->close();
}

$conn->close();
?>