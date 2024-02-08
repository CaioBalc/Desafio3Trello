<?php
require_once 'Conexao.php'; 

class Usuario{
    private $conexaoBanco;

    public function __construct() {
        $this->conexaoBanco = Conexao::conectar(); // Utiliza a conexão única fornecida pela classe Conexao
    }

    public function criaUsuario($dadosUsuario)
    {
        try {
            // Validação básica dos dados do usuário
            if (empty($dadosUsuario['nome_usuario'])) {
                throw new Exception("O nome do usuário é obrigatório.");
            }
            if (empty($dadosUsuario['email'])) {
                throw new Exception("O email do usuário é obrigatório.");
            }
            if (!filter_var($dadosUsuario['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("O email fornecido é inválido.");
            }

            $this->salvaUsuario($dadosUsuario);//--------

            echo "Usuário criado com sucesso.";
        } catch (Exception $e) {
            echo "Erro ao criar usuário: " . $e->getMessage();
        }
    }

    private function salvaUsuario($usuarioData) 
    {
        $sql = "INSERT INTO usuarios (nome_usuario, email) VALUES (?, ?)";
        try 
        {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([

                $usuarioData['nome_usuario'],
                $usuarioData['email']
   
            ]);
          
            echo "Usuario salvo com sucesso.\n";
        } 
        catch (PDOException $e) 
        {
            exit('Erro ao salvar usuario no banco de dados: ' . $e->getMessage());
        }
    }

}