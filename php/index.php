<?php


require "Atribuicao.php";
require "Projeto.php";
require "Tarefa.php";
require "Usuario.php";


$usuario = new Usuario();
$usuario->criaUsuario('Nome Usuario','email@teste.com');

$projeto = new Projeto();
$projeto->criaProjeto('Nome projeto', 'Descrição projeto', '2024-02-05', '2024-02-09');

$tarefa = new Tarefa();
$tarefa->criaTarefa('Descrição tarefa', '2024-02-05', '2024-02-09', $projeto); 

$tarefa2 = new Tarefa();
$tarefa2->criaTarefa('Descrição tarefa dois', '2024-02-06', '2024-02-08', $projeto);


$atribuicao = new Atribuicao();
$atribuicao->atribuirTarefaAUsuario($usuario->getIdUsuario(),$tarefa->getIdTarefa());
$atribuicao->atribuirTarefaAUsuario($usuario->getIdUsuario(),$tarefa2->getIdTarefa());

$atribuicao->listarTarefasAtribuidas($usuario->getIdUsuario());
?>