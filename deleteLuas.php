<?php
//Constantes a ser usadas
require_once($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/config.php');

//Conectar à BD
$connect = mysqli_connect(database_host, database_user, database_password, database_name); //Conectar à Base de Dados
//Caso o botão de apagar seja premido
if (isset($_POST["deleteluas"])) {
    $luasID = $_GET["id"];
    //Query a ser executada
    $query = "UPDATE luas set Visible=0 WHERE IDLua=?";
    //Prepared statement
    $stmt = $connect->prepare($query);
    //Binding
    $stmt->bind_param('i', $luasID);
    //Executar a prepared statement
    if ($stmt->execute()) {
        //Criar a string que contém o script do alert de sucesso
        $str = "<script language='javascript'>" . "alert('" . traduzir("LuasDeletedSuccessfully") . "');";
        $str2 = "window.location.href = '$PATH/Views/Sistemas/Sistema.php?id=" . $_SESSION['systemID'] . "';</script>";
        $str3 = $str . $str2;
        //Mostrar a string
        echo $str3;
    } else {
        //Criar a string que contém o script do alert de insucesso
        $str = "<script language='javascript'>" . "alert('" . traduzir("LuasNotDeleted") . "');";
        $str2 = "window.location.href = '$PATH/Views/Sistemas/Sistema.php?id=" . $_SESSION['systemID'] . "';</script>";
        $str3 = $str . $str2;
        //Mostrar a string
        echo $str3;
    }
}
