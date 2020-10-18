<?php

class UsersController
{
    public static function AuthCheck($auth)
    {
        $token = "Basic " . base64_encode($auth['user'] . ":" . $auth['pass']);
        $auth_check = User::AuthCheck($token);

        return $auth_check;
    }
    public static function store($datos)
    {
        if (isset($datos['nombre']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos['nombre'])) {

            $json = json_encode([
                'estado' => 404,
                'mensaje' => 'Ingrese un nombre válido'
            ], true);
            echo $json;
        } elseif (isset($datos['apellidos']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos['apellidos'])) {

            $json = json_encode([
                'estado' => 404,
                'mensaje' => 'Ingrese un apellido válido'
            ], true);
            echo $json;
        } elseif (isset($datos['correo']) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $datos['correo'])) {

            $json = json_encode([
                'estado' => 404,
                'mensaje' => 'Ingrese un correo válido'
            ], true);
            echo $json;
        } elseif (isset($datos['contrasenia']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ 0-9]+$/', $datos['contrasenia'])) {

            $json = json_encode([
                'estado' => 404,
                'mensaje' => 'Ingrese una contraseña válido, sin caracteres especiales'
            ], true);
            echo $json;
        } elseif (User::ValidarCorreo($datos['correo']) != null) {
            $json = json_encode([
                'estado' => 404,
                'mensaje' => 'El correo ya esta en uso'
            ], true);
            echo $json;
        } else {

            $datos['id_cliente'] = str_replace('$', 'R', crypt($datos['nombre'] . $datos['apellidos'] . $datos['correo'], '$2a$07$kjk8uh8258fdskEOIEN2568ffsEH$'));

            $datos['secret_cliente'] = str_replace('$', 'T', crypt($datos['correo'] . $datos['nombre'] . $datos['apellido'], '$2a$07$kjk8uh8258fdskEOIEN2568ffsEH$'));

            $datos['token'] = 'Basic ' . base64_encode($datos['id_cliente'] . ':' . $datos['secret_cliente']);

            $datos['created_at'] = date('Y-m-d h:i:s');
            $datos['updated_at'] = date('Y-m-d h:i:s');

            if (User::store($datos) > 0) {
                $json = json_encode([
                    'estado' => 202,
                    'mensaje' => 'Se ha registrado exitosamente, guarde tus credenciales',
                    'credenciales' => [
                        'id_cliente' =>  $datos['id_cliente'],
                        'secret_cliente' => $datos['secret_cliente'],
                    ]

                ], true);

                echo $json;
            } else {
                $json = json_encode([
                    'estado' => 404,
                    'mensaje' => 'Error al registrarse, intente de nuevo',
                ], true);

                echo $json;
            }
        }
    }
}
