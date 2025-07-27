<?php
include $_SERVER['DOCUMENT_ROOT']."/sisventas/controlador/productoController.php";

$producto = new ProductoController();
$datos = $producto->obtener_listado();

session_start();

if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {
    header("Location: /sisventas/login.php");
    exit();
}

$cartCount = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;

$stockPopups = [];
foreach ($datos as $row) {
    if ($row['stock'] == 0) {
        $stockPopups[] = [
            'type' => 'error',
            'title' => 'Sin stock',
            'message' => 'El producto <b>' . htmlspecialchars($row['nomProducto']) . '</b> no tiene stock disponible.'
        ];
    } elseif ($row['stock'] > 0 && $row['stock'] < 20) { 
        $stockPopups[] = [
            'type' => 'warning',
            'title' => 'Stock bajo',
            'message' => 'El producto <b>' . htmlspecialchars($row['nomProducto']) . '</b> tiene poco stock: ' . $row['stock']
        ];
    }
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/navbar.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/logindiseno.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/tabla.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/cartas_compras.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/carrito.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/popups.css">
</head>
<body>
    <nav class="navbar">
  <div class="navbar-container">
    <div class="navbar-logo">SISTEMA DE VENTAS</div>
    <ul class="navbar-menu">
      <li><a href="/sisventas/index.php">Inicio</a></li>

      <li class="dropdown">
        <a>Buscar ▾</a>
        <ul class="dropdown-menu">
          <li><a href="buscar.php?tipo=id">Por "ID"</a></li>
          <li><a href="buscar.php?tipo=nombre">Por "Nombre"</a></li>
          <li><a href="buscar.php?tipo=categoria">Por "Categoría"</a></li>
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

<?php if (!empty($stockPopups)): ?>
    <div class="notifications-container">
        <?php foreach ($stockPopups as $spopup): ?>
        <div class="notification-popup notification-<?= $spopup['type'] ?> show">
            <div class="notification-header">
                <span class="notification-icon"></span>
                <span class="notification-title"><?= $spopup['title'] ?></span>
                <button class="notification-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
            </div>
            <div class="notification-content"><?= $spopup['message'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($popup)): ?>
    <div class="notifications-container">
        <div class="notification-popup notification-<?= $popup['type'] ?> show">
            <div class="notification-header">
                <span class="notification-icon"></span>
                <span class="notification-title"><?= htmlspecialchars($popup['title']) ?></span>
                <button class="notification-close" onclick="this.parentElement.parentElement.parentElement.style.display='none'">&times;</button>
            </div>
            <div class="notification-content"><?= htmlspecialchars($popup['message']) ?></div>
        </div>
    </div>
<?php endif; ?>

<div class="cards-container">
<?php foreach($datos as $row): ?>
    <?php
        if ($row['stock'] > 20) {
            $cardClass = 'card_';
        } elseif ($row['stock'] > 0) {
            $cardClass = 'card-advertencia';
        } else {
            $cardClass = 'card-vencida';
        }

        $imgSrc = '';
        if (!empty($row['imagenProducto'])) {
            $imgSrc = 'data:image/jpeg;base64,' . base64_encode($row['imagenProducto']);
        }
        $cardHref = ($row['stock'] > 0)
            ? "cantidad.php?id={$row['idProducto']}&nombre=" . urlencode($row['nomProducto']) . "&precio={$row['preuni']}&stock={$row['stock']}"
            : "#";
        $cardStyle = ($row['stock'] > 0)
            ? 'cursor:pointer; text-decoration:none; color:inherit;'
            : 'pointer-events:none; opacity:0.6;';
    ?>
    <div class="<?= $cardClass ?>">
        <a href="<?= $cardHref ?>" style="<?= $cardStyle ?>">
            <div class="card_2">
                <?php if ($imgSrc): ?>
                    <img src="<?= $imgSrc ?>" alt="Imagen Producto" style="width:100px; height:100px; object-fit:cover; border-radius:10px; margin-bottom:10px;">
                <?php else: ?>
                    <div style="width:100px; height:100px; background:#444; border-radius:10px; margin-bottom:10px; display:flex; align-items:center; justify-content:center; color:#ccc;">Sin imagen</div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['nomProducto']) ?></h3>
                <p><strong>ID:</strong> <?= htmlspecialchars($row['idProducto']) ?></p>
                <p><strong>Categoría:</strong> <?= htmlspecialchars($row['idCategoria']) ?></p>
                <p><strong>Unidad:</strong> <?= htmlspecialchars($row['unimed']) ?></p>
                <p><strong>Precio:</strong> S/ <?= htmlspecialchars($row['preuni']) ?></p>
                <p><strong>Stock:</strong> <?= htmlspecialchars($row['stock']) ?></p>
            </div>
        </a>
    </div>
<?php endforeach; ?>
</div>
<?php if (empty($datos)): ?>
    <div style="color:#fff; text-align:center; font-size:1.2em; margin-top:40px;">
        No se encontraron productos.
    </div>
<?php endif; ?>
<button class="floating-cart-btn fade-in" onclick="window.location.href='carrito.php'">
    <svg class="cart-icon" viewBox="0 0 24 24">
        <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
    </svg>
    <span class="cart-badge"><?= $cartCount ?></span>
    <div class="cart-tooltip">Ver Carrito</div>
</button>
</body>
</html>

