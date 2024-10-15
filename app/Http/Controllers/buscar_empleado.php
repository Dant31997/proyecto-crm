<?php
header('Content-Type: application/json');

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "empleados";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    echo json_encode(['encontrado' => false]);
    exit();
}

// Obtener la cédula enviada por el formulario
$cedula = $_POST['Cedula'];

// Consulta en la base de datos
$sql = "SELECT Cedula, Nombre FROM bd_empleados WHERE Cedula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

// Comprobar si se encontró el usuario
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = $row['Nombre'];
    $cedula = $row['Cedula'];

    // Respuesta JSON
    echo json_encode(['encontrado' => true, 'Nombre' => $nombre, 'Cedula' => $cedula]);
} else {
    echo json_encode(['encontrado' => false]);
}

$stmt->close();
$conn->close();
?>
