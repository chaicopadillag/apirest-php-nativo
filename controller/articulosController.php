<?php
class ArticulosController
{
    public static function index($cantidad, $desde)
    {
        $articulos = Articulo::index($cantidad, $desde);
        if ($articulos != null) {
            $json = json_encode(
                [
                    'estado'                => 202,
                    'cantidad de registros' => count($articulos),
                    'articulos'             => $articulos,
                ],
                true
            );
            // header("Access-Control-Allow-Origin: *");
            // // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            // // header("Access-Control-Allow-Methods: GET, POST, PUT,DELETE");
            // header('Content-type:application/json');
            echo $json;
        } else {

            $json = json_encode(
                [
                    'estado'                => 404,
                    'cantidad de registros' => count($articulos),
                    'mensaje'               => 'No hay registros que mostrar',
                ],
                true
            );
            echo $json;
        }
    }
    public static function ArticuloCheckTitulo($titulo)
    {
        $article = Articulo::ArticuloCheckTitulo($titulo);
        return $article;
    }
    public static function store($datos)
    {
        if (isset($datos['titulo']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos['titulo'])) {

            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese un titulo válido',
            ], true);
            echo $json;
        } elseif (isset($datos['descripcion']) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $datos['descripcion'])) {

            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese una descripción válida',
            ], true);
            echo $json;
        } elseif (isset($datos['palabras_claves']) && !preg_match('/^[,\\a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $datos['palabras_claves'])) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese palabras claves separados por coma (,)',
            ], true);
            echo $json;
        } elseif (isset($datos['ruta']) && !preg_match('/^[-\\a-z]+$/', $datos['ruta'])) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese una ruta en minusculas separados por guión medio (-)',
            ], true);
            echo $json;
        } elseif (isset($datos['contenido']) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $datos['contenido'])) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese el contenido del articulo válido',
            ], true);
            echo $json;
        } elseif (isset($datos['img']) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-Z ]+$/', $datos['img'])) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese la ruta de una imagen válida',
            ], true);
            echo $json;
        } elseif (self::ArticuloCheckTitulo($datos['titulo']) != null) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'El titulo del articulo ya esta en uso',
            ], true);
            echo $json;
        } else {
            if (Articulo::store($datos) > 0) {
                $json = json_encode([
                    'mensaje' => 'El articulo se ha registrado exitosamente',
                    'datos'   => $datos,
                ], true);
                echo $json;
            } else {
                $json = json_encode(['mensaje' => 'Error al registrar el articulo, intente de nuevo'], true);
                echo $json;
            }
        }
    }
    public static function show($id)
    {
        $article = Articulo::show($id);
        return $article;
    }

    public static function update($datos, $id)
    {
        if (isset($datos['titulo']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos['titulo'])) {

            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese un titulo válido para actualizar',
            ], true);
            echo $json;
        } elseif (isset($datos['descripcion']) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $datos['descripcion'])) {

            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese una descripción válida',
            ], true);
            echo $json;
        } elseif (isset($datos['palabras_claves']) && !preg_match('/^[,\\a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $datos['palabras_claves'])) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese palabras claves separados por coma (,)',
            ], true);
            echo $json;
        } elseif (isset($datos['ruta']) && !preg_match('/^[-\\a-z]+$/', $datos['ruta'])) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese una ruta en minusculas separados por guión medio (-)',
            ], true);
            echo $json;
        } elseif (isset($datos['contenido']) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $datos['contenido'])) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese el contenido del articulo válido',
            ], true);
            echo $json;
        } elseif (isset($datos['img']) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-Z ]+$/', $datos['img'])) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'Ingrese la ruta de una imagen válida',
            ], true);
            echo $json;
        } elseif (self::ArticuloCheckUser($id, $datos['user']) != null) {
            $json = json_encode([
                'estado'  => 404,
                'mensaje' => 'El titulo del articulo ya esta en uso',
            ], true);
            echo $json;
        } else {
            if (Articulo::update($datos, $id) > 0) {
                $json = json_encode([
                    'mensaje'            => 'El articulo se ha actualizado exitosamente',
                    'datos actualizados' => $datos,
                ], true);
                echo $json;
            } else {
                $json = json_encode(['mensaje' => 'Error al actualizar el articulo, intente de nuevo'], true);
                echo $json;
            }
        }
    }
    public static function delete($id, $id_user)
    {
        $resultado = Articulo::delete($id, $id_user);
        if ($resultado > 0) {
            $json = json_encode([
                'mensaje' => 'El articulo se ha eliminado exitosamente',
            ], true);
            echo $json;
        } else {
            $json = json_encode(['mensaje' => 'Error al eliminar el articulo, intente de nuevo'], true);
            echo $json;
        }
    }
    public static function ArticuloCheckUser($id_articulo, $id_user)
    {
        $article = Articulo::ArticuloCheckUser($id_articulo, $id_user);
        return $article;
    }
}
