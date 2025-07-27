<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

$idCliente = isset($_GET['id']) ? $_GET['id'] : '';
$cliente = null;

if ($idCliente) {
    $db = new DBConection();
    $con = $db->conectar();
    $stmt = $con->prepare("SELECT * FROM clientes WHERE idCliente = ?");
    $stmt->execute([$idCliente]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cliente) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Cliente no encontrado',
            'message' => 'No se encontró el cliente solicitado.'
        ];
        header("Location: listado.php");
        exit();
    }
} else {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error',
        'message' => 'No se especificó el cliente a editar.'
    ];
    header("Location: listado.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
    <style>
        @media (max-width: 700px) {
            .modal-content {
                max-height: 90vh;
                overflow-y: auto;
            }
        }
    </style>
</head>
<body>
    <div class="modal-overlay active" id="clienteModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Editar Cliente</h2>
            <form id="clienteForm" method="POST" action="update.php?id=<?= htmlspecialchars($idCliente) ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="idCliente">ID (DNI)</label>
                        <input type="text" id="idCliente" name="idCliente" value="<?= htmlspecialchars($cliente['idCliente']) ?>" required maxlength="10" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nomCliente">Nombre</label>
                        <input type="text" id="nomCliente" name="nomCliente" value="<?= htmlspecialchars($cliente['nomCliente']) ?>" required maxlength="128">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="rucCliente">RUC</label>
                        <input type="text" id="rucCliente" name="rucCliente" value="<?= htmlspecialchars($cliente['rucCliente']) ?>" maxlength="11">
                    </div>
                    <div class="form-group">
                        <label for="dirCliente">Dirección</label>
                        <input type="text" id="dirCliente" name="dirCliente" value="<?= htmlspecialchars($cliente['dirCliente']) ?>" maxlength="128">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="telCliente">Teléfono</label>
                        <input type="text" id="telCliente" name="telCliente" value="<?= htmlspecialchars($cliente['telCliente']) ?>" required maxlength="9">
                    </div>
                    <div class="form-group">
                        <label for="emailCliente">Email</label>
                        <input type="email" id="emailCliente" name="emailCliente" value="<?= htmlspecialchars($cliente['emailCliente']) ?>" maxlength="64">
                    </div>
                </div>
                <div class="buttons-container">
                    <button type="button" class="btn-cancel" onclick="closeModal()">✖ Cancelar</button>
                    <button type="submit" class="btn-save">✓ Actualizar Cliente</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function closeModal() {
            window.location.href = "listado.php";
        }
    </script>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function validar($campo, $max, $obligatorio = false) {
        if (!isset($_POST[$campo])) return !$obligatorio;
        $valor = trim($_POST[$campo]);
        if ($obligatorio && $valor === '') return false;
        if (strlen($valor) > $max) return false;
        return $valor;
    }
    $nomCliente   = validar('nomCliente', 128, true);
    $rucCliente   = validar('rucCliente', 11);
    $dirCliente   = validar('dirCliente', 128);
    $telCliente   = validar('telCliente', 9, true);
    $emailCliente = validar('emailCliente', 64);

    if (!$nomCliente || !$telCliente) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error al actualizar',
            'message' => 'Campos obligatorios vacíos o datos demasiado largos.'
        ];
        header("Location: listado.php");
        exit();
    }
    if (!preg_match('/^\d{1,9}$/', $telCliente)) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error en Teléfono',
            'message' => 'El teléfono debe contener solo números y hasta 9 dígitos.'
        ];
        header("Location: listado.php");
        exit();
    }
    try {
        $stmt = $con->prepare("UPDATE clientes SET nomCliente=?, rucCliente=?, dirCliente=?, telCliente=?, emailCliente=? WHERE idCliente=?");
        $stmt->execute([
            $nomCliente,
            $rucCliente ?: null,
            $dirCliente ?: null,
            $telCliente,
            $emailCliente ?: null,
            $idCliente
        ]);
        $_SESSION['popup'] = [
            'type' => 'success',
            'title' => 'Cliente actualizado',
            'message' => 'La información del cliente fue actualizada correctamente.'
        ];
        header("Location: listado.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error inesperado',
            'message' => 'No se pudo actualizar el cliente. ' . $e->getMessage()
        ];
        header("Location: listado.php");
        exit();
    }
}
?>