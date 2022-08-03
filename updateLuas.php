<?php

require_once($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/constants.php');

$connect = mysqli_connect(database_host, database_user, database_password, database_name); //Conectar à Base de Dados


if (isset($_POST["update_luas"])) {
    //Nome da lua
    $LuasName = $_POST["luas_name"];
    //Descrição da lua
    $LuasDescription = $_POST["luas_desc"];
    //Tamanho da lua
    $LuasSize = $_POST["luas_size"];
    //Velocidade da lua
    $LuasVelocity = $_POST["luas_velocity"];
    //Velocidade de rotação da lua
    $LuasRotationVelocity = $_POST["luas_rotation_velocity"];
    //
    $focus1Elipse = 0;
    //
    $focus2Elipse = 0;
    //
    $focus1Orbit = $_POST["luas_orbit_focus_1"];
    //
    $focus2Orbit = $_POST["luas_orbit_focus_2"];
    //Verificar se foi colocada uma imagem diferente
    if (file_exists($_FILES["LuasImageInsert"]["tmp_name"])) {
        //Imagem da lua
        $luasImage = addslashes(file_get_contents($_FILES["LuasImageInsert"]["tmp_name"]));
    }
    //Verifica se foi colocada uma textura diferente
    if (file_exists($_FILES["LuasSkinInsert"]["tmp_name"])) {
        //Imagem da textura
        $luasSkin = addslashes(file_get_contents($_FILES["LuasSkinInsert"]["tmp_name"]));
    }

    //Verificações para a query
    if (!isset($luasSkin) and !isset($luasImage)) {
        //Query a ser executada
        $query = "UPDATE luas SET Nome=?, Descricao=?, Tamanho=?, Velocidade=?, VelocidadeRotacao=?,
    Foco1elipse=?, Foco2elipse=?, DistanciaFoco1=?, DistanciaFoco2=? WHERE IDLua=?";
        //Prepared statement
        $stmt = $connect->prepare($query);
        //Binding
        $stmt->bind_param(
            'ssdidddddi',
            $LuasName,
            $LuasDescription,
            $LuasSize,
            $LuasVelocity,
            $LuasRotationVelocity,
            $focus1Elipse,
            $focus2Elipse,
            $focus1Orbit,
            $focus2Orbit,
            $_SESSION["luasID"]
        );
    }
    if (isset($luasSkin) and !isset($luasImage)) {
        //Query a ser executada
        $query = "UPDATE luas SET Skin=?, Nome=?, Descricao=?, Tamanho=?, Velocidade=?, VelocidadeRotacao=?, 
    Foco1elipse=?, Foco2elipse=?, DistanciaFoco1=?, DistanciaFoco2=? WHERE IDLua=?";
        $null = NULL;
        //Prepared statement
        $stmt = $connect->prepare($query);
        //Binding
        $stmt->bind_param(
            'bssdidddddi',
            $null,
            $LuasName,
            $LuasDescription,
            $LuasSize,
            $LuasVelocity,
            $LuasRotationVelocity,
            $focus1Elipse,
            $focus2Elipse,
            $focus1Orbit,
            $focus2Orbit,
            $_SESSION["luasID"]
        );
        $stmt->send_long_data(0, file_get_contents($_FILES["LuasSkinInsert"]["tmp_name"]));
    }
    if (!isset($LuasSkin) and isset($LuasImage)) {
        //Query a ser executada
        $query = "UPDATE luas SET Imagem=?, Nome=?, Descricao=?, Tamanho=?, Velocidade=?, VelocidadeRotacao=?, 
                Foco1elipse=?, Foco2elipse=?, DistanciaFoco1=?, DistanciaFoco2=? WHERE IDLua=?";
        $null = NULL;
        //Prepared statement
        $stmt = $connect->prepare($query);
        //Binding
        $stmt->bind_param(
            'bssdidddddi',
            $null,
            $LuasName,
            $LuasDescription,
            $LuasSize,
            $LuasVelocity,
            $LuasRotationVelocity,
            $focus1Elipse,
            $focus2Elipse,
            $focus1Orbit,
            $focus2Orbit,
            $_SESSION["luasID"]
        );
        $stmt->send_long_data(0, file_get_contents($_FILES["LuasImageInsert"]["tmp_name"]));
    }
    if (isset($LuasSkin) and isset($LuasImage)) {
        //Query a ser executada
        $query = "UPDATE luas SET Imagem=?, Skin=?, Nome=?, Descricao=?, Tamanho=?, Velocidade=?, VelocidadeRotacao=?, 
                        Foco1elipse=?, Foco2elipse=?, DistanciaFoco1=?, DistanciaFoco2=? WHERE IDLua=?";
        $null = NULL;
        //Prepared statement
        $stmt = $connect->prepare($query);
        //Binding
        $stmt->bind_param(
            'bbssdidddddi',
            $null,
            $null,
            $LuasName,
            $LuasDescription,
            $LuasSize,
            $LuasVelocity,
            $LuasRotationVelocity,
            $focus1Elipse,
            $focus2Elipse,
            $focus1Orbit,
            $focus2Orbit,
            $_SESSION["luasID"]
        );
        $stmt->send_long_data(0, file_get_contents($_FILES["LuasImageInsert"]["tmp_name"]));
        $stmt->send_long_data(1, file_get_contents($_FILES["LuasSkinInsert"]["tmp_name"]));
    }
    //Executar a prepared statement
    $stmt->execute();
    //Guardar o resultado da query
    $result = $stmt->get_result();
    //Fechar a conexão
    mysqli_close($connect);
    //Redireccionar
    header('location: ../Luas/detalhesLuas.php?id=' . $_SESSION["luasID"]);
}
