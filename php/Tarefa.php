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

    public function criaTarefa($tarefaData) {
        try {
            // Validação básica dos dados da tarefa
            if (empty($tarefaData->descricao_tarefa)) {
                throw new Exception("A descrição da tarefa é obrigatória.");
            }

            // Atribui os dados validados aos atributos da classe
            $this->descricao_tarefa = $tarefaData->descricao_tarefa;
            $this->data_inicio = $tarefaData->data_inicio;
            $this->data_fim = $tarefaData->data_fim;
            $this->id_projeto = $tarefaData->id_projeto;

            // Salva a tarefa no banco de dados
            $this->salvaTarefa();

            echo "Tarefa criada com sucesso.";
        } catch (Exception $e) {
            echo "Erro ao criar tarefa: " . $e->getMessage();
        }
    }

    private function salvaTarefa() 
    {
        $sql = "INSERT INTO tarefas (descricao_tarefa, id_projeto, data_inicio, data_fim) VALUES (?, ?, ?, ?) RETURNING id_tarefa";
        try {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([
                $this->descricao_tarefa,
                $this->id_projeto,
                $this->data_inicio,
                $this->data_fim
            ]);
    
            // Captura o ID da tarefa recém-criada
            $this->id_tarefa = $stmt->fetch(PDO::FETCH_ASSOC)['id_tarefa'];
    
            echo "Tarefa salva com sucesso e ID recuperado: " . $this->id_tarefa . "\n";
        } catch (PDOException $e) {
            exit('Erro ao salvar tarefa no banco de dados: ' . $e->getMessage());
        }
    }
}
