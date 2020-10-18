<?php
$rutas = array_filter(explode('/', $_SERVER['REQUEST_URI']));
$auth  = [
    'user' => $_SERVER['PHP_AUTH_USER'] ?? 'null',
    'pass' => $_SERVER['PHP_AUTH_PW'] ?? 'null',
];

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $pagina   = (int) $_GET['page'] ?? 0;
    $cantidad = 10;
    $desde    = ($pagina - 1) * $cantidad;
    if (UsersController::AuthCheck($auth) != null) {
        ArticulosController::index($cantidad, $desde);
    } else {
        $json = json_encode([
            'status'  => 404,
            'mensaje' => 'No esta Autorizado par ver los registros',
        ], true);
        echo $json;
    }
    return;
}

switch ($rutas[1]) {
    case 'registro':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'nombre'      => $_POST['nombre'],
                'apellidos'   => $_POST['apellidos'],
                'correo'      => $_POST['correo'],
                'contrasenia' => $_POST['contrasenia'],
            ];
            UsersController::store($datos);
        } else {
            $json = json_encode([
                'status'  => 404,
                'mensaje' => 'No encontrado',
            ], true);
            echo $json;
        }

        break;
    case 'articulos':
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            if (UsersController::AuthCheck($auth) != null) {
                if (count($rutas) == 2 && is_numeric($rutas[2])) {
                    $id      = (int) $rutas[2];
                    $article = ArticulosController::show($id);
                    if ($article != null) {
                        $json = json_encode([
                            'status'   => 202,
                            'mensaje'  => 'Articulo encontrado',
                            'articulo' => $article,
                        ], true);
                        echo $json;
                    } else {
                        $json = json_encode([
                            'status'  => 404,
                            'mensaje' => 'Articulo no encontrado',
                        ], true);
                        echo $json;
                    }
                } elseif (count($rutas) == 1) {
                    ArticulosController::index(null, null);
                } else {
                    $json = json_encode([
                        'status'  => 404,
                        'mensaje' => 'Servicio no encontrado',
                    ], true);
                    echo $json;
                }
            } else {
                $json = json_encode([
                    'status'  => 404,
                    'mensaje' => 'No esta Autorizado par ver los registros',
                ], true);
                echo $json;
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $user = UsersController::AuthCheck($auth);
            if ($user != null) {
                $datos = [
                    'id_categoria'    => 1,
                    'titulo'          => $_POST['titulo'] ?? '',
                    'descripcion'     => $_POST['descripcion'] ?? '',
                    'palabras_claves' => $_POST['palabras_claves'],
                    'ruta'            => $_POST['ruta'] ?? '',
                    'contenido'       => $_POST['contenido'] ?? '',
                    'img'             => $_POST['img'] ?? '',
                    'id_user'         => $user['id'],
                    'created_at'      => date('Y-m-d h:i:s'),
                    'updated_at'      => date('Y-m-d h:i:s'),
                ];

                ArticulosController::store($datos);
            } else {
                $json = json_encode([
                    'status'  => 404,
                    'mensaje' => 'No esta Autorizado para crear registro',
                ], true);
                echo $json;
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $user = UsersController::AuthCheck($auth);
            if ($user != null) {
                $datos_put = [];
                parse_str(file_get_contents('php://input'), $datos_put);
                $datos = [
                    'titulo'          => $datos_put['titulo'] ?? '',
                    'descripcion'     => $datos_put['descripcion'] ?? '',
                    'palabras_claves' => $datos_put['palabras_claves'],
                    'ruta'            => $datos_put['ruta'] ?? '',
                    'contenido'       => $datos_put['contenido'] ?? '',
                    'img'             => $datos_put['img'] ?? '',
                    'id_user'         => $user['id'],
                    'updated_at'      => date('Y-m-d h:i:s'),
                ];
                $id = (int) $rutas[2];
                if (ArticulosController::ArticuloCheckUser($id, $user['id']) != null) {
                    ArticulosController::update($datos, $id);
                } else {
                    $json = json_encode([
                        'status'  => 403,
                        'mensaje' => 'No tiene los derechos del articulo para actualizar',
                    ], true);
                    echo $json;
                }
            } else {
                $json = json_encode([
                    'status'  => 403,
                    'mensaje' => 'No esta Autorizado para actualizar el registro',
                ], true);
                echo $json;
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $user = UsersController::AuthCheck($auth);
            if ($user != null) {
                $id = (int) $rutas[2];
                if (ArticulosController::ArticuloCheckUser($id, $user['id']) != null) {
                    ArticulosController::delete($id, $user['id']);
                } else {
                    $json = json_encode([
                        'status'  => 403,
                        'mensaje' => 'No tiene los derechos del articulo para eliminar',
                    ], true);
                    echo $json;
                }
            } else {
                $json = json_encode([
                    'status'  => 404,
                    'mensaje' => 'No esta Autorizado para realizar esta acción',
                ], true);
                echo $json;
            }
        } else {
            $json = json_encode([
                'status'  => 404,
                'mensaje' => 'No encontrado',
            ], true);
            echo $json;
        }
        break;
    default:
        $json = json_encode([
            'status'  => 404,
            'mensaje' => 'No encontrado',
        ], true);
        echo $json;
        break;
}
