<?php
// Validar autenticación y rol mínimo requerido
require_once "security.php";

// ===========================
// Valida si el usuario tiene el permiso requerido.
// Permisos posibles: admin, editor, global.
// ===========================
function validarPermiso(int $usuarioId, string $permiso): bool {
    switch ($permiso) {
        case 'admin':
            return validarRolAdmin($usuarioId);
        case 'editor':
            return validarRolEditor($usuarioId);
        case 'global':
            return validarRolGeneral($usuarioId);
        default:
            return false;
    }
}

// ===========================
// Oculta la contraseña del/los usuario(s).
// ===========================
function ocultarContrasena(array $usuarios) {
    if (isset($usuarios[0]) && is_array($usuarios[0])) {
        foreach ($usuarios as &$u) {
            unset($u['contrasena']);
        }
        return $usuarios;
    }
    if (isset($usuarios['contrasena'])) {
        unset($usuarios['contrasena']);
    }
    return $usuarios;
}
// ===========================
// Valida si el usuario está autenticado.
// ===========================
function estaAutenticado(): bool {
    return isset($_SESSION['usuario_id']) && is_numeric($_SESSION['usuario_id']);
}

// ===========================
// Valida que exista un ID válido en GET.
// ===========================
function idGetValido(): ?int {
    return isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;
}

// ===========================
// Valida que un input tenga todos los campos requeridos.
// ===========================
function validarCamposRequeridos(array $input, array $requeridos): bool {
    foreach ($requeridos as $campo) {
        if (!isset($input[$campo]) || trim($input[$campo]) === '') {
            return false;
        }
    }
    return true;
}

// ===========================
// Valida que un valor sea 0 o 1 (para campos booleanos tipo activo).
// ===========================
function validarBinario($valor): bool {
    return $valor === 0 || $valor === 1 || $valor === '0' || $valor === '1';
}
// ===========================
// Valida si un ID es un entero positivo válido.
// ===========================
function validarId(int $id): bool {
    return $id > 0;
}

// ===========================
// Valida que el estado de noticia esté dentro de valores permitidos.
// Valores válidos: 1, 2, 3
// ===========================
function validarEstadoNoticia(int $estado): bool {
    return in_array($estado, [1, 2, 3], true);
}

// ===========================
// Sanitiza todos los valores string de un array asociativo.
// ===========================
function sanitizarArray(array $data): array {
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = SanitizarEntrada::limpiarCadena($value);
        }
    }
    return $data;
}
?>
