<?php
class Conexion
{
    protected $host = 'localhost';
    protected $db = '';
    protected $user = 'code';
    protected $password = '123456';

    public static function Conectar()
    {
        try {
            $con = new PDO('mysql:host=localhost;dbname=news_mag', 'code', '123456');
            $con->exec('set names utf8');
            return $con;
        } catch (Exception $e) {
            return 'Error de conexión: ' . $e->getMessage();
        }
    }
}
