<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "itsolvent";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['usuario'] ?? '';
    $password = $_POST['contrasena'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE nombre = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $hash_en_bd = $usuario['contrasena'];

        if (!preg_match('/^\$2y\$/', $hash_en_bd)) {
            // Contraseña sin hash aún
            if ($password === $hash_en_bd) {
                // Rehash y guardar
                $nuevo_hash = password_hash($password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE usuario SET contrasena = ? WHERE idUsuario = ?");
                $update->bind_param("si", $nuevo_hash, $usuario['idUsuario']);
                $update->execute();
                $update->close();

                $_SESSION['username'] = $usuario['nombre'];
                $_SESSION['nivel'] = $usuario['nivel'];
                redirigirPorNivel($usuario['nivel']);
            } else {
                redirigirConError($username);
            }
        } else {
            // Contraseña ya con hash
            if (password_verify($password, $hash_en_bd)) {
                $_SESSION['username'] = $usuario['nombre'];
                $_SESSION['nivel'] = $usuario['nivel'];
                redirigirPorNivel($usuario['nivel']);
            } else {
                redirigirConError($username);
            }
        }
    } else {
        redirigirConError($username);
    }

    $stmt->close();
}

$conn->close();

function redirigirPorNivel($nivel) {
    if ($nivel === 'admin') {
        header("Location: administrador.html");
    } else {
        header("Location: tecnicos.php");
    }
    exit;
}

function redirigirConError($usuario) {
    header("Location: login1.php?error=1&usuario=" . urlencode($usuario));
    exit;
}
?>


