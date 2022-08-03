<?php
//Inicio de sessão desta página
session_start();
ob_start();
//Definir variavel de sessao
$_SESSION["asteroidID"] = $_GET["id"];
//URL para ser usado na parte das traduções
$_SESSION['url'] = basename($_SERVER['PHP_SELF']);
$_SESSION['url'] = $_SESSION['url'] . "?id=" . $_SESSION['asteroidID'] . "&";
//----------------------------------------------------------------------------------------------------
//Requires necessários para o funcionamento correto da página
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/updateAsteroide.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php';
//Obtenção das informações do planeta em questão~
//Conectar à Base de Dados
$connect = mysqli_connect(database_host, database_user, database_password, database_name);
//Query a ser executada
$query = "SELECT * FROM corpos_celestes WHERE id=?";
//Prepared statement
$stmt = $connect->prepare($query);
//Binding
$stmt->bind_param('i', $_SESSION["asteroidID"]);
//Executar a prepared statement
$stmt->execute();
//Guardar o resultado da query
$result = $stmt->get_result();
//Fechar a conexão
mysqli_close($connect);
//Array associativo dos resultados obtidos através da query
$data = mysqli_fetch_assoc($result);
//----------------------------------------------------------------------------------------------------
//Obter as informações do planeta
$planetDetails = getPlanetaDetails($_SESSION["asteroidID"]);
//Obter array associativo
$planetDetails = $planetDetails->fetch_assoc();
//Obter as informações do sistema onde o planeta está inserido
$SistemaDetails = getSistema($planetDetails["SistemasFK"]);
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
    if ($_SESSION["role"] != "admin" || $_SESSION["role"] != "supervisor") {
        header('location: ../Index.php');
    }
}
//Caso tenha sido apagado
if($planetDetails['visible'] == 0){
	header('location: ../Index.php');
}
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanetarySystemGO</title>
    <link rel="stylesheet" href="../../CSS/EditAsteroid.css" />
</head>

<body>
    <!-- Formulário que permite a modificação de um planeta de um sistema -->
    <form id="myForm" action="EditarAsteroide.php?id=<?php echo $_SESSION["asteroidID"] ?>" method="POST" enctype="multipart/form-data">
        <div id="generalContainer">
            <div id="createContainer">
                <div id="AsteroidName">
                    <div class="input-group">
                        <input type="text" class="form-control" name="asteroid_name" id="asteroid_name" value="<?php echo $data["Nome"] ?>" placeholder="<?php echo traduzir("PlaceholderNomeAsteroide") ?>" required />
                    </div>
                </div>
                <div id="containerImages">
                    <div id="LeftImageContainer">
                        <label id="label_asteroid_image"><?php echo traduzir("LabelImagemAsteroid") ?></label>
                        <div class="input-group">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($data['Imagem'])?>" id="planet_image" onclick="triggerClickPlanetImage()">
                            <input type="file" name="AsteroidImageInsert" id="AsteroidImageInsert" onchange="displayPlanetImage(this)">
                        </div>
                    </div>
                    <div id="RightImageContainer">
                        <label id="label_planet_skin"><?php echo traduzir("LabelTexturaAsteroid") ?></label>
                        <div class="input-group">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($data['Skin'])?>" id="asteroid_texture" onclick="triggerClickPlanetSkin()">
                            <input type="file" name="AsteroidSkinInsert" id="AsteroidSkinInsert" onchange="displayPlanetSkin(this)" />
                        </div>
                    </div>
                </div>
                <div id="AsteroidDescription">
                    <div class="input-group">
                        <textarea class="form-control text-box multi-line" id="asteroid_desc" name="asteroid_desc" placeholder="<?php echo traduzir("PlaceholderDescriçãoAsteroide") ?>" required><?php echo $data["Descricao"] ?></textarea>
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
                            <input type="number" name="planet_velocity" id="asteroid_velocity" min="0" value="<?php echo $data["Velocidade"] ?>" required />
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
                            <label><?php echo traduzir("PlaceholderDistanciaFoco1ElipseOrbita") ?></label>
                        </td>
                        <td>
                            <input type="number" name="asteroid_orbit_focus_1" id="asteroid_orbit_focus_1" value="<?php echo $data["DistanciaFoco1"] ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderDistanciaFoco2ElipseOrbita") ?></label>
                        </td>
                        <td>
                            <input type="number" name="asteroid_orbit_focus_2" id="asteroid_orbit_focus_2" value="<?php echo $data["DistanciaFoco2"] ?>" required />
                        </td>
                    </tr>
                </table>
                <input type="submit" name="update_asteroid" id="update_asteroid" value="<?php echo traduzir("BTN_EditarAsteroid")?>">
            </div>
        </div>
    </form>
</body>

</html>

<script>
    //Função que permite que quando a imagem é clicada, permitir a inserção de uma imagem
    function triggerClickasteroidImage() {
        document.querySelector('#asteroidImageInsert').click();
    }

    //Função que mostra a imagem que foi indicada
    function displayasteroidImage(e) {
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
    function triggerClickasteroidSkin() {
        document.querySelector('#asteroidSkinInsert').click();
    }

    //Função que mostra a imagem que foi indicada
    function displayasteroidSkin(e) {
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
        $('#send_asteroid').click(function() {
            var image_name = $('#asteroidImageInsert').val();
            if (image_name == '') {
                alert("Por favor seleccione uma imagem");
                return false;
            } else {
                var extension = $('#asteroidImageInsert').val().split('.').pop().toLowerCase();
                if (jQuery.inArray(extension, ['png', 'jpg', 'jpeg']) == -1) {
                    alert('Ficheiro de imagem inválido');
                    $('#asteroidImageInsert').val('');
                    return false;
                }
            }
        });
        $('#send_asteroid').click(function() {
            var image_name = $('#asteroidSkinInsert').val();
            if (image_name == '') {
                alert("Por favor seleccione uma imagem");
                return false;
            } else {
                var extension = $('#asteroidSkinInsert').val().split('.').pop().toLowerCase();
                if (jQuery.inArray(extension, ['png', 'jpg', 'jpeg']) == -1) {
                    alert('Ficheiro de textura inválido');
                    $('#asteroidSkinInsert').val('');
                    return false;
                }
            }
        });

    });
</script>