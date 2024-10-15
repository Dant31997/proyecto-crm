<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "empleados";

// Crear conexión
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los valores del formulario
$usuario = $_POST['Usuario'];
$contrasena = $_POST['Contraseña'];

// Consulta SQL para verificar el usuario y la contraseña
$sql = "SELECT * FROM usuarios WHERE Usuario = ? AND Contraseña = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario, $contrasena);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el usuario existe
if ($result->num_rows > 0) {
    // Obtener la fila del resultado
    $row = $result->fetch_assoc();
    $rol = $row['Rol']; // Obtener el rol del usuario

    // Redirigir a la página correspondiente según el rol
    if ($rol == 'Administrador') {
        header("Location: admin-dashboard.html");
    } elseif ($rol == 'Supervisor'){
        header("Location: /proyecto-crm/resources/views/supervisor.blade.php");
    } else {
        header("Location: user-dashboard.html");
    }
    exit();
} else {
    // Si el usuario no es válido, muestra un mensaje de error
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href = 'login.html';</script>";
}

// Cerrar la conexión
$conn->close();
?>

