<?php

require "Atribuicao.php";
require "Projeto.php";
require "Tarefa.php";
require "Usuario.php";

$dadosUsuario1 = [
    'nome_usuario' => 'NomeDoUsuario',
    'email' => 'email@exemplo.com'
];
$usuario1 = new Usuario();
$usuario1->criaUsuario($dadosUsuario1);

$projeto = new Projeto();
$projeto->criaProjeto($nome_projeto, $descricao_projeto, $data_inicio, $data_fim);


?>