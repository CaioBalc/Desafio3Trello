<?php


require "Atribuicao.php";
require "Projeto.php";
require "Tarefa.php";
require "Usuario.php";


$usuario1 = new Usuario();
$usuario1->criaUsuario('Nome Usuario','email@teste.com');

$projeto = new Projeto();
$projeto->criaProjeto('Nome projeto', 'Descrição projeto', '2024-02-05', '2024-02-09');


#echo $projeto;
?>