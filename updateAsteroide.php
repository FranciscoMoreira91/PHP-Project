<?php

require_once($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/constants.php');

$connect = mysqli_connect(database_host, database_user, database_password, database_name); //Conectar à Base de Dados


if (isset($_POST["update_asteroid"])) {
    //Nome do asteroida
    $asteroidName = $_POST["asteroid_name"];
    //Descrição do asteroida
    $asteroidDescription = $_POST["asteroid_desc"];
    //Tamanho do asteroida
    $asteroidSize = $_POST["asteroid_size"];
    //Velocidade do asteroida
    $asteroidVelocity = $_POST["asteroid_velocity"];
    //Velocidade de rotação do asteroida
    $asteroidRotationVelocity = $_POST["asteroid_rotation_velocity"];
    //
    $focus1Elipse = 0;
    //
    $focus2Elipse = 0;
    //
    $focus1Orbit = $_POST["Asteroid_orbit_focus_1"];
    //
    $focus2Orbit = $_POST["Asteroid_orbit_focus_2"];
    //Verificar se foi colocada uma imagem diferente
    if (file_exists($_FILES["AsteroidImageInsert"]["tmp_name"])) {
        //Imagem do asteroida
        $asteroidImage = addslashes(file_get_contents($_FILES["AsteroidImageInsert"]["tmp_name"]));
    }
    //Verifica se foi colocada uma textura diferente
    if (file_exists($_FILES["AsteroidSkinInsert"]["tmp_name"])) {
        //Imagem da textura
        $asteroidSkin = addslashes(file_get_contents($_FILES["AsteroidSkinInsert"]["tmp_name"]));
    }

    //Verificações para a query
    if (!isset($asteroidSkin) and !isset($asteroidImage)) {
        //Query a ser executada
        $query = "UPDATE corpos_celestes SET Nome=?, Descricao=?, Tamanho=?, Velocidade=?, VelocidadeRotacao=?,
    Foco1elipse=?, Foco2elipse=?, DistanciaFoco1=?, DistanciaFoco2=? WHERE id=?";
        //Prepared statement
        $stmt = $connect->prepare($query);
        //Binding
        $stmt->bind_param(
            'ssdidddddi',
            $asteroidName,
            $asteroidDescription,
            $asteroidSize,
            $asteroidVelocity,
            $asteroidRotationVelocity,
            $focus1Elipse,
            $focus2Elipse,
            $focus1Orbit,
            $focus2Orbit,
            $_SESSION["asteroidID"]
        );
    }
    if (isset($asteroidSkin) and !isset($asteroidImage)) {
        //Query a ser executada
        $query = "UPDATE corpos_celestes SET Skin=?, Nome=?, Descricao=?, Tamanho=?, Velocidade=?, VelocidadeRotacao=?, 
    Foco1elipse=?, Foco2elipse=?, DistanciaFoco1=?, DistanciaFoco2=? WHERE id=?";
        $null = NULL;
        //Prepared statement
        $stmt = $connect->prepare($query);
        //Binding
        $stmt->bind_param(
            'bssdidddddi',
            $null,
            $asteroidName,
            $asteroidDescription,
            $asteroidSize,
            $asteroidVelocity,
            $asteroidRotationVelocity,
            $focus1Elipse,
            $focus2Elipse,
            $focus1Orbit,
            $focus2Orbit,
            $_SESSION["asteroidID"]
        );
        $stmt->send_long_data(0, file_get_contents($_FILES["AsteroidSkinInsert"]["tmp_name"]));
    }
    if (!isset($asteroidSkin) and isset($asteroidImage)) {
        //Query a ser executada
        $query = "UPDATE corpos_celestes SET Imagem=?, Nome=?, Descricao=?, Tamanho=?, Velocidade=?, VelocidadeRotacao=?, 
                Foco1elipse=?, Foco2elipse=?, DistanciaFoco1=?, DistanciaFoco2=? WHERE id=?";
        $null = NULL;
        //Prepared statement
        $stmt = $connect->prepare($query);
        //Binding
        $stmt->bind_param(
            'bssdidddddi',
            $null,
            $asteroidName,
            $asteroidDescription,
            $asteroidSize,
            $asteroidVelocity,
            $asteroidRotationVelocity,
            $focus1Elipse,
            $focus2Elipse,
            $focus1Orbit,
            $focus2Orbit,
            $_SESSION["asteroidID"]
        );
        $stmt->send_long_data(0, file_get_contents($_FILES["AsteroidImageInsert"]["tmp_name"]));
    }
    if (isset($asteroidSkin) and isset($asteroidImage)) {
        //Query a ser executada
        $query = "UPDATE corpos_celestes SET Imagem=?, Skin=?, Nome=?, Descricao=?, Tamanho=?, Velocidade=?, VelocidadeRotacao=?, 
                        Foco1elipse=?, Foco2elipse=?, DistanciaFoco1=?, DistanciaFoco2=? WHERE id=?";
        $null = NULL;
        //Prepared statement
        $stmt = $connect->prepare($query);
        //Binding
        $stmt->bind_param(
            'bbssdidddddi',
            $null,
            $null,
            $asteroidName,
            $asteroidDescription,
            $asteroidSize,
            $asteroidVelocity,
            $asteroidRotationVelocity,
            $focus1Elipse,
            $focus2Elipse,
            $focus1Orbit,
            $focus2Orbit,
            $_SESSION["asteroidID"]
        );
        $stmt->send_long_data(0, file_get_contents($_FILES["AsteroidImageInsert"]["tmp_name"]));
        $stmt->send_long_data(1, file_get_contents($_FILES["AsteroidSkinInsert"]["tmp_name"]));
    }
    //Executar a prepared statement
    $stmt->execute();
    //Guardar o resultado da query
    $result = $stmt->get_result();
    //Fechar a conexão
    mysqli_close($connect);
    //Redireccionar
    header('location: ../Asteroides/detalhesAsteroid.php?id=' . $_SESSION["asteroidID"]);
}
