<?php
session_start();

$nivel = $_SESSION['nivel'] ?? null;
$paginaRegreso = ($nivel === 'admin') ? 'administrador.html' : 'tecnicos.php';

// Si se hace POST (formulario enviado), guardar resumen y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valor'])) {
    $id = intval($_POST['valor']);

    $resumen = "Datos necesarios:\n";
    foreach ($_POST as $key => $valor) {
        if (str_starts_with($key, 'campo')) {
            $resumen .= '- ' . trim($valor) . "\n";
        }
    }

    $resumen .= "\nPruebas Realizadas:\n";
    foreach ($_POST as $key => $valor) {
        if (str_starts_with($key, 'check')) {
            $resumen .= '- ' . trim($valor) . "\n";
        }
    }

    $_SESSION['resumen'] = $resumen;

    header("Location: tapu.php?id=$id");
    exit;
}

$id = $_GET['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Protocolo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <img src="logo.webp" alt="Logo ITSolvent" class="logo-img">
        <h2>Visualización de Protocolo</h2>
    </header>

    <main class="result-container">
<?php
if ($id !== null) {
    $host = "localhost";
    $usuario = "root";
    $contrasena = "";
    $base_datos = "itsolvent";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $contrasena);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT solucion, nivdelDeAcceso FROM protocolo WHERE idProtocolo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            $texto = $fila['solucion'];
            $nivel_protocolo = $fila['nivdelDeAcceso'];

            $niveles = ['n0' => 0, 'n1' => 1, 'n2' => 2, 'admin' => 3];

            if (!isset($niveles[$nivel]) || !isset($niveles[$nivel_protocolo]) || $niveles[$nivel] < $niveles[$nivel_protocolo]) {
                echo "<div class='error'>⚠️ No tienes permiso para ver este protocolo.</div>";
            } else {
                $partes = explode("( - )", $texto);
                $totalPartes = count($partes);

                echo "<h2>Resultado</h2>";
                echo "<form method='POST' class='styled-form'>";
                echo "<input type='hidden' name='valor' value='" . htmlspecialchars($id) . "'>";

                foreach ($partes as $i => $parte) {
                    if ($i < $totalPartes - 1) {
                        $parteLimpia = htmlspecialchars(trim($parte));
                        echo "<label>$parteLimpia</label>";
                        echo '<input type="text" name="campo' . $i . '" class="campo"><br>';
                    } else {
                        echo "<label>Checklist:</label><br>";
                        $items = explode("+=+", $parte);
                        foreach ($items as $j => $item) {
                            $itemLimpio = htmlspecialchars(trim($item));
                            echo '<label class="checkbox-label"><input type="checkbox" name="check' . $j . '" value="' . $itemLimpio . '"> ' . $itemLimpio . '</label><br>';
                        }
                    }
                }

                echo "<button type='submit' name='resumen'>Mostrar Resumen</button>";
                echo "</form>";

                if (isset($_SESSION['resumen'])) {
                    echo '<h3>Resumen generado:</h3>';
                    echo '<textarea readonly class="resumen-textarea">' . htmlspecialchars($_SESSION['resumen']) . '</textarea>';
                    unset($_SESSION['resumen']);
                }
            }
        } else {
            echo "<div class='error'>⚠️ No se encontró ningún contenido con ese ID.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='error'>⚠️ Error en la base de datos: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='error'>⚠️ ID de protocolo no recibido correctamente.</div>";
}
?>
        <div style="text-align: center; margin-top: 30px;">
            <form action="<?= htmlspecialchars($paginaRegreso) ?>" method="get">
                <button type="submit" class="btn-return">Regresar a la página de inicio</button>
            </form>
        </div>
    </main>

    <footer style="background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 14px; color: #6c757d;">
        <p>&copy; 2025 ITSolvent. Todos los derechos reservados.</p>
        <p>
            <a href="/aviso-legal" style="color: #6c757d; text-decoration: none;">Aviso Legal</a> |
            <a href="/politica-de-privacidad" style="color: #6c757d; text-decoration: none;">Política de Privacidad</a> |
            <a href="/cookies" style="color: #6c757d; text-decoration: none;">Política de Cookies</a>
        </p>
        <p>Síguenos en:
            <a href="https://facebook.com/" target="_blank" style="color: #6c757d; text-decoration: none;">Facebook</a>,
            <a href="https://twitter.com/" target="_blank" style="color: #6c757d; text-decoration: none;">Twitter</a>,
            <a href="https://instagram.com/" target="_blank" style="color: #6c757d; text-decoration: none;">Instagram</a>
        </p>
    </footer>
</body>
</html>
