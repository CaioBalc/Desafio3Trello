<?php

require "Atribuicao.php";
require "Projeto.php";
require "Tarefa.php";
require "Usuario.php";


$usuario1 = new Usuario();
$usuario1->criaUsuario('Nome Usuario','email@teste.com');

$projeto = new Projeto();
$projeto->criaProjeto($nome_projeto, $descricao_projeto, $data_inicio, $data_fim);
?>