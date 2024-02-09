<?php
require_once 'Conexao.php';

class Tarefa {
    private $conexaoBanco;
    private $descricao_tarefa;
    private $data_inicio;
    private $data_fim;
    private $projeto; // Agora este é um objeto Projeto
    private $id_tarefa;
    private $id_projeto;

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
            $this->id_projeto = $projeto->getIdProjeto();
    
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

    public function editaTarefa(string $descricao_tarefa, string $data_inicio, string $data_fim, Projeto $projeto) {
        try {
            // Verifica se a descrição está vazia
            if (empty($descricao_tarefa)) {
                throw new Exception("A descrição da tarefa é obrigatória.");
            }
    
            // Converte e valida as datas
            $inicio = DateTime::createFromFormat('Y-m-d', $data_inicio);
            $fim = DateTime::createFromFormat('Y-m-d', $data_fim);
            if (!$inicio || $inicio->format('Y-m-d') !== $data_inicio || !$fim || $fim->format('Y-m-d') !== $data_fim) {
                throw new Exception("Formato de data inválido. Use o formato yyyy-mm-dd.");
            }
    
            // Verifica se as datas da tarefa estão dentro do intervalo do projeto
            $projetoInicio = new DateTime($projeto->getDataInicio());
            $projetoFim = new DateTime($projeto->getDataFim());
            if ($inicio < $projetoInicio || $fim > $projetoFim) {
                throw new Exception("As datas da tarefa devem estar dentro do intervalo do projeto.");
            }
    
            // Prepara a query SQL para atualização
            $sql = "UPDATE tarefas SET descricao_tarefa = ?, data_inicio = ?, data_fim = ?, id_projeto = ? WHERE id_tarefa = ?";
            $stmt = $this->conexaoBanco->prepare($sql);
            
            // Executa a query
            $stmt->execute([
                $descricao_tarefa,
                $data_inicio,
                $data_fim,
                $projeto->getIdProjeto(),
                $this->id_tarefa
            ]);
    
            // Verifica se a atualização foi bem-sucedida
            if ($stmt->rowCount() > 0) {
                echo "Tarefa atualizada com sucesso.";
            } else {
                throw new Exception("Nenhuma tarefa foi atualizada. Verifique se o ID da tarefa está correto.");
            }
    
        } catch (Exception $e) {
            echo "Erro ao atualizar tarefa: " . $e->getMessage();
        }
    }

    public function pegaTarefaDoBanco($id) {
        try {
            $sql = "SELECT id_tarefa, descricao_tarefa, data_inicio, data_fim, id_projeto FROM tarefas WHERE id_tarefa = ?";
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([$id]);
    
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($resultado) {
                $this->id_tarefa = $resultado['id_tarefa'];
                $this->descricao_tarefa = $resultado['descricao_tarefa'];
                $this->data_inicio = $resultado['data_inicio'];
                $this->data_fim = $resultado['data_fim'];
                $this->id_projeto = $resultado['id_projeto']; // Atribui apenas o ID do projeto
    
                echo "Tarefa recuperada com sucesso: " . $this->descricao_tarefa . "\n";
            } else {
                throw new Exception("Tarefa não encontrada.");
            }
        } catch (PDOException $e) {
            exit('Erro ao recuperar tarefa do banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function deletaTarefa() {
        try {
            // Certifica-se de que o id_tarefa está definido
            if (empty($this->id_tarefa)) {
                throw new Exception("ID da tarefa não está definido.");
            }
    
            // Prepara a query SQL para deletar
            $sql = "DELETE FROM tarefas WHERE id_tarefa = ?";
            $stmt = $this->conexaoBanco->prepare($sql);
    
            // Executa a query usando o id_tarefa da instância
            $stmt->execute([$this->id_tarefa]);
    
            // Verifica se a deleção foi bem-sucedida
            if ($stmt->rowCount() > 0) {
                echo "Tarefa deletada com sucesso.";
            } else {
                throw new Exception("Nenhuma tarefa foi deletada. Verifique se o ID da tarefa está correto.");
            }
    
        } catch (PDOException $e) {
            exit('Erro ao deletar tarefa do banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            echo "Erro ao deletar tarefa: " . $e->getMessage();
        }
    }

    public function getDescricaoTarefa() {
        return $this->descricao_tarefa;
    }

    public function getDataInicio() {
        return $this->data_inicio;
    }

    public function getDataFim() {
        return $this->data_fim;
    }

    public function getIdTarefa() {
        return $this->id_tarefa;
    }

    public function getIdProjeto() {
        return $this->id_projeto;
    }
    
    
    
}

