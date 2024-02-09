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

    public function criaProjeto(string $nome_projeto, string $descricao_projeto, string $data_inicio, string $data_fim) 
    {
        try {
            
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
    
           
            $inicio = DateTime::createFromFormat('Y-m-d', $data_inicio);
            $fim = DateTime::createFromFormat('Y-m-d', $data_fim);
    
            if (!$inicio || !$fim) {
                throw new Exception("As datas devem estar no formato AAAA-MM-DD.");
            }
    
            
            if ($inicio > $fim) {
                throw new Exception("A data de início deve ser antes da data de fim.");
            }
    
            $this->nome_projeto = $nome_projeto;
            $this->descricao_projeto = $descricao_projeto;
            $this->data_inicio = $data_inicio;
            $this->data_fim = $data_fim;

            echo "Projeto criado com sucesso.\n";

            $this->salvaProjeto();
    
            
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

    public function getIdProjeto() {
        return $this->id_projeto;
    }

    public function getDataInicio() {
        return $this->data_inicio;
    }

    public function getDataFim() {
        return $this->data_fim;
    }

    public function getNomeProjeto() {
        return $this->nome_projeto;
    }

    public function getDescricaoProjeto() {
        return $this->descricao_projeto;
    }
}
