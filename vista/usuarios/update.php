<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/sisventas/includes/db.php";

$idUsuario = isset($_GET['id']) ? $_GET['id'] : '';
$usuario = null;

if ($idUsuario) {
    $db = new DBConection();
    $con = $db->conectar();
    $stmt = $con->prepare("SELECT * FROM usuarios WHERE idUsuario = ?");
    $stmt->execute([$idUsuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Usuario no encontrado',
            'message' => 'No se encontró el usuario solicitado.'
        ];
        header("Location: listado.php");
        exit();
    }
} else {
    $_SESSION['popup'] = [
        'type' => 'error',
        'title' => 'Error',
        'message' => 'No se especificó el usuario a editar.'
    ];
    header("Location: listado.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function validar($campo, $max, $obligatorio = false) {
        if (!isset($_POST[$campo])) return !$obligatorio;
        $valor = trim($_POST[$campo]);
        if ($obligatorio && $valor === '') return false;
        if (strlen($valor) > $max) return false;
        return $valor;
    }
    $nomUsuario = validar('nomUsuario', 15, true);
    $password   = validar('password', 255, false);
    $apellidos  = validar('apellidos', 64, true);
    $nombres    = validar('nombres', 64, true);
    $email      = validar('email', 64);
    $estado     = validar('estado', 1, true);

    if (!$nomUsuario || !$apellidos || !$nombres || !$estado) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error al actualizar',
            'message' => 'Campos obligatorios vacíos o datos demasiado largos.'
        ];
        header("Location: listado.php");
        exit();
    }

    try {
        if ($password !== '') {
            $stmt = $con->prepare("UPDATE usuarios SET nomUsuario=?, password=?, apellidos=?, nombres=?, email=?, estado=? WHERE idUsuario=?");
            $stmt->execute([
                $nomUsuario,
                $password,
                $apellidos,
                $nombres,
                $email ?: null,
                $estado,
                $idUsuario
            ]);
        } else {
            $stmt = $con->prepare("UPDATE usuarios SET nomUsuario=?, apellidos=?, nombres=?, email=?, estado=? WHERE idUsuario=?");
            $stmt->execute([
                $nomUsuario,
                $apellidos,
                $nombres,
                $email ?: null,
                $estado,
                $idUsuario
            ]);
        }
        $_SESSION['popup'] = [
            'type' => 'success',
            'title' => 'Usuario actualizado',
            'message' => 'La información del usuario fue actualizada correctamente.'
        ];
        header("Location: listado.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['popup'] = [
            'type' => 'error',
            'title' => 'Error inesperado',
            'message' => 'No se pudo actualizar el usuario. ' . $e->getMessage()
        ];
        header("Location: listado.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
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
    <div class="modal-overlay active" id="usuarioModal">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='listado.php'">&times;</span>
            <h2>Editar Usuario</h2>
            <form id="usuarioForm" method="POST" action="update.php?id=<?= htmlspecialchars($idUsuario) ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="idUsuario">ID</label>
                        <input type="text" id="idUsuario" name="idUsuario" value="<?= htmlspecialchars($usuario['idUsuario']) ?>" required maxlength="3" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nomUsuario">Usuario</label>
                        <input type="text" id="nomUsuario" name="nomUsuario" value="<?= htmlspecialchars($usuario['nomUsuario']) ?>" required maxlength="15">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" value="" maxlength="255" placeholder="Ingrese nueva contraseña">
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required maxlength="64">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombres">Nombres</label>
                        <input type="text" id="nombres" name="nombres" value="<?= htmlspecialchars($usuario['nombres']) ?>" required maxlength="64">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" maxlength="64">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" id="estado" name="estado" value="<?= htmlspecialchars($usuario['estado']) ?>" required maxlength="1" placeholder="A/I">
                    </div>
                </div>
                <div class="buttons-container">
                    <button type="button" class="btn-cancel" onclick="window.location.href='listado.php'">✖ Cancelar</button>
                    <button type="submit" class="btn-save">✓ Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>