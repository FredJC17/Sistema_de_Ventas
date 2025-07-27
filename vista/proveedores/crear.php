<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Proveedor</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active" id="proveedorModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Agregar Nuevo Proveedor</h2>
            <form id="proveedorForm" method="POST" action="grabar.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="idProveedor">ID</label>
                        <input type="text" id="idProveedor" name="idProveedor" required maxlength="3">
                    </div>
                    <div class="form-group">
                        <label for="nomProveedor">Nombre</label>
                        <input type="text" id="nomProveedor" name="nomProveedor" required maxlength="128">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="rucProveedor">RUC</label>
                        <input type="text" id="rucProveedor" name="rucProveedor" maxlength="11">
                    </div>
                    <div class="form-group">
                        <label for="dirProveedor">Dirección</label>
                        <input type="text" id="dirProveedor" name="dirProveedor" maxlength="128">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="telProveedor">Teléfono</label>
                        <input type="text" id="telProveedor" name="telProveedor" required maxlength="9">
                    </div>
                    <div class="form-group">
                        <label for="emailProveedor">Email</label>
                        <input type="email" id="emailProveedor" name="emailProveedor" maxlength="64">
                    </div>
                </div>
                <div class="buttons-container">
                    <button type="button" class="btn-cancel" onclick="closeModal()">✖ Cancelar</button>
                    <button type="submit" class="btn-save">✓ Guardar Proveedor</button>
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