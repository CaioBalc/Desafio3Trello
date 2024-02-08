<?php


require "Atribuicao.php";
require "Projeto.php";
require "Tarefa.php";
require "Usuario.php";


$usuario1 = new Usuario();
$usuario1->criaUsuario('Nome Usuario','email@teste.com');

$projeto = new Projeto();
$projeto->criaProjeto('Nome projeto', 'Descrição projeto', '2024-02-05', '2024-02-09');

$tarefa = new Tarefa();
$id_projeto = $projeto->getIdProjeto(); // Recupera o id_projeto do projeto criado
$tarefa->criaTarefa('Descrição tarefa', '2024-02-05', '2024-02-09', $id_projeto); // Passa o id_projeto para a função criaTarefa

?>