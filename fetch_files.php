<?php
include 'db_connection.php';

// Obtener archivos
$result = $conn->query("SELECT * FROM pdf_files ORDER BY id ASC");

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['file_name']}</td>
                    <td>
                        <a href='javascript:void(0);' onclick=\"viewPDF('{$row['file_name']}')\" class='btn btn-info'>
                            <i class='fas fa-eye'></i>
                        </a>
                        <a href='uploads/{$row['file_name']}' download class='btn btn-success'>
                            <i class='fas fa-download'></i>
                        </a>
                        <button onclick=\"eliminarArchivo({$row['id']}, '{$row['file_name']}')\" class='btn btn-danger'>
                            <i class='fas fa-trash-alt'></i>
                        </button>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No se encontraron archivos.</td></tr>";
    }
} else {
    echo "Error al obtener los archivos: " . $conn->error;
}

$conn->close();
?>