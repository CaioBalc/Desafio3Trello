<?php

class Projeto{
    private $conexaoBanco;

    public function __construct() {
        $this->conexaoBanco = Conexao::conectar(); // Utiliza a conexão única fornecida pela classe Conexao
    }

    private function criaProjeto()
    {
        
    }
    private function salvaProjeto($projetoData) {//função usada dentro da de cima para pegar os dados do json e salvar no formato certo na tabela
        $sql = "INSERT INTO projetos (nome_projeto, descricao_projeto, data_inicio, data_fim) VALUES (?, ?, ?, ?)";
        try 
        {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([

                $projetoData['nome_projeto'],
                $projetoData['descricao_projeto'],
                $projetoData['data_inicio'],
                $projetoData['data_fim']
   
            ]);
          
            echo "Projeto salvo com sucesso.\n";
        } 
        catch (PDOException $e) 
        {
            exit('Erro ao salvar projeto no banco de dados: ' . $e->getMessage());
        }
    }
}