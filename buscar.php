<?php
session_start();

$nivel = $_SESSION['nivel'] ?? null;
$paginaRegreso = ($nivel === 'admin') ? 'administrador.html' : 'tecnicos.php';

$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "itsolvent";
$resultados = [];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = trim($_GET['q']);

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $contrasena);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT idProtocolo, palabraClave FROM protocolo WHERE palabraClave LIKE :query");
        $stmt->execute([':query' => '%' . $q . '%']);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar protocolos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- HEADER UNIFICADO CON LOGO -->
    <header class="header">
        <div style="display: flex; align-items: center;">
            <img src="logo.webp" alt="Logo ITSolvent" class="logo-top-left">
            <h2 style="margin: 0;">Buscador de Protocolos</h2>
        </div>
        <button onclick="window.location.href='logout.php'">Cerrar sesión</button>
    </header>

    <div class="search-container">
        <form method="GET" action="buscar.php" class="centered-form">
            <input type="text" name="q" placeholder="Ej: SAP, error, adobe..." required>
            <br>
            <input type="submit" value="Buscar">
        </form>

        <?php if (!empty($resultados)): ?>
            <div class="result-container">
                <h3 style="text-align:center;">Resultados</h3>
                <?php foreach ($resultados as $item): ?>
                    <form method="POST" action="tapu.php">
                        <input type="hidden" name="valor" value="<?= htmlspecialchars($item['idProtocolo']) ?>">
                        <button type="submit" class="result-button"><?= htmlspecialchars($item['palabraClave']) ?></button>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($_GET['q'])): ?>
            <p style="text-align:center;">No se encontraron resultados para "<strong><?= htmlspecialchars($_GET['q']) ?></strong>".</p>
        <?php endif; ?>

        <!-- Botón de regresar -->
        <div style="text-align: center; margin-top: 30px;">
            <form action="<?= htmlspecialchars($paginaRegreso) ?>" method="get">
                <button type="submit" class="btn-return">Regresar a la página de inicio</button>
            </form>
        </div>
    </div>

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
