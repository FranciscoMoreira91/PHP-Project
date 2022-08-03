<?php

function traduzir($texto)
{
    //Require do array da linguagem Inglesa
    require "en.php";
    //Require da linguagem em questão
    $languagePath = $_SESSION['lang'] . ".php";
    //Obter o array da linguagem seleccionada
    $myArray = require $languagePath;
    //Caso exista o valor que se quer no array, retornar esse valor
    if (isset($myArray[$texto])) {
        return $myArray[$texto];
    } 
    //Caso não exista, 
    else {
        return $pt[$texto];
    }
}
?>