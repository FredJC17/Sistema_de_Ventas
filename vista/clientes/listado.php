<?php
include $_SERVER['DOCUMENT_ROOT']."/sisventas/controlador/clienteController.php";

$cliente = new ClienteController();
$datos = $cliente->obtener_listado();

session_start();
if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {
    header("Location: /sisventas/login.php");
    exit();
}
if (isset($_SESSION['popup'])) {
    $popup = $_SESSION['popup'];
    unset($_SESSION['popup']);
}
if (isset($_GET['busqueda']) && isset($_SESSION['resultados_busqueda'])) {
    $datos = $_SESSION['resultados_busqueda'];
    unset($_SESSION['resultados_busqueda']);
}
?>
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/navbar.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/logindiseno.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/tabla.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/popups.css">
</head>
<body>
    <body>
<?php if (isset($popup)): ?>
    <div class="notifications-container">
        <div class="notification-popup notification-<?= $popup['type'] ?> show">
            <div class="notification-header">
                <span class="notification-icon"></span>
                <span class="notification-title"><?= htmlspecialchars($popup['title']) ?></span>
                <button class="notification-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
            </div>
            <div class="notification-content"><?= htmlspecialchars($popup['message']) ?></div>
        </div>
    </div>
<?php endif; ?>
<nav class="navbar">
  <div class="navbar-container">
    <div class="navbar-logo">SISTEMA DE VENTAS</div>
    <ul class="navbar-menu">
      <li><a href="/sisventas/index.php">Inicio</a></li>

      <li><a href="crear.php">Agregar</a></li>

      <li class="dropdown">
        <a href="#">Buscar ▾</a>
        <ul class="dropdown-menu">
          <li><a href="buscar.php?tipo=id">Por "ID"</a></li>
          <li><a href="buscar.php?tipo=nombre">Por "Nombre"</a></li>
        </ul>
      </li>

      <li class="dropdown">
        <a>Cambiar Pestaña ▾</a>
        <ul class="dropdown-menu">
          <li><a href="/sisventas/vista/producto/store.php">Comprar</a></li>
          <li><a href="/sisventas/vista/producto/listado.php">Productos</a></li>
          <li><a href="/sisventas/vista/clientes/listado.php">Clientes</a></li>
          <li><a href="/sisventas/vista/proveedores/listado.php">Proveedores</a></li>
          <li><a href="/sisventas/vista/usuarios/listado.php">Usuarios</a></li>
          <li><a href="/sisventas/vista/ventas/listado.php">Ventas</a></li>
        </ul>
      </li>
      <li><a href="/sisventas/logout.php">Salir</a></li>
    </ul>
  </div>
</nav>
<?php if (isset($popup)): ?>
    <div class="notifications-container">
        <div class="notification-popup notification-<?= $popup['type'] ?> show">
            <div class="notification-header">
                <span class="notification-icon"></span>
                <span class="notification-title"><?= htmlspecialchars($popup['title']) ?></span>
                <button class="notification-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
            </div>
            <div class="notification-content"><?= htmlspecialchars($popup['message']) ?></div>
        </div>
    </div>
<?php endif; ?>
<div class="table-container">
    <h2 class="tabla-titulo">Listado de Clientes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>RUC</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($datos as $row): ?>
            <tr>
                <td data-label="ID" title="<?= htmlspecialchars($row['idCliente']) ?>"><?= htmlspecialchars($row['idCliente']) ?></td>
                <td data-label="Nombre" title="<?= htmlspecialchars($row['nomCliente']) ?>"><?= htmlspecialchars($row['nomCliente']) ?></td>
                <td data-label="RUC" title="<?= htmlspecialchars($row['rucCliente']) ?>"><?= htmlspecialchars($row['rucCliente']) ?></td>
                <td data-label="Dirección" title="<?= htmlspecialchars($row['dirCliente']) ?>"><?= htmlspecialchars($row['dirCliente']) ?></td>
                <td data-label="Teléfono" title="<?= htmlspecialchars($row['telCliente']) ?>"><?= htmlspecialchars($row['telCliente']) ?></td>
                <td data-label="Email" title="<?= htmlspecialchars($row['emailCliente']) ?>"><?= htmlspecialchars($row['emailCliente']) ?></td>
                <td data-label="Acciones">
                    <a href="update.php?id=<?= urlencode($row['idCliente']) ?>" class="boton-editar">Editar</a>
                </td>
                <td data-label="Eliminar">
                    <a href="eliminar.php?id=<?= urlencode($row['idCliente']) ?>&nombre=<?= urlencode($row['nomCliente']) ?>" class="boton-eliminar">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>