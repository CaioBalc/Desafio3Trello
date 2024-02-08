<?php
require_once 'Conexao.php'; 

class Usuario{
    private $conexaoBanco;

    public function __construct($sessionToken) {
        $this->conexaoBanco = Conexao::conectar(); // Utiliza a conexão única fornecida pela classe Conexao
    }

    private function salvaUsuario($questionData) {//função usada dentro da de cima para pegar os dados do json e salvar no formato certo na tabela
        $sql = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";
        try 
        {
            $stmt = $this->conexaoBanco->prepare($sql);
            $incorrectAnswers = implode(', ', $questionData['incorrect_answers']);
            $stmt->execute([
                $questionData['type'],
                $questionData['category'],
                $questionData['difficulty'],
                $questionData['question'],
                $questionData['correct_answer'],
                $incorrectAnswers
            ]);
          
            echo "Usuario salvo com sucesso.\n";
        } 
        catch (PDOException $e) 
        {
            exit('Erro ao salvar pergunta no banco de dados: ' . $e->getMessage());
        }
    }

}