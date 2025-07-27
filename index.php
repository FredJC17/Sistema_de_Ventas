
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/cartas.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/cabeceraindex.css">
</head>
<body>
<div class="header">
  <div class="header-content">
    <div class="title">SISTEMA DE VENTAS</div>
    <a href="/sisventas/logout.php" class="logout-btn">Cerrar sesión</a>
  </div>
</div>

<div class="cards-wrapper">
  <a href="/sisventas/vista/producto/store.php" class="card">
    <div class="card2">
      <h2>COMPRAS</h2>
    </div>
  </a>
  <a href="/sisventas/vista/producto/listado.php" class="card">
    <div class="card2">
      <h2>PRODUCTOS</h2>
    </div>
  </a>

  <a href="/sisventas/vista/clientes/listado.php" class="card">
    <div class="card2">
      <h2>CLIENTES</h2>
    </div>
  </a>

  <a href="/sisventas/vista/proveedores/listado.php" class="card">
    <div class="card2">
      <h2>PROVEEDORES</h2>
    </div>
  </a>

  <a href="/sisventas/vista/usuarios/listado.php" class="card">
    <div class="card2">
      <h2>USUARIOS</h2>
    </div>
  </a>

  <a href="/sisventas/vista/ventas/listado.php" class="card">
    <div class="card2">
      <h2>VENTAS</h2>
    </div>
  </a>
</div>
</body>
</html>
<?php

session_start();

if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {
    header("Location: /sisventas/login.php");
    exit();
}
?>