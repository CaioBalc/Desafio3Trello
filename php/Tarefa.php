<?php
require_once 'Conexao.php';

class Tarefa {
    private $conexaoBanco;
    private $descricao_tarefa;
    private $data_inicio;
    private $data_fim;
    private $id_projeto;
    private $id_tarefa;

    public function __construct() {
        $this->conexaoBanco = Conexao::conectar(); // Utiliza a conexão única fornecida pela classe Conexao
    }

    public function criaTarefa($descricao_tarefa, $data_inicio, $data_fim) {
        try {
            // Validação básica dos dados do tarefa
            if (empty($nome_tarefa)) {
                throw new Exception("O nome da tarefa é obrigatório.");
            }

            // Atribui os dados validados aos atributos da classe
            $this->descricao_tarefa = $descricao_tarefa;
            $this->data_inicio = $data_inicio;
            $this->data_fim = $data_fim;

            // Salva a tarefa no banco de dados
            $this->salvaTarefa();

            echo "Tarefa criado com sucesso.";
        } catch (Exception $e) {
            echo "Erro ao criar tarefa: " . $e->getMessage();
        }
    }

    private function salvaTarefa() 
    {
        $sql = "INSERT INTO tarefas (descricao_tarefa, data_inicio, data_fim) VALUES (?, ?, ?) RETURNING id_tarefa";
        try {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([
                $this->descricao_tarefa,
                $this->data_inicio,
                $this->data_fim
            ]);
    
            // Captura o ID do tarefa recém-criado
            $this->id_tarefa = $stmt->fetch(PDO::FETCH_ASSOC)['id_tarefa'];
    
            echo "Tarefa salvo com sucesso e ID recuperado: " . $this->id_tarefa . "\n";
        } catch (PDOException $e) {
            exit('Erro ao salvar tarefa no banco de dados: ' . $e->getMessage());
        }
    }
}
