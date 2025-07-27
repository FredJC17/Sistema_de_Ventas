<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

$con = (new DBConection())->conectar();

$hoy = date('Y-m-d');
$primerDiaMes = date('Y-m-01');
$fechaInicio = $primerDiaMes;
$fechaFin = $hoy;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    if ($tipo === 'dia') {
        $fechaInicio = $fechaFin = $_POST['fecha_dia'];
    } elseif ($tipo === 'mes') {
        $fechaInicio = $_POST['fecha_mes'] . '-01';
        $fechaFin = date('Y-m-t', strtotime($fechaInicio));
    } elseif ($tipo === 'personalizado') {
        $fechaInicio = $_POST['fecha_inicio'];
        $fechaFin = $_POST['fecha_fin'];
    }
} else {
    $tipo = 'mes';
}

$sql = "
SELECT 
    p.idProducto,
    p.nomProducto,
    p.unimed,
    SUM(df.cant) AS total_vendidos,
    p.preuni,
    p.cosuni,
    (p.preuni - p.cosuni) AS ganancia_unidad,
    SUM(df.cant * (p.preuni - p.cosuni)) AS ganancia_total
FROM detallefactura df
JOIN productos p ON df.idProducto = p.idProducto
JOIN facturas f ON df.idFactura = f.idFactura
WHERE f.fecha BETWEEN ? AND ?
GROUP BY p.idProducto, p.nomProducto, p.unimed, p.preuni, p.cosuni
ORDER BY ganancia_total DESC
";
$stmt = $con->prepare($sql);
$stmt->execute([$fechaInicio, $fechaFin]);
$reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ganancias por Producto</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/navbar.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/logindiseno.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/tabla.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/popups.css">
    <link rel="stylesheet" href="/sisventas/vista/layout/css/filtro.css">
</head>
<body>
<nav class="navbar">
  <div class="navbar-container">
    <div class="navbar-logo">SISTEMA DE VENTAS</div>
    <ul class="navbar-menu">
       <a href="/sisventas/vista/producto/listado.php">Regresar </a>
    </ul>
  </div>
</nav>

<div class="filtros-reporte">
    <form method="POST">
        <label>Tipo de reporte:</label>
        <select name="tipo" id="tipo-reporte" onchange="mostrarFiltros()">
            <option value="dia" <?= $tipo=='dia'?'selected':'' ?>>Por Día</option>
            <option value="mes" <?= $tipo=='mes'?'selected':'' ?>>Por Mes</option>
            <option value="personalizado" <?= $tipo=='personalizado'?'selected':'' ?>>Personalizado</option>
        </select>
        <span id="filtro-dia" style="display:<?= $tipo=='dia'?'inline':'none' ?>">
            <label>Fecha:</label>
            <input type="date" name="fecha_dia" value="<?= htmlspecialchars($fechaInicio) ?>">
        </span>
        <span id="filtro-mes" style="display:<?= $tipo=='mes'?'inline':'none' ?>">
            <label>Mes:</label>
            <input type="month" name="fecha_mes" value="<?= substr($fechaInicio,0,7) ?>">
        </span>
        <span id="filtro-personalizado" style="display:<?= $tipo=='personalizado'?'inline':'none' ?>">
            <label>Desde:</label>
            <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fechaInicio) ?>">
            <label>Hasta:</label>
            <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fechaFin) ?>">
        </span>
        <button type="submit">Generar</button>
    </form>
</div>

<div class="table-container">
    <h2 class="tabla-titulo">Reporte de Ganancias por Producto</h2>
    <table>
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Cantidad Vendida</th>
                <th>Precio Venta</th>
                <th>Costo Unitario</th>
                <th>Ganancia por Unidad</th>
                <th>Ganancia Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reporte as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['idProducto']) ?></td>
                <td><?= htmlspecialchars($row['nomProducto']) ?></td>
                <td><?= htmlspecialchars($row['unimed']) ?></td>
                <td><?= htmlspecialchars($row['total_vendidos']) ?></td>
                <td>S/ <?= number_format($row['preuni'], 2) ?></td>
                <td>S/ <?= number_format($row['cosuni'], 2) ?></td>
                <td>S/ <?= number_format($row['ganancia_unidad'], 2) ?></td>
                <td style="font-weight:bold; color:#00e5ff;">S/ <?= number_format($row['ganancia_total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($reporte)): ?>
            <tr>
                <td colspan="8" style="text-align:center; color:#fff;">No hay datos para el rango seleccionado.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
function mostrarFiltros() {
    var tipo = document.getElementById('tipo-reporte').value;
    document.getElementById('filtro-dia').style.display = tipo === 'dia' ? 'inline' : 'none';
    document.getElementById('filtro-mes').style.display = tipo === 'mes' ? 'inline' : 'none';
    document.getElementById('filtro-personalizado').style.display = tipo === 'personalizado' ? 'inline' : 'none';
}
</script>
</body>
</html>