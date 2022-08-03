<?php

require_once($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Languages/tradutor.php');
/* CASO SE MUDE DE SERVIDOR, MUDAR AQUI O LINK */
$PATH = "http://localhost/PlanetarySystemTPSI/SolarSystemGO-2020";

$connect = mysqli_connect(database_host, database_user, database_password, database_name); //Conectar à Base de Dados

if (isset($_POST["send_asteroid"])) {
    //Nome do asteroid
    $asteroidName = $_POST["asteroid_name"];
    //Descrição do asteroid
    $asteroidDescription = $_POST["asteroid_desc"];
    //Tamanho do asteroida
    $asteroidSize = $_POST["asteroid_size"];
    //Velocidade do asteroid
    $asteroidVelocity = $_POST["asteroid_velocity"];
    //Velocidade de rotação do asteroid
    $asteroidRotationVelocity = $_POST["asteroid_rotation_velocity"];
    //
    $focus1Elipse = 0;
    //
    $focus2Elipse = 0;
    //
    $focus1Orbit = $_POST['asteroid_orbit_focus_2'];
    //
    $focus2Orbit = $_POST['asteroid_orbit_focus_1'];
    //Imagem do asteroid
    $asteroidImage = addslashes(file_get_contents($_FILES["AsteroidImageInsert"]["tmp_name"]));
    //Imagem da textura
    $asteroidSkin = addslashes(file_get_contents($_FILES["AsteroidSkinInsert"]["tmp_name"]));
    //Query a ser executada
    $query = "INSERT INTO corpos_celestes(Nome, Descricao, Tamanho, Velocidade, VelocidadeRotacao, Imagem, Skin, Foco1elipse, Foco2elipse, DistanciaFoco1, DistanciaFoco2, SistemasFK, TipoDeCorpoCeleste)
    VALUES (?,?,?,?,?,'$asteroidImage', '$asteroidSkin',?,?,?,?,?,1)";
    //Prepared statement
    $stmt = $connect->prepare($query);
    //Binding
    $stmt->bind_param('ssdidddddi', $asteroidName, $asteroidDescription, $asteroidSize, $asteroidVelocity, $asteroidRotationVelocity, $focus1Elipse, $focus2Elipse, $focus1Orbit, $focus2Orbit, $_SESSION["systemID"]);
    //Executar a prepared statement
    if ($stmt->execute()) {
        //Criar a string que contém o script do alert de sucesso
        $str = "<script language='javascript'>" . "alert('" . traduzir("asteroidCreatedSuccessfully") . "');";
        $str2 = "window.location.href = '$PATH/Views/Sistemas/Sistema.php?id=" . $_SESSION['systemID'] . "';</script>";
        $str3 = $str . $str2;
        //Mostrar a string
        echo $str3;
    } else {
        //Criar a string que contém o script do alert de insucesso
        $str = "<script language='javascript'>" . "alert('" . traduzir("asteroidNotCreated") . "');";
        $str2 = "window.location.href = '$PATH/Views/Sistemas/Sistema.php?id=" . $_SESSION['systemID'] . "';</script>";
        $str3 = $str . $str2;
        //Mostrar a string
        echo $str3;
    }
}
