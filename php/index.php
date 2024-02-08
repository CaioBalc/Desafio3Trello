<?php

require "Atribuicao.php";
require "Projeto.php";
require "Tarefa.php";
require "Usuario.php";


$usuario1 = new Usuario();
$usuario1->criaUsuario($dadosUsuario1);

$projeto = new Projeto();
$projeto->criaProjeto($nome_projeto, $descricao_projeto, $data_inicio, $data_fim);
?>