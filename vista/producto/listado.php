<?php
include $_SERVER['DOCUMENT_ROOT']."/sisventas/controlador/productoController.php";

$producto = new ProductoController();
session_start();

if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {
    header("Location: /sisventas/login.php");
    exit();
}

if (isset($_GET['busqueda']) && isset($_SESSION['resultados_busqueda'])) {
    $datos = $_SESSION['resultados_busqueda'];
    unset($_SESSION['resultados_busqueda']);
} else {
    $datos = $producto->obtener_listado();
}

if (isset($_SESSION['popup'])) {
    $popup = $_SESSION['popup'];
    unset($_SESSION['popup']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/navbar.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/logindiseno.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/tabla.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/popups.css">
</head>
<body>
<nav class="navbar">
  <div class="navbar-container">
    <div class="navbar-logo">SISTEMA DE VENTAS</div>
    <ul class="navbar-menu">
      <li><a href="/sisventas/index.php">Inicio</a></li>
      <li><a href="crear.php">Agregar</a></li>
      <li class="dropdown">
        <a href="#">Buscar ▾</a>
        <ul class="dropdown-menu">
          <li><a href="buscar2.php?tipo=id">Por "ID"</a></li>
          <li><a href="buscar2.php?tipo=nombre">Por "Nombre"</a></li>
          <li><a href="buscar2.php?tipo=categoria">Por "Categoria"</a></li>
        </ul>
      </li>
      <li><a href="reporte.php">Reporte</a></li>

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
<div class="table-container">
    <h2 class="tabla-titulo">Listado de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Proveedor</th>
                <th>Nombre</th>
                <th>Unidad Medida</th>
                <th>Stock</th>
                <th>Costo Unitario</th>
                <th>Precio Unitario</th>
                <th>ID Categoría</th>
                <th>Estado</th>
                <th>Acciones</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($datos as $row): ?>
            <tr>
                <td data-label="ID" title="<?= htmlspecialchars($row['idProducto']) ?>"><?= htmlspecialchars($row['idProducto']) ?></td>
                <td data-label="ID Proveedor" title="<?= htmlspecialchars($row['idProveedor']) ?>"><?= htmlspecialchars($row['idProveedor']) ?></td>
                <td data-label="Nombre" title="<?= htmlspecialchars($row['nomProducto']) ?>"><?= htmlspecialchars($row['nomProducto']) ?></td>
                <td data-label="Unidad Medida" title="<?= htmlspecialchars($row['unimed']) ?>"><?= htmlspecialchars($row['unimed']) ?></td>
                <td data-label="Stock" title="<?= htmlspecialchars($row['stock']) ?>"><?= htmlspecialchars($row['stock']) ?></td>
                <td data-label="Costo Unitario" title="<?= htmlspecialchars($row['cosuni']) ?>"><?= htmlspecialchars($row['cosuni']) ?></td>
                <td data-label="Precio Unitario" title="<?= htmlspecialchars($row['preuni']) ?>"><?= htmlspecialchars($row['preuni']) ?></td>
                <td data-label="ID Categoría" title="<?= htmlspecialchars($row['idCategoria']) ?>"><?= htmlspecialchars($row['idCategoria']) ?></td>
                <td data-label="Estado" title="<?= $row['estado']=='1' ? 'Activo' : 'Inactivo' ?>"><?= $row['estado']=='1' ? 'Activo' : 'Inactivo' ?></td>
                <td data-label="Acciones">
                  <a href="update.php?id=<?= urlencode($row['idProducto']) ?>" class="boton-editar">Editar</a>
                </td>
                <td data-label="Eliminar">
                  <a href="eliminar.php?id=<?= urlencode($row['idProducto']) ?>&nombre=<?= urlencode($row['nomProducto']) ?>" class="boton-eliminar">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
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
</body>
</html>
