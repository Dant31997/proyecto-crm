<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Descripción de tu aplicación BancApp">
    <script src="https://kit.fontawesome.com/03ca14290a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/proyecto-crm/resources/css/supervisor.css">
    <title>BancApp</title>
</head>

<body>
    <header class="contenedor-header">
        <div class="logo-imagen">
            <img src="/proyecto-crm/resources/img/pixelcut-export.png" width="50" height="50" alt="Logo de BancApp">
        </div>
        <div class="dropdown">
            <button><i class="fa-solid fa-user" aria-label="Usuario"></i></button>
            <div class="dropdown-content">
            <a href="https://blog.hubspot.com/">Blog</a>
            <a href="https://academy.hubspot.com/">Academy</a>
            <a href="/proyecto-crm/resources/views/index.blade.php">Cerrar sesion</a>
            </div>
        </div>
        
    </header>

    <main class="main-contenedor">
        <!-- Contenido de la sección izquierda -->
        <section class="izquierda">
            <div class="tabla-contenedor">
                <?php
                    echo "<div class='tabla2'>";
                    // Conexión a la base de datos
                    $conexion = new mysqli("localhost", "root", "", "empleados");

                        // Verifica la conexión
                        if ($conexion->connect_error) {
                            die("Error en la conexión: " . $conexion->connect_error);
                        }
                        
                        $registrosPorPagina = 5;
                        $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
                        
                        // Consulta SQL con LIMIT para obtener registros de la página actual
                        $offset = ($paginaActual - 1) * $registrosPorPagina;
                        $sql = "SELECT * FROM tareas_empleados LIMIT $offset, $registrosPorPagina";
                        $resultado = $conexion->query($sql);
                        
                        // Consulta SQL para obtener el número total de registros
                        $totalRegistros = $conexion->query("SELECT COUNT(*) as total FROM tareas_empleados")->fetch_assoc()['total'];
                        
                        // Calcular el número total de páginas
                        $numTotalPaginas = ceil($totalRegistros / $registrosPorPagina);

                    if ($resultado->num_rows > 0) {
                        echo "<table class='tabla1'>";
                        echo "<tr class= 'encabezado'>
                        <th style=width:200px;>Nombre</th>
                        <th style=width:200px; > Tarea </th>
                        <th style=width:80px;> Estado </th>
                        <th style=width:150px;> Dia de Inicio</th>
                        <th style=width:150px;> Dia de entrega </th>
                        <th style=width:100px>Acciones</th>
                        </tr>";
                        

                        while ($fila = $resultado->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $fila['Nombre'] . "</td>";
                            echo "<td>" . $fila['Tarea'] . "</td>";
                            echo "<td>" . $fila['Estado'] . "</td>";
                            echo "<td>" . $fila['Dia_inicio'] . "</td>";
                            echo "<td>" . $fila['Dia_fin'] . "</td>";
                            echo "<td><a href='editar_espacio.php?ID_Tarea=" . $fila['ID_Tarea'] . "&Nombre=" . $fila['Nombre'] ."&Tarea=" . $fila['Tarea']. "&Estado=" . $fila['Estado'] . "&Dia_inicio=" . $fila['Dia_inicio']. "&Dia_fin=" . $fila['Dia_fin'] ."'><html><i class='fa-solid fa-pen-to-square'></i></i></html></a><h>--</h><a href='eliminar_espacio.php?ID_Tarea=" . $fila['ID_Tarea'] . "'><html><i class='fa-solid fa-trash'></i></html></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        echo "</div>";
                    }

                    $conexion->close();

                ?>
            </div>
            <div class="pagination">
                    <?php
                    for ($i = 1; $i <= $numTotalPaginas; $i++) {
                        $claseActiva = ($i == $paginaActual) ? "active" : "";
                        echo "<a class='$claseActiva' href='espacios.php?pagina=$i'>$i</a>";
                    }
                    ?>
            </div>
             
        </section>
        <!-- Contenido de la sección derecha -->
        <section class="derecha">
            
            <div class="crear">
                <form id="Crear-form" method="post">
                    <div class="container">
                        <div class="congrup">
                            <label for="Cedula" class="form_label"><i class="fa-brands fa-searchengin"></i>Cedula del empleado</label>
                            <input type="text" id="Cedula" class="form_input" placeholder=" " name="Cedula">
                            <span class="form_line"></span>
                            <button type="button" onclick="buscarEmpleado(); resetearInput();">Buscar</button>
                        </div>
                        
                    </div>
                </form>
            </div>
        </section>
        <!-- Pruebas -->
        <script>
        function buscarEmpleado() {
            // Obtener el valor de la cédula del formulario
            const Cedula = document.getElementById('Cedula').value;

            // Crear una solicitud AJAX para enviar al servidor
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/proyecto-crm/app/Http/Controllers/buscar_empleado.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            // Cuando el servidor responde
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Parsear la respuesta JSON del servidor
                    const respuesta = JSON.parse(xhr.responseText);
                    
                    // Verificar si el usuario fue encontrado
                    if (respuesta.encontrado) {
                        // Generar una combinación aleatoria de nombre y cédula
                        const Nombre = respuesta.Nombre;
                        const Cedula = respuesta.Cedula;
                        
                        const combinacionAleatoria =  Nombre.substring(0, 2).split('').sort(() => 0.5 - Math.random()).join('') + 
                                                        Cedula.split('').sort(() => 0.5 - Math.random()).join('');
                        
                        // Mostrar la combinación en un alert
                        alert("El usuario asignado es: "+ Cedula +  
                        "\nLa contraseña temporal asignada es: " + combinacionAleatoria);
                    } else {
                        alert("Empleado no encontrado.");
                    }
                } else {
                    alert("Error al conectarse con el servidor.");
                }
            };
            
            // Enviar los datos al servidor
            xhr.send("Cedula=" + encodeURIComponent(Cedula));
        }
    </script>
  <script>
  function resetearInput() {
    document.getElementById('Cedula').value = '';
  }
</script>
    </main>
</body>
</html>