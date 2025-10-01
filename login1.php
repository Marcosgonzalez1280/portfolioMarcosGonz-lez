<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <style>
    .logo-top {
      display: block;
      margin: 30px auto 10px;
      height: 65px;
    }

    .login-container {
      max-width: 400px;
      margin: 20px auto 100px;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .login-container h2 {
      margin-bottom: 20px;
      color: #002b5c;
      text-align: center;
    }

    .login-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }

    .login-container button:hover {
      background-color: #0056b3;
    }

    .error {
      color: red;
      background-color: #ffe6e6;
      border: 1px solid #ff0000;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      text-align: center;
    }
  </style>
</head>
<body>

  <!-- LOGO -->
  <img src="logo.webp" alt="Logo ITSolvent" class="logo-top">

  <!-- FORMULARIO LOGIN -->
  <div class="login-container">
    <h2>Iniciar sesión</h2>

    <?php if (isset($_GET['error'])): ?>
      <div class="error">⚠️ Usuario o contraseña incorrectos</div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <input type="text" name="usuario" placeholder="Usuario" required
             value="<?php echo isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : ''; ?>" />
      <input type="password" name="contrasena" placeholder="Contraseña" required />
      <button type="submit">Entrar</button>
    </form>
  </div>

  <!-- PIE DE PÁGINA -->
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
