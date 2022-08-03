<?php
//Perguntar acerca dos session_start() se precisam de ser mesmo em todas as páginas
session_start();
//Requires necessários para o funcionamento correto da página
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/insertAsteroid.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php';
//Variavel de sessão 
$_SESSION["systemID"] = $_GET["id"];
//URL para ser usado na parte das traduções
$_SESSION['url'] = basename($_SERVER['PHP_SELF']);
$_SESSION['url'] = $_SESSION['url'] . "?id=" . $_SESSION["systemID"] . "&";
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php';
//----------------------------------------------------------------------------------------------------
$SistemaDetails = getSistema($_SESSION["systemID"]); //Obter as informações do sistema onde o planeta está inserido
//Caso o utilizador não esteja autenticado
if (!isset($_SESSION["isLogged"])) {
    header('location: Index.php');
} else {
    if ($_SESSION['verified'] == 0) {
        header('location: ConfirmationSentEmail.php');
    }
}
//Caso o utilizador em questão não seja o mesmo que criou o sistema
if ($SistemaDetails["UtilizadorFK"] != $_SESSION["userID"]) {
    header('location: Index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanetarySystemGO</title>
    <link rel="stylesheet" href="../../CSS/CriarAsteroide.css" />
</head>

<body>
    <form id="myForm" action="CriarAsteroide.php?id=<?php echo $_SESSION["systemID"] ?>" method="POST" enctype="multipart/form-data">
        <div id="generalContainer">
            <div id="createContainer">
                <div id="AsteroidName">
                    <div class="input-group">
                        <input type="text" class="form-control" name="asteroid_name" id="asteroid_name" placeholder="<?php echo traduzir("PlaceholderNomeAsteroid") ?>" required />
                    </div>
                </div>
                <div id="containerImages">
                    <div id="LeftImageContainer">
                        <label id="label_asteroid_image"><?php echo traduzir("LabelImagemAsteroid") ?></label>
                        <div class="input-group">
                            <img src="../../Images/Question_Mark.png" id="asteroid_image" onclick="triggerClickPlanetImage()">
                            <input type="file" name="AsteroidImageInsert" id="AsteroidImageInsert" onchange="displayPlanetImage(this)">
                        </div>
                    </div>
                    <div id="RightImageContainer">
                        <label id="label_asteroid_skin"><?php echo traduzir("LabelTexturaAsteroid") ?></label>
                        <div class="input-group">
                            <img src="../../Images/Question_Mark.png" id="asteroid_texture" onclick="triggerClickPlanetSkin()">
                            <input type="file" name="AsteroidSkinInsert" id="AsteroidSkinInsert" onchange="displayPlanetSkin(this)" />
                        </div>
                    </div>
                </div>
                <div id="AsteroidDescription">
                    <div class="input-group">
                        <textarea class="form-control text-box multi-line" id="asteroid_desc" name="asteroid_desc" placeholder="<?php echo traduzir("PlaceholderDescriçãoAsteroid") ?>" required></textarea>
                    </div>
                </div>
                <table id="dataTable">
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderTamanhoAsteroid") ?></label>
                        </td>
                        <td>
                            <input type="number" name="asteroid_size" id="asteroid_size" min="0" value="<?php echo $data["Tamanho"] ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderVelocidadeAsteroid") ?></label>
                        </td>
                        <td>
                            <input type="number" name="asteroid_velocity" id="asteroid_velocity" min="0" value="<?php echo $data["Velocidade"] ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderVelocidadeRotaçãoAsteroid") ?></label>
                        </td>
                        <td>
                            <input type="number" name="asteroid_rotation_velocity" id="asteroid_rotation_velocity" min="0" value="<?php echo $data["VelocidadeRotacao"] ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderDistanciaFoco3ElipseOrbita") ?></label>
                        </td>
                        <td>
                            <input type="number" name="asteroid_orbit_focus_1" id="asteroid_orbit_focus_1" value="<?php echo $data["DistanciaFoco1"] ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderDistanciaFoco4ElipseOrbita") ?></label>
                        </td>
                        <td>
                            <input type="number" name="asteroid_orbit_focus_2" id="asteroid_orbit_focus_2" value="<?php echo $data["DistanciaFoco2"] ?>" required />
                        </td>
                    </tr>
                </table>
                <input type="submit" name="send_asteroid" id="send_asteroid" value="<?php echo traduzir("BTN_InserirAsteroid") ?>">
            </div>
        </div>
    </form>
</body>

</html>

<script>
    //Função que permite que quando a imagem é clicada, permitir a inserção de uma imagem
    function triggerClickPlanetImage() {
        document.querySelector('#AsteroidImageInsert').click();
    }

    //Função que mostra a imagem que foi indicada
    function displayPlanetImage(e) {
        if (e.files[0]) {
            //Novo file reader
            var reader = new FileReader();
            //Quando a imagem tiver sido carregada
            reader.onload = function(e) {
                document.querySelector('#asteroid_image').setAttribute('src', e.target.result);
            }
            //Ler a imagem
            reader.readAsDataURL(e.files[0]);
        }
    }

    //Função que permite que quando a imagem é clicada, permitir a inserção de uma imagem
    function triggerClickPlanetSkin() {
        document.querySelector('#AsteroidSkinInsert').click();
    }

    //Função que mostra a imagem que foi indicada
    function displayPlanetSkin(e) {
        if (e.files[0]) {
            //Novo file reader
            var reader = new FileReader();
            //Quando a imagem tiver sido carregada
            reader.onload = function(e) {
                document.querySelector('#asteroid_texture').setAttribute('src', e.target.result);
            }
            //Ler a imagem
            reader.readAsDataURL(e.files[0]);
        }
    }

    $(document).ready(function() {
        $('#send_planet').click(function() {
            var image_name = $('#AsteroidImageInsert').val();
            if (image_name == '') {
                alert("Por favor seleccione uma imagem");
                return false;
            } else {
                var extension = $('#AsteroidImageInsert').val().split('.').pop().toLowerCase();
                if (jQuery.inArray(extension, ['png', 'jpg', 'jpeg']) == -1) {
                    alert('Ficheiro de imagem inválido');
                    $('#AsteroidImageInsert').val('');
                    return false;
                }
            }
        });
        $('#send_asteroid').click(function() {
            var image_name = $('#AsteroidSkinInsert').val();
            if (image_name == '') {
                alert("Por favor seleccione uma imagem");
                return false;
            } else {
                var extension = $('#AsteroidSkinInsert').val().split('.').pop().toLowerCase();
                if (jQuery.inArray(extension, ['png', 'jpg', 'jpeg']) == -1) {
                    alert('Ficheiro de textura inválido');
                    $('#AsteroidSkinInsert').val('');
                    return false;
                }
            }
        });

    });
</script>