<?php
require_once 'Conexao.php'; 

class Usuario{
    private $conexaoBanco;

    public function __construct() {
        $this->conexaoBanco = Conexao::conectar(); // Utiliza a conexão única fornecida pela classe Conexao
    }

    public function criaUsuario()
    {

        $this->salvaUsuario($dadosUsuario);
    }
    private function salvaUsuario($usuarioData) {//função usada dentro da de cima para pegar os dados do json e salvar no formato certo na tabela
        $sql = "INSERT INTO usuarios (nome_usuario, email) VALUES (?, ?)";
        try 
        {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([

                $usuarioData['nome_usuario'],
                $usuarioData['email'],
   
            ]);
          
            echo "Usuario salvo com sucesso.\n";
        } 
        catch (PDOException $e) 
        {
            exit('Erro ao salvar usuario no banco de dados: ' . $e->getMessage());
        }
    }

}