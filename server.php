<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expo_tecnica";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Contar visitas
if (!isset($_SESSION['visited'])) {
    $_SESSION['visited'] = true;
    $sql = "UPDATE visit_count SET count = count + 1 WHERE id = 1";
    $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'upload_project') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $imagen = $_FILES['imagen']['name'];
        $target = "uploads/" . basename($imagen);

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target)) {
            $sql = "INSERT INTO proyectos (nombre, descripcion, imagen) VALUES ('$nombre', '$descripcion', '$imagen')";
            if ($conn->query($sql) === TRUE) {
                echo "Proyecto subido con éxito.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } elseif ($_POST['action'] === 'vote') {
        $project_id = $_POST['project_id'];
        $sql = "UPDATE proyectos SET votos = votos + 1 WHERE id = $project_id";
        if ($conn->query($sql) === TRUE) {
            echo "Voto registrado con éxito.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === 'get_visit_count') {
        $sql = "SELECT count FROM visit_count WHERE id = 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo $row['count'];
    } elseif ($_GET['action'] === 'get_top_projects') {
        $sql = "SELECT * FROM proyectos ORDER BY votos DESC LIMIT 3";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<div class='proyecto'>";
            echo "<h3>" . $row['nombre'] . "</h3>";
            echo "<p>Votos: " . $row['votos'] . "</p>";
            echo "<img src='uploads/" . $row['imagen'] . "' alt='Imagen del proyecto'>";
            echo "</div>";
        }
    } elseif ($_GET['action'] === 'get_votes') {
        $sql = "SELECT * FROM proyectos";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<div class='proyecto'>";
            echo "<h3>" . $row['nombre'] . "</h3>";
            echo "<p>" . $row['descripcion'] . "</p>";
            echo "<img src='uploads/" . $row['imagen'] . "' alt='Imagen del proyecto'>";
            echo "<button onclick='vote(" . $row['id'] . ")'>Votar</button>";
            echo "</div>";
        }
    }
}

$conn->close();
?>
