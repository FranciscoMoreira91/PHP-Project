<?php
//Inicio da sessão
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/deleteLuas.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php';
//Variavel de sessão do planeta
$idLua = $_GET["id"];
//URL para ser usado na parte das traduções
$_SESSION['url'] = basename($_SERVER['PHP_SELF']);
$_SESSION['url'] = $_SESSION['url'] . "?id=" . $idLua . "&";
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php';
//----------------------------------------------------------------------------------------------------
//Obter as informações do planeta
$LuaDetails = getLuaDetails($idLua);
//Obter array associativo 
$LuaDetails = $LuaDetails->fetch_assoc();
//Obter as informações do sistema onde o planeta está inserido
$SistemaDetails = getSistema($LuaDetails["SistemasFK"]);
//Caso o utilizador não esteja autenticado
if (!isset($_SESSION["isLogged"])) {
    header('location: Index.php');
}
//Se o utilizador não estiver verificado
if ($_SESSION['verified'] == 0) {
    header('location: ConfirmationSentEmail.php');
}
//Caso o utilizador em questão não seja o mesmo que criou o sistema
if ($SistemaDetails["UtilizadorFK"] != $_SESSION["userID"]) {
    if($_SESSION["roleID"] != (2 || 3)){
        header('location: ../Index.php');
      }
}
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanetarySystemGO</title>
    <link rel="stylesheet" href="../../CSS/ApagarLua.css" />
</head>

<body>
    <form action="ApagarLuas.php?id=<?php echo $idLua ?>" method="POST">
        <!-- Container principal -->
        <div id="generalContainer">
            <!-- Container-->
            <div id="cont2" name="cont2">
                <!-- Container que possui o texto principal -->
                <div id="TextContainer">
                    <!-- Texto relativo ao utilizador não possuir uma organização -->
                    <p id="TextParagraph"><?php echo traduzir("AskDeleteMoon")?></p>
                </div>
                <!-- Container que possui o botão de criar uma organização -->
                <div id="ButtonContainer">
                    <a id="returnBTN" name="returnBTN" href="<?php echo $PATH ?>/Views/Sistemas/Sistema.php?id=<?php echo $_SESSION["systemID"] ?>"><?php echo traduzir("BTN_Return") ?></a>
                    <!-- Botão responsável de redireccionar para a página de criação de organização -->
                    <input type="submit" id="deleteluas" name="deleteluas" value="<?php echo traduzir("BTN_ApagarLua") ?>">
                </div>
            </div>
        </div>
    </form>
</body>

</html>