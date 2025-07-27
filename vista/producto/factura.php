<?php
session_start();
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$idCliente = isset($_SESSION['cliente_dni']) ? $_SESSION['cliente_dni'] : '';
$cliente = [
    'nomCliente' => '',
    'rucCliente' => '',
    'dirCliente' => '',
    'telCliente' => '',
    'emailCliente' => ''
];
if ($idCliente) {
    include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";
    $db = new DBConection();
    $con = $db->conectar();
    $stmt = $con->prepare("SELECT nomCliente, rucCliente, dirCliente, telCliente, emailCliente FROM clientes WHERE idCliente = ?");
    $stmt->execute([$idCliente]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cliente) {
        $cliente = [
            'nomCliente' => '',
            'rucCliente' => '',
            'dirCliente' => '',
            'telCliente' => '',
            'emailCliente' => ''
        ];
    }
}

$subtotal = 0;
foreach ($carrito as $item) {
    $subtotal += $item['precio'] * $item['cantidad'];
}
$igv = round($subtotal * 0.18, 2);
$total = round($subtotal + $igv, 2);
$_SESSION['total_pago'] = $total;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/factura.css">
</head>
<body>
<div class="invoice-modal-overlay active">
    <div class="invoice-modal-content">
        <div class="invoice-header">
            <span class="invoice-title">FACTURA</span>
            <span class="invoice-number">N° 001-<?= str_pad(rand(1,999999), 9, '0', STR_PAD_LEFT) ?></span>
            <button class="close-btn" onclick="window.location.href='store.php'">&times;</button>
        </div>
        <div class="invoice-body">
            <div class="customer-info info-section">
                <h3>DATOS DEL CLIENTE</h3>
                <p><strong>ID Cliente:</strong> <?= htmlspecialchars($idCliente) ?></p>
                <p><strong>Nombre:</strong> <?= htmlspecialchars($cliente['nomCliente'] ?? '') ?></p>
                <p><strong>RUC:</strong> <?= htmlspecialchars($cliente['rucCliente'] ?? '') ?></p>
                <p><strong>Dirección:</strong> <?= htmlspecialchars($cliente['dirCliente'] ?? '') ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($cliente['emailCliente'] ?? '') ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($cliente['telCliente'] ?? '') ?></p>
            </div>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>PRODUCTO</th>
                        <th>CANT.</th>
                        <th>PRECIO UNIT.</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito as $item): ?>
                    <tr>
                        <td class="product-name"><?= htmlspecialchars($item['nombre']) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td>S/ <?= number_format($item['precio'], 2) ?></td>
                        <td class="product-price">S/ <?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="totals-section">
                <div class="total-row subtotal">
                    <span>Subtotal:</span>
                    <span>S/ <?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="total-row tax">
                    <span>IGV (18%):</span>
                    <span>S/ <?= number_format($igv, 2) ?></span>
                </div>
                <div class="total-row final">
                    <span>TOTAL A PAGAR:</span>
                    <span>S/ <?= number_format($total, 2) ?></span>
                </div>
            </div>
            <div class="payment-methods">
                <h3>Método de Pago</h3>
                <form id="formPago" action="pago.php" method="post">
                    <div class="payment-options">
                        <label>
                            <input type="radio" name="idCondicion" value="1" required onclick="document.getElementById('btnProcesar').disabled = false;">
                            <span class="payment-option">Contado</span>
                        </label>
                        <label>
                            <input type="radio" name="idCondicion" value="2" required onclick="document.getElementById('btnProcesar').disabled = false;">
                            <span class="payment-option">Crédito</span>
                        </label>
                    </div>
                    <div class="invoice-footer">
                        <a href="store.php" class="btn-cancel">✖ Cancelar</a>
                        <button id="btnProcesar" class="btn-pay" type="submit" disabled>Procesar Pago - S/ <?= number_format($total, 2) ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>