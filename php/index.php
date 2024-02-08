<?php
//sudo chmod -R 755 /home/imply/Área\ de\ Trabalho/desafios/desafios02/desafio2/dados     se por acaso acontecer de novo
//sudo chown -R imply /home/imply/Área\ de\ Trabalho/desafios/desafios02/desafio2/dados
//remover dados

require "Atribuicao.php";
require "Projeto.php";
require "Tarefa.php";
require "Usuario.php";


$usuario1 = new Usuario();
$usuario1->criaUsuario('Nome Usuario','email@teste.com');

$projeto = new Projeto();
$projeto->criaProjeto('Nome projeto', 'Descrição projeto', '2024-02-05', '2024-02-09');

echo "FUNCIONOU!";
#echo $projeto;
?>