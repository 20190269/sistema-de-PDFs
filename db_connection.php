<?php
// db_connection.php

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pdf_system";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}