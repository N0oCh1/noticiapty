<?php
require_once "../class/C_usuario.php";

// ===========================
// Obtiene el rol del usuario por ID usando la clase Usuario.
// ===========================
function obtenerRolPorId(int $id_usuario): ?string {
    $usuario = new Usuario();
    return $usuario->obtenerRolPorId($id_usuario);
}


// ===========================
// Valida si el usuario tiene un rol general permitido.
//  Roles permitidos: admin, periodista, lglobal (puedes ajustar aquí).
// ===========================
function validarRolGeneral(int $id_usuario): bool {
    $rol = obtenerRolPorId($id_usuario);
    if ($rol === null) return false;

    $rolesValidos = ['admin', 'publicador', 'lglobal'];
    return in_array($rol, $rolesValidos);
}

// ===========================
// Valida si el usuario es administrador.
// ===========================
function validarRolAdmin(int $id_usuario): bool {
    $rol = obtenerRolPorId($id_usuario);
    return $rol === 'admin';
}

// ===========================
// Valida si el usuario es periodista.
// ===========================
function validarRolPeriodista(int $id_usuario): bool {
    $rol = obtenerRolPorId($id_usuario);
    return $rol === 'publicador';
}
