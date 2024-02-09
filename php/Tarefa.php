<?php
require_once 'Conexao.php';

class Tarefa {
    private $conexaoBanco;
    private $descricao_tarefa;
    private $data_inicio;
    private $data_fim;
    private $projeto; // Agora este é um objeto Projeto
    private $id_tarefa;

    public function __construct() {
        $this->conexaoBanco = Conexao::conectar();
    }

    public function criaTarefa($descricao_tarefa, $data_inicio, $data_fim, Projeto $projeto) {
        try {
            // Validação básica dos dados da tarefa
            if (empty($descricao_tarefa)) {
                throw new Exception("A descrição da tarefa é obrigatória.");
            }

            // Validação das datas da tarefa em relação ao projeto
            if ($data_inicio < $projeto->getDataInicio() || $data_fim > $projeto->getDataFim()) {
                throw new Exception("As datas da tarefa devem estar dentro do intervalo do projeto.");
            }

            // Atribui os dados validados aos atributos da classe
            $this->descricao_tarefa = $descricao_tarefa;
            $this->data_inicio = $data_inicio;
            $this->data_fim = $data_fim;
            $this->projeto = $projeto; // Armazena o objeto Projeto

            // Salva a tarefa no banco de dados
            $this->salvaTarefa();

            echo "Tarefa criada com sucesso.";
        } catch (Exception $e) {
            echo "Erro ao criar tarefa: " . $e->getMessage();
        }
    }

    private function salvaTarefa() {
        $sql = "INSERT INTO tarefas (descricao_tarefa, data_inicio, data_fim, id_projeto) VALUES (?, ?, ?, ?) RETURNING id_tarefa";
        try {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([
                $this->descricao_tarefa,
                $this->data_inicio,
                $this->data_fim,
                $this->projeto->getIdProjeto() // Utiliza o ID do projeto a partir do objeto Projeto
            ]);
    
            // Captura o ID da tarefa recém-criada
            $this->id_tarefa = $stmt->fetch(PDO::FETCH_ASSOC)['id_tarefa'];
    
            echo "Tarefa salva com sucesso e ID recuperado: " . $this->id_tarefa . "\n";
        } catch (PDOException $e) {
            exit('Erro ao salvar tarefa no banco de dados: ' . $e->getMessage());
        }
    }
}

