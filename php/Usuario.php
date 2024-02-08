<?php
require_once 'Conexao.php';

class Usuario {
    private $conexaoBanco;
    private $nome;
    private $email;
    private $idUsuario;

    public function __construct() {
        $this->conexaoBanco = Conexao::conectar(); // Utiliza a conexão única fornecida pela classe Conexao
    }

    public function criaUsuario($nome, $email) {
        try {
            // Validação básica dos dados do usuário
            if (empty($nome)) {
                throw new Exception("O nome do usuário é obrigatório.");
            }
            if (empty($email)) {
                throw new Exception("O email do usuário é obrigatório.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("O email fornecido é inválido.");
            }

            // Atribui os dados validados aos atributos da classe
            $this->nome = $nome;
            $this->email = $email;

            // Salva o usuário no banco de dados
            $this->salvaUsuario();

            echo "Usuário criado com sucesso.";
        } catch (Exception $e) {
            echo "Erro ao criar usuário: " . $e->getMessage();
        }
    }

    private function salvaUsuario() {
        $sql = "INSERT INTO usuarios (nome_usuario, email) VALUES (?, ?)";
        try {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([
                $this->nome,
                $this->email
            ]);

            $this->idUsuario = $stmt->fetch(PDO::FETCH_ASSOC)['id_usuario'];//pega id gerado no tabela 

            echo "Usuário salvo com sucesso.\n";
        } catch (PDOException $e) {
            exit('Erro ao salvar usuário no banco de dados: ' . $e->getMessage());
        }
    }
}
