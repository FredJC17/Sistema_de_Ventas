<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/logindiseno.css">
</head>
<body>
    <div class="header">
        INICIAR SESIÓN
    </div>
    <div class="container">
        <h2>Ingresar</h2>
        
        <?php if (isset($_GET['error'])): ?>
    <div class="error-message">Usuario o contraseña incorrectos</div>
<?php endif; ?>
        <form action="/sisventas/controlador/loginController.php" method="post">
            <label>Usuario o ID</label>
            <input type="text" name="idUsuario" placeholder="Ingrese su usuario o ID" required>
            
            <label>Contraseña</label>  
            <input type="password" name="password" placeholder="Contraseña" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>