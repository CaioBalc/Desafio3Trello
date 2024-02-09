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

    public function criaTarefa(string $descricao_tarefa, string $data_inicio, string $data_fim, Projeto $projeto) {
        try {
            // Validação básica dos dados da tarefa
            if (empty($descricao_tarefa)) {
                throw new Exception("A descrição da tarefa é obrigatória.");
            }
    
            // Tenta converter as strings de data para objetos DateTime
            $inicio = DateTime::createFromFormat('Y-m-d', $data_inicio);
            $fim = DateTime::createFromFormat('Y-m-d', $data_fim);
    
            // Verifica se as datas são válidas
            if (!$inicio || $inicio->format('Y-m-d') !== $data_inicio || !$fim || $fim->format('Y-m-d') !== $data_fim) {
                throw new Exception("Formato de data inválido. Use o formato yyyy-mm-dd.");
            }
    
            // Validação das datas da tarefa em relação ao projeto
            $projetoInicio = new DateTime($projeto->getDataInicio());
            $projetoFim = new DateTime($projeto->getDataFim());
            if ($inicio < $projetoInicio || $fim > $projetoFim) {
                throw new Exception("As datas da tarefa devem estar dentro do intervalo do projeto.");
            }
    
            // Atribui os dados validados aos atributos da classe
            $this->descricao_tarefa = $descricao_tarefa;
            $this->data_inicio = $data_inicio;
            $this->data_fim = $data_fim;
            $this->projeto = $projeto;
    
            // Salva a tarefa no banco de dados
            echo "Tarefa criada com sucesso.";

            $this->salvaTarefa();
    
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

