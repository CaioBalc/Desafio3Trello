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
        $this->conexaoBanco = Conexao::conectar();
    }

    public function criaProjeto($nome_projeto, $descricao_projeto, $data_inicio, $data_fim)
    {
        try {
            // Verifica se os campos estão vazios e lança uma exceção se estiverem
            if (empty($nome_projeto)) {
                throw new Exception("O nome do projeto é obrigatório.");
            }
            if (empty($descricao_projeto)) {
                throw new Exception("A descrição do projeto é obrigatória.");
            }
            if (empty($data_inicio)) {
                throw new Exception("A data de início do projeto é obrigatória.");
            }
            if (empty($data_fim)) {
                throw new Exception("A data de fim do projeto é obrigatória.");
            }
    
            // Verifica se a data de início é anterior à data de fim
            if (new DateTime($data_inicio) > new DateTime($data_fim)) {
                throw new Exception("A data de início deve ser anterior à data de fim.");
            }
    
            $this->nome_projeto = $nome_projeto;
            $this->descricao_projeto = $descricao_projeto;
            $this->data_inicio = $data_inicio;
            $this->data_fim = $data_fim;
    
            $this->salvaProjeto();
    
            echo "Projeto criado com sucesso.";
        } catch (Exception $e) {
            echo "Erro ao criar projeto: " . $e->getMessage();
        }
    }

    private function salvaProjeto() {
        $sql = "INSERT INTO projetos (nome_projeto, descricao_projeto, data_inicio, data_fim) VALUES (?, ?, ?, ?) RETURNING id_projeto";
        try {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([
                $this->nome_projeto,
                $this->descricao_projeto,
                $this->data_inicio,
                $this->data_fim
            ]);

            $this->id_projeto = $stmt->fetch(PDO::FETCH_ASSOC)['id_projeto'];

            echo "Projeto salvo com sucesso e ID recuperado: " . $this->id_projeto . "\n";
        } catch (PDOException $e) {
            exit('Erro ao salvar projeto no banco de dados: ' . $e->getMessage());
        }
    }

    // Método para retornar o ID do projeto
    public function getIdProjeto() {
        return $this->id_projeto;
    }

    // Método para retornar a data de início do projeto
    public function getDataInicio() {
        return $this->data_inicio;
    }

    // Método para retornar a data de fim do projeto
    public function getDataFim() {
        return $this->data_fim;
    }
}
