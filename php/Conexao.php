<?php 


class Conexao {
    private static $conexao = null;

    
    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Não pode usar wakeup na classe Conexao");
    }

    public static function conectar() {
        if (self::$conexao === null) {
            try {
                $host = "db"; 
                $dbname = "dadostarefas";
                $user = "usuario";
                $password = "senha";
                
                self::$conexao = new PDO(
                    "pgsql:host=$host;dbname=$dbname",
                    $user,
                    $password
                );

                
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                echo "Erro na conexão com o banco de dados: " . $e->getMessage();
                exit;
            }
        }
        return self::$conexao;
    }
}
