<?php
require_once 'db.php';

class Articulo
{
    public static function index($cantidad = null, $desde = null)
    {
        if (!is_null($cantidad) && !is_null($desde)) {
            $sql = "SELECT a.id,a.titulo, a.descripcion, a.palabras_claves, a.ruta, a.contenido, a.img, u.name, u.apellidos FROM articulo AS a INNER JOIN users as u ON u.id=a.id_user LIMIT $desde, $cantidad";
        } else {
            $sql = "SELECT a.id,a.titulo, a.descripcion, a.palabras_claves, a.ruta, a.contenido, a.img, u.name, u.apellidos FROM articulo AS a INNER JOIN users as u ON u.id=a.id_user";
        }
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->execute();
        $artiulos = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $artiulos;
    }

    public static function ArticuloCheckTitulo($titulo)
    {
        $artiulo = null;
        $sql = "SELECT * FROM articulo WHERE titulo=:titulo";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo
        ]);
        $article = $stmt->fetch();
        return $article;
    }
    public static function store($datos)
    {
        $resultado = 0;
        try {
            $sql = "INSERT INTO articulo (id_categoria, titulo, descripcion, palabras_claves, ruta, contenido, img, id_user, created_at, updated_at) VALUES (:id_categoria, :titulo, :descripcion, :palabras_claves, :ruta, :contenido, :img, :id_user, :created_at, :updated_at)";
            $stmt = Conexion::Conectar()->prepare($sql);
            if ($stmt->execute([
                ':id_categoria' => $datos['id_categoria'],
                ':titulo' => $datos['titulo'],
                ':descripcion' => $datos['descripcion'],
                ':palabras_claves' => json_encode(explode(',', $datos['palabras_claves'])),
                ':ruta' => $datos['ruta'],
                ':contenido' => $datos['contenido'],
                ':img' => $datos['img'],
                ':id_user' => $datos['id_user'],
                ':created_at' => $datos['created_at'],
                ':updated_at' => $datos['updated_at'],
            ])) {

                $resultado = 10;
            } else {
                print_r(Conexion::conectar()->errorInfo());
            }
        } catch (\Exception $e) {
            echo "Error en: " . $e->getMessage();
        }
        return $resultado;
    }
    public static function show($id)
    {
        $artiulo = null;
        $sql = "SELECT a.id,a.titulo, a.descripcion, a.palabras_claves, a.ruta, a.contenido, a.img, u.name, u.apellidos FROM articulo AS a INNER JOIN users as u ON u.id=a.id_user WHERE a.id=:id";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        $artiulo = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $artiulo;
    }
    public static function update($datos, $id)
    {
        $resultado = 0;
        try {
            $sql = "UPDATE articulo SET titulo=:titulo,descripcion=:descripcion,palabras_claves=:palabras_claves,ruta=:ruta,contenido=:contenido,img=:img,updated_at=:updated_at WHERE id=:id and id_user=:id_user";
            $stmt = Conexion::Conectar()->prepare($sql);
            if ($stmt->execute([
                ':id' => $id,
                ':titulo' => $datos['titulo'],
                ':descripcion' => $datos['descripcion'],
                ':palabras_claves' => json_encode(explode(',', $datos['palabras_claves'])),
                ':ruta' => $datos['ruta'],
                ':contenido' => $datos['contenido'],
                ':img' => $datos['img'],
                ':updated_at' => $datos['updated_at'],
                ':id_user' => $datos['id_user']
            ])) {
                $resultado = 10;
            } else {
                print_r(Conexion::conectar()->errorInfo());
            }
        } catch (\Exception $e) {
            echo "Error en: " . $e->getMessage();
        }
        return $resultado;
    }
    public  static function ArticuloCheckUser($id_article, $id_user)
    {
        $artiulo = null;
        $sql = "SELECT * FROM articulo WHERE id=:id AND id_user=:id_user";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->execute([
            ':id' => $id_article,
            'id_user' => $id_user
        ]);
        $artiulo = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $artiulo;
    }
    public static function delete($id, $id_user)
    {
        $resultado = 0;
        try {
            $sql = "DELETE FROM articulo WHERE id=:id AND id_user=:id_user";
            $stmt = Conexion::Conectar()->prepare($sql);
            if ($stmt->execute([
                ':id' => $id,
                ':id_user' => $id_user
            ])) {
                $resultado = 10;
            } else {
                echo (Conexion::conectar()->errorInfo());
            }
        } catch (\Exception $e) {
            echo "Error en: " . $e->getMessage();
        }
        return $resultado;
    }
}
