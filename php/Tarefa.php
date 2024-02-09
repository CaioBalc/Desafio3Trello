<?php
require_once 'Conexao.php';

class Tarefa {
    private $conexaoBanco;
    private $descricao_tarefa;
    private $data_inicio;
    private $data_fim;
    private $projeto; 
    private $id_tarefa;
    private $id_projeto;

    public function __construct() {
        $this->conexaoBanco = Conexao::conectar();
    }

    public function criaTarefa(string $descricao_tarefa, string $data_inicio, string $data_fim, Projeto $projeto) {
        try {
            
            if (empty($descricao_tarefa)) {
                throw new Exception("A descrição da tarefa é obrigatória.");
            }
    
            
            $inicio = DateTime::createFromFormat('Y-m-d', $data_inicio)->setTime(0, 0, 0);
            $fim = DateTime::createFromFormat('Y-m-d', $data_fim)->setTime(0, 0, 0);
    
            if (!$inicio || $inicio->format('Y-m-d') !== $data_inicio || !$fim || $fim->format('Y-m-d') !== $data_fim) {
                throw new Exception("Formato de data inválido. Use o formato yyyy-mm-dd.");
            }
    
            
            $projetoInicio = new DateTime($projeto->getDataInicio());
            $projetoFim = new DateTime($projeto->getDataFim());

            //echo "\n\nprojeto inicio: ";var_dump($projetoInicio);
            //echo "\nprojeto fim: ";var_dump($projetoFim);
            //echo "\ninicio: "; var_dump($inicio);
            //echo "\ninicio: ";var_dump($fim);

            if ($inicio < $projetoInicio || $fim > $projetoFim) {
                throw new Exception("As datas da tarefa devem estar dentro do intervalo do projeto.");
            }
    
            
            $this->descricao_tarefa = $descricao_tarefa;
            $this->data_inicio = $data_inicio;
            $this->data_fim = $data_fim;
            $this->projeto = $projeto;
            $this->id_projeto = $projeto->getIdProjeto();
    
            
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
                $this->projeto->getIdProjeto() 
            ]);
    
            
            $this->id_tarefa = $stmt->fetch(PDO::FETCH_ASSOC)['id_tarefa'];
    
            echo "Tarefa salva com sucesso e ID recuperado: " . $this->id_tarefa . "\n";
        } catch (PDOException $e) {
            exit('Erro ao salvar tarefa no banco de dados: ' . $e->getMessage());
        }
    }

    public function editaTarefa(string $descricao_tarefa, string $data_inicio, string $data_fim, Projeto $projeto) {
        try {
            
            if (empty($descricao_tarefa)) {
                throw new Exception("A descrição da tarefa é obrigatória.");
            }
    
            
            $inicio = DateTime::createFromFormat('Y-m-d', $data_inicio)->setTime(0, 0, 0);
            $fim = DateTime::createFromFormat('Y-m-d', $data_fim)->setTime(0, 0, 0);
            if (!$inicio || $inicio->format('Y-m-d') !== $data_inicio || !$fim || $fim->format('Y-m-d') !== $data_fim) {
                throw new Exception("Formato de data inválido. Use o formato yyyy-mm-dd.");
            }
    
           
            $projetoInicio = new DateTime($projeto->getDataInicio());
            $projetoFim = new DateTime($projeto->getDataFim());
            if ($inicio < $projetoInicio || $fim > $projetoFim) {
                throw new Exception("As datas da tarefa devem estar dentro do intervalo do projeto.");
            }
    
            
            $sql = "UPDATE tarefas SET descricao_tarefa = ?, data_inicio = ?, data_fim = ?, id_projeto = ? WHERE id_tarefa = ?";
            $stmt = $this->conexaoBanco->prepare($sql);
            
            
            $stmt->execute([
                $descricao_tarefa,
                $data_inicio,
                $data_fim,
                $projeto->getIdProjeto(),
                $this->id_tarefa
            ]);
    
            
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
                $this->id_projeto = $resultado['id_projeto']; 
    
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
            
            if (empty($this->id_tarefa)) {
                throw new Exception("ID da tarefa não está definido.");
            }
    
            
            $sql = "DELETE FROM tarefas WHERE id_tarefa = ?";
            $stmt = $this->conexaoBanco->prepare($sql);
    
            
            $stmt->execute([$this->id_tarefa]);
    
            
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

