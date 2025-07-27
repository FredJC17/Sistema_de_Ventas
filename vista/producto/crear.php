<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="/sisventas/vista/layout/css/formulario.css">
    <!-- diseños especiales solo para este formulario-->
    <style>
        .modal-content {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px 20px 20px 20px;
            border-radius: 12px;
            background: #181818;
            box-shadow: 0 0 18px #0008;
        }
        .modal-content .form-row {
            display: flex;
            gap: 12px;
            margin-bottom: 14px;
        }
        .modal-content .form-group {
            flex: 1 1 0;
            min-width: 0;
        }
        .modal-content label {
            font-size: 1em;
            margin-bottom: 4px;
            display: block;
        }
        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 7px 8px;
            font-size: 1em;
            border-radius: 6px;
            border: 1px solid #00e5ff;
            background: #222;
            color: #fff;
            box-sizing: border-box;
        }
        .modal-content input[type="file"] {
            padding: 2px 4px;
            background: #222;
            color: #fff;
            border: none;
        }
        .modal-content h2 {
            font-size: 1.5em;
            margin-bottom: 18px;
            text-align: center;
        }
        .buttons-container {
            display: flex;
            gap: 20px;
            margin-top: 18px;
            justify-content: center;
        }
        .btn-cancel, .btn-save {
            font-size: 1.1em;
            padding: 10px 28px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-cancel {
            background: #666;
            color: #fff;
        }
        .btn-save {
            background: #00e5ff;
            color: #222;
        }
        @media (max-width: 900px) {
            .modal-content {
                max-width: 98vw;
                padding: 10px 2vw;
            }
            .modal-content .form-row {
                flex-wrap: wrap;
            }
            .modal-content .form-group {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
    <div class="modal-overlay active" id="productoModal">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='listado.php'">&times;</span>
            <h2>Agregar Nuevo Producto</h2>
            <form method="POST" action="grabar.php" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label for="idProducto">ID Producto</label>
                        <input type="text" id="idProducto" name="idProducto" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <label for="idProveedor">ID Proveedor</label>
                        <input type="text" id="idProveedor" name="idProveedor" maxlength="3" required>
                    </div>
                    <div class="form-group">
                        <label for="idCategoria">ID Categoría</label>
                        <input type="text" id="idCategoria" name="idCategoria" maxlength="2" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nomProducto">Nombre</label>
                        <input type="text" id="nomProducto" name="nomProducto" maxlength="128" required>
                    </div>
                    <div class="form-group">
                        <label for="unimed">Unidad Medida</label>
                        <input type="text" id="unimed" name="unimed" maxlength="15" required>
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="cosuni">Costo Unitario</label>
                        <input type="number" id="cosuni" name="cosuni" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="preuni">Precio Unitario</label>
                        <input type="number" id="preuni" name="preuni" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" required>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="imagenProducto">Imagen</label>
                        <input type="file" id="imagenProducto" name="imagenProducto" accept="image/*" required>
                    </div>
                </div>
                <div class="buttons-container">
                    <button type="button" class="btn-cancel" onclick="window.location.href='listado.php'">✖ Cancelar</button>
                    <button type="submit" class="btn-save">✓ Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
