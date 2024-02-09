<?php

class Atribuicao {
   private $conexaoBanco;
   private $idAtribuicao; 

   public function __construct() {
       $this->conexaoBanco = Conexao::conectar();
   }

   public function atribuirTarefaAUsuario($id_usuario, $id_tarefa) {
       $sql = "INSERT INTO atribuicoes (id_usuario, id_tarefa) VALUES (?, ?) RETURNING id_atribuicao"; 
       try {
           $stmt = $this->conexaoBanco->prepare($sql);
           $stmt->execute([$id_usuario, $id_tarefa]);
           if ($stmt->rowCount() > 0) {
               $this->idAtribuicao = $stmt->fetch(PDO::FETCH_COLUMN); 
               echo "Tarefa atribuída com sucesso. ID da atribuição: {$this->idAtribuicao}\n";
           }
       } catch (PDOException $e) {
           exit("Erro ao atribuir tarefa ao usuário: " . $e->getMessage());
       }
   }

   public function listarTarefasAtribuidas($id_usuario) {
      $sql = "SELECT 
                  t.id_tarefa, 
                  t.descricao_tarefa, 
                  t.data_inicio, 
                  t.data_fim,
                  p.nome_projeto
              FROM tarefas AS t
              INNER JOIN atribuicoes AS a ON t.id_tarefa = a.id_tarefa
              INNER JOIN projetos AS p ON t.id_projeto = p.id_projeto
              WHERE a.id_usuario = ?";
      try {
          $stmt = $this->conexaoBanco->prepare($sql);
          $stmt->execute([$id_usuario]);
          $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
          if (!empty($tarefas)) {
              echo "Tarefas atribuídas ao usuário ID $id_usuario:\n";
              foreach ($tarefas as $tarefa) {
                  echo "ID Tarefa: {$tarefa['id_tarefa']}, Projeto: {$tarefa['nome_projeto']}, Descrição: {$tarefa['descricao_tarefa']}, Data de Início: {$tarefa['data_inicio']}, Data de Fim: {$tarefa['data_fim']}\n";
              }
          } else {
              echo "Nenhuma tarefa atribuída a este usuário.\n";
          }
      } catch (PDOException $e) {
          exit("Erro ao listar tarefas atribuídas ao usuário: " . $e->getMessage());
      }
  }
  


   public function getIdAtribuicao() {
       return $this->idAtribuicao;
   }
}
