<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
</head>
<body>
    <div class="modal-overlay active" id="usuarioModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Agregar Nuevo Usuario</h2>
            <form id="usuarioForm" method="POST" action="grabar.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="idUsuario">ID</label>
                        <input type="text" id="idUsuario" name="idUsuario" required maxlength="3">
                    </div>
                    <div class="form-group">
                        <label for="nomUsuario">Usuario</label>
                        <input type="text" id="nomUsuario" name="nomUsuario" required maxlength="15">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required maxlength="255">
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" required maxlength="64">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombres">Nombres</label>
                        <input type="text" id="nombres" name="nombres" required maxlength="64">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" maxlength="64">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" id="estado" name="estado" required maxlength="1" placeholder="A/I">
                    </div>
                </div>
                <div class="buttons-container">
                    <button type="button" class="btn-cancel" onclick="closeModal()">✖ Cancelar</button>
                    <button type="submit" class="btn-save">✓ Guardar Usuario</button>
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