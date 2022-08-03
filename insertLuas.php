<?php

require_once($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Languages/tradutor.php');
/* CASO SE MUDE DE SERVIDOR, MUDAR AQUI O LINK */
$PATH = "http://localhost/PlanetarySystemTPSI/SolarSystemGO-2020";

$connect = mysqli_connect(database_host, database_user, database_password, database_name); //Conectar à Base de Dados

if (isset($_POST["send_luas"])) {
    //Nome do luaa
    $LuasName = $_POST["luas_name"];
    //Descrição do lua
    $LuasDescription = $_POST["luas_desc"];
    //Tamanho do lua
    $LuasSize = $_POST["luas_size"];
    //Velocidade do moona
    $LuasVelocity = $_POST["luas_velocity"];
    //Velocidade de rotação do lua
    $LuasRotationVelocity = $_POST["luas_rotation_velocity"];
    //
    $focus1Elipse = 0;
    //
    $focus2Elipse = 0;
    //
    $focus1Orbit = $_POST['luas_orbit_focus_2'];
    //
    $focus2Orbit = $_POST['luas_orbit_focus_1'];
    //Imagem da lua
    $LuasImage = addslashes(file_get_contents($_FILES["LuasImageInsert"]["tmp_name"]));
    //Imagem da textura
    $LuasSkin = addslashes(file_get_contents($_FILES["LuasSkinInsert"]["tmp_name"]));
    //Query a ser executada
    $query = "INSERT INTO luas(Nome, Descricao, Tamanho, Velocidade, VelocidadeRotacao, Imagem, Skin, Foco1elipse, Foco2elipse, DistanciaFoco1, DistanciaFoco2,IDFK)
    VALUES (?,?,?,?,?,'$LuasImage', '$LuasSkin',?,?,?,?,?)";
    
    //Prepared statement
    $stmt = $connect->prepare($query);
    //Binding
    $stmt->bind_param('ssdidddddi', $LuasName, $LuasDescription, $LuasSize, $LuasVelocity, $LuasRotationVelocity, $focus1Elipse, $focus2Elipse, $focus1Orbit, $focus2Orbit, $_GET["planeta"]);
    //Executar a prepared statement
    if ($stmt->execute()) {
        //Criar a string que contém o script do alert de sucesso
        $str = "<script language='javascript'>" . "alert('" . traduzir("luasCreatedSuccessfully") . "');";
        $str2 = "window.location.href = '$PATH/Views/Sistemas/Sistema.php?id=" . $_SESSION['systemID'] . "';</script>";
        $str3 = $str . $str2;
        //Mostrar a string
        echo $str3;
    } else {
        //Criar a string que contém o script do alert de insucesso
        $str = "<script language='javascript'>" . "alert('" .$stmt->error." ". traduzir("luasNotCreated") . "');";
        $str2 = "window.location.href = '$PATH/Views/Sistemas/Sistema.php?id=" . $_SESSION['systemID'] . "';</script>";
        $str3 = $str . $str2;
        //Mostrar a string
        echo $str3;
    }
}
