<?php
include $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";
session_start();

if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {
    header("Location: /sisventas/login.php");
    exit();
}

$con = (new DBConection())->conectar();

// Búsqueda
$datos = [];
if (isset($_GET['busqueda']) && isset($_SESSION['resultados_busqueda'])) {
    $datos = $_SESSION['resultados_busqueda'];
    unset($_SESSION['resultados_busqueda']);
} else {
    $stmt = $con->prepare("SELECT f.*, c.nomCliente FROM facturas f LEFT JOIN clientes c ON f.idCliente = c.idCliente ORDER BY f.idFactura DESC");
    $stmt->execute();
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_SESSION['popup'])) {
    $popup = $_SESSION['popup'];
    unset($_SESSION['popup']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Facturas</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/navbar.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/logindiseno.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/tabla.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/popups.css">
</head>
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
      <li class="dropdown">
        <a>Buscar ▾</a>
        <ul class="dropdown-menu">
          <li><a href="buscar.php?tipo=id">Por "ID Factura"</a></li>
          <li><a href="buscar.php?tipo=cliente">Por "Cliente"</a></li>
          <li><a href="buscar.php?tipo=condicion">Por "Condición"</a></li>
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
    <h2 class="tabla-titulo">Listado de Facturas</h2>
    <table>
        <thead>
            <tr>
                <th>ID Factura</th>
                <th>Cliente</th>
                <th>Usuario</th>
                <th>Condición</th>
                <th>Fecha</th>
                <th>Fecha Registro</th>
                <th>Valor Venta</th>
                <th>IGV</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($datos as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['idFactura']) ?></td>
                <td><?= htmlspecialchars($row['nomCliente']) ?></td>
                <td><?= htmlspecialchars($row['idUsuario']) ?></td>
                <td>
                    <?php
                        if ($row['idCondicion'] == 1) echo "Contado";
                        elseif ($row['idCondicion'] == 2) echo "Crédito";
                        else echo htmlspecialchars($row['idCondicion']);
                    ?>
                </td>
                <td><?= htmlspecialchars($row['fecha']) ?></td>
                <td><?= htmlspecialchars($row['fechaReg']) ?></td>
                <td>S/ <?= number_format($row['valorVenta'], 2) ?></td>
                <td>S/ <?= number_format($row['igv'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>