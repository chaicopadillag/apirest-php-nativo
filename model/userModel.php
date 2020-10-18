<?php
require_once 'db.php';
class User
{
    public static function AuthCheck($token)
    {
        $sql = "SELECT * FROM users WHERE token=:token";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->execute([
            ':token' => $token
        ]);
        $auth = $stmt->fetch();
        return $auth;
    }
    public static function ValidarCorreo($correo)
    {
        $user = null;

        $sql = "SELECT * FROM users WHERE email=:correo";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        $user = $stmt->fetch();
        return $user;
    }

    public static function store($datos)
    {
        $resultado = 0;
        try {
            $sql = "INSERT INTO users (name, apellidos, email, password, id_cliente, secret_cliente, token, created_at, updated_at) VALUES (:name, :apellidos, :email, :password, :id_cliente, :secret_cliente, :token, :created_at, :updated_at)";
            $stmt = Conexion::Conectar()->prepare($sql);
            $stmt->execute([
                ':name' => $datos['nombre'],
                ':apellidos' => $datos['apellidos'],
                ':email' => $datos['correo'],
                ':password' => $datos['contrasenia'],
                ':id_cliente' => $datos['id_cliente'],
                ':secret_cliente' => $datos['secret_cliente'],
                ':token' => $datos['token'],
                ':created_at' => $datos['created_at'],
                ':updated_at' => $datos['updated_at'],
            ]);
            $resultado = 10;
        } catch (\Exception $e) {
            echo "Error en: " . $e->getMessage();
        }
        return $resultado;
    }
}
