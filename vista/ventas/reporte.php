<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

$con = (new DBConection())->conectar();

// Fechas por defecto
$hoy = date('Y-m-d');
$primerDiaMes = date('Y-m-01');
$fechaInicio = $primerDiaMes;
$fechaFin = $hoy;

// Procesar filtro
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

// Consulta de reporte: ventas por día
$sql = "
SELECT 
    f.fecha,
    COUNT(f.idFactura) AS total_facturas,
    SUM(f.valorVenta) AS monto_total
FROM facturas f
WHERE f.fecha BETWEEN ? AND ?
GROUP BY f.fecha
ORDER BY total_facturas DESC, f.fecha DESC
";
$stmt = $con->prepare($sql);
$stmt->execute([$fechaInicio, $fechaFin]);
$reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total general
$totalFacturas = 0;
$totalMonto = 0;
foreach ($reporte as $row) {
    $totalFacturas += $row['total_facturas'];
    $totalMonto += $row['monto_total'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas por Día</title>
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
       <a href="/sisventas/vista/ventas/listado.php">Regresar </a>
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
    <h2 class="tabla-titulo">Reporte de Ventas por Día</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cantidad de Facturas</th>
                <th>Monto Total Vendido</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reporte as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['fecha']) ?></td>
                <td><?= htmlspecialchars($row['total_facturas']) ?></td>
                <td>S/ <?= number_format($row['monto_total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($reporte)): ?>
            <tr>
                <td colspan="3" style="text-align:center; color:#fff;">No hay datos para el rango seleccionado.</td>
            </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr style="font-weight:bold; color:#00e5ff;">
                <td>Total</td>
                <td><?= $totalFacturas ?></td>
                <td>S/ <?= number_format($totalMonto, 2) ?></td>
            </tr>
        </tfoot>
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