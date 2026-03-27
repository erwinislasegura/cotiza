<?php

function iniciar_sesion_segura(string $nombre): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_name($nombre);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function base_path_url(): string
{
    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $dir = str_replace('\\', '/', dirname($scriptName));

    if ($dir === '/' || $dir === '.') {
        return '';
    }

    // Si corre desde /public/index.php, la base pública real es el padre.
    if (str_ends_with($dir, '/public')) {
        $dir = substr($dir, 0, -7) ?: '';
    }

    return rtrim($dir, '/');
}

function url(string $ruta = '/'): string
{
    $base = base_path_url();
    $ruta = '/' . ltrim($ruta, '/');
    if ($ruta === '/index.php') {
        $ruta = '/';
    }
    return ($base === '' ? '' : $base) . $ruta;
}

function csrf_token(): string
{
    if (!isset($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token()) . '">';
}

function validar_csrf(): void
{
    $token = $_POST['_csrf'] ?? '';
    if (!$token || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Token CSRF inválido.');
    }
}

function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

function usuario_actual(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function obtener_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function tiene_rol(string|array $roles): bool
{
    $usuario = usuario_actual();
    if (!$usuario) {
        return false;
    }
    $roles = (array) $roles;
    return in_array($usuario['rol_codigo'], $roles, true);
}

function empresa_actual_id(): ?int
{
    return usuario_actual()['empresa_id'] ?? null;
}
