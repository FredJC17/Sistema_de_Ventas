<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cliente</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active" id="clienteModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Agregar Nuevo Cliente</h2>
            <form id="clienteForm" method="POST" action="grabar.php">
    <div class="form-row">
        <div class="form-group">
            <label for="idCliente">ID (DNI)</label>
            <input type="text" id="idCliente" name="idCliente" required maxlength="10">
        </div>
        <div class="form-group">
            <label for="nomCliente">Nombre</label>
            <input type="text" id="nomCliente" name="nomCliente" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="rucCliente">RUC</label>
            <input type="text" id="rucCliente" name="rucCliente">
        </div>
        <div class="form-group">
            <label for="dirCliente">Dirección</label>
            <input type="text" id="dirCliente" name="dirCliente">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="telCliente">Teléfono</label>
            <input type="text" id="telCliente" name="telCliente" required>
        </div>
        <div class="form-group">
            <label for="emailCliente">Email</label>
            <input type="email" id="emailCliente" name="emailCliente">
        </div>
    </div>
    <div class="buttons-container">
        <button type="button" class="btn-cancel" onclick="closeModal()">✖ Cancelar</button>
        <button type="submit" class="btn-save">✓ Guardar Cliente</button>
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