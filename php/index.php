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

?>