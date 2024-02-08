<?php
require_once 'Conexao.php';

class Projeto {
    private $conexaoBanco;
    private $nome_projeto;
    private $descricao_projeto;
    private $data_inicio;
    private $data_fim;
    private $id_projeto;

    public function __construct() {
        $this->conexaoBanco = Conexao::conectar(); // Utiliza a conexão única fornecida pela classe Conexao
    }

    public function criaProjeto($nome_projeto, $descricao_projeto, $data_inicio, $data_fim) {
        try {
            // Validação básica dos dados do projeto
            if (empty($nome_projeto)) {
                throw new Exception("O nome do projeto é obrigatório.");
            }

            // Atribui os dados validados aos atributos da classe
            $this->nome_projeto = $nome_projeto;
            $this->descricao_projeto = $descricao_projeto;
            $this->data_inicio = $data_inicio;
            $this->data_fim = $data_fim;

            // Salva o projeto no banco de dados
            $this->salvaProjeto();

            echo "Projeto criado com sucesso.";
        } catch (Exception $e) {
            echo "Erro ao criar projeto: " . $e->getMessage();
        }
    }

    private function salvaProjeto() 
    {
        $sql = "INSERT INTO projetos (nome_projeto, descricao_projeto, data_inicio, data_fim) VALUES (?, ?, ?, ?) RETURNING id_projeto";
        try {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([
                $this->nome_projeto,
                $this->descricao_projeto,
                $this->data_inicio,
                $this->data_fim
            ]);
    
            // Captura o ID do projeto recém-criado
            $this->id_projeto = $stmt->fetch(PDO::FETCH_ASSOC)['id_projeto'];
    
            echo "Projeto salvo com sucesso e ID recuperado: " . $this->id_projeto . "\n";
        } catch (PDOException $e) {
            exit('Erro ao salvar projeto no banco de dados: ' . $e->getMessage());
        }
    }

    public function getIdProjeto() {
        return $this->id_projeto;
    }
    
}
