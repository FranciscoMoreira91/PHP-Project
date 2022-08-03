<?php
//Inicio de sessão desta página
session_start();
ob_start();
//Definir variavel de sessao
$_SESSION["luasID"] = $_GET["id"];
//URL para ser usado na parte das traduções
$_SESSION['url'] = basename($_SERVER['PHP_SELF']);
$_SESSION['url'] = $_SESSION['url'] . "?id=" . $_SESSION['luasID'] . "&";
//----------------------------------------------------------------------------------------------------
//Requires necessários para o funcionamento correto da página
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/updateLuas.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php';
//Obtenção das informações do planeta em questão~
//Conectar à Base de Dados
$connect = mysqli_connect(database_host, database_user, database_password, database_name);
//Query a ser executada
$query = "SELECT * FROM luas WHERE IDLua=?";
//Prepared statement
$stmt = $connect->prepare($query);
//Binding
$stmt->bind_param('i', $_SESSION["luasID"]);
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
$luaDetails = getLuaDetails($_SESSION["luasID"]);
//Obter array associativo
$luaDetails = $luaDetails->fetch_assoc();
//Obter as informações do sistema onde o planeta está inserido
$SistemaDetails = getSistema($luaDetails["SistemasFK"]);
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
if($luaDetails['Visible'] == 0){
	header('location: ../Index.php');
}
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanetarySystemGO</title>
    <link rel="stylesheet" href="../../CSS/EditarLuas.css" />
</head>

<body>
    <form id="myForm" action="EditarLuas.php?id=<?php echo $_SESSION["luasID"] ?>" method="POST" enctype="multipart/form-data">
        <div id="generalContainer">
            <div id="createContainer">
                <div id="LuaName">
                    <div class="input-group">
                        <input type="text" class="form-control" name="luas_name" id="luas_name" value="<?php echo $data["Nome"] ?>" placeholder="<?php echo traduzir("PlaceholderNomeLuas") ?>" required />
                    </div>
                </div>
                <div id="containerImages">
                    <div id="LeftImageContainer">
                        <label id="label_luas_image"><?php echo traduzir("LabelImagemLuas") ?></label>
                        <div class="input-group">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($data['Imagem'])?>" id="luas_image" onclick="triggerClickPlanetImage()">
                            <input type="file" name="LuasImageInsert" id="LuasImageInsert" onchange="displayPlanetImage(this)">
                        </div>
                    </div>
                    <div id="RightImageContainer">
                        <label id="label_planet_skin"><?php echo traduzir("LabelTexturaLuas") ?></label>
                        <div class="input-group">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($data['Skin'])?>" id="luas_texture" onclick="triggerClickPlanetSkin()">
                            <input type="file" name="LuasSkinInsert" id="LuasSkinInsert" onchange="displayPlanetSkin(this)" />
                        </div>
                    </div>
                </div>
                <div id="LuasDescription">
                    <div class="input-group">
                        <textarea class="form-control text-box multi-line" id="luas_desc" name="luas_desc" placeholder="<?php echo traduzir("PlaceholderDescriçãoLuas") ?>" required><?php echo $data["Descricao"] ?></textarea>
                    </div>
                </div>
                <table id="dataTable">
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderTamanhoLuas") ?></label>
                        </td>
                        <td>
                            <input type="number" name="luas_size" id="luas_size" min="0" value="<?php echo $data["Tamanho"] ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderVelocidadeLuas") ?></label>
                        </td>
                        <td>
                            <input type="number" name="luas_velocity" id="luas_velocity" min="0" value="<?php echo $data["Velocidade"] ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderVelocidadeRotaçãoLuas") ?></label>
                        </td>
                        <td>
                            <input type="number" name="luas_rotation_velocity" id="luas_rotation_velocity" min="0" value="<?php echo $data["VelocidadeRotacao"] ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderDistanciaFoco1ElipseOrbita") ?></label>
                        </td>
                        <td>
                            <input type="number" name="luas_orbit_focus_1" id="luas_orbit_focus_1" value="<?php echo $data["DistanciaFoco1"] ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo traduzir("PlaceholderDistanciaFoco2ElipseOrbita") ?></label>
                        </td>
                        <td>
                            <input type="number" name="luas_orbit_focus_2" id="luas_orbit_focus_2" value="<?php echo $data["DistanciaFoco2"] ?>" required />
                        </td>
                    </tr>
                </table>
                <input type="submit" name="update_luas" id="update_luas" value="<?php echo traduzir("BTN_EditarLua") ?>">
            </div>
        </div>
    </form>
</body>

</html>

<script>
    //Função que permite que quando a imagem é clicada, permitir a inserção de uma imagem
    function triggerClickPlanetImage() {
        document.querySelector('#LuasImageInsert').click();
    }

    //Função que mostra a imagem que foi indicada
    function displayPlanetImage(e) {
        if (e.files[0]) {
            //Novo file reader
            var reader = new FileReader();
            //Quando a imagem tiver sido carregada
            reader.onload = function(e) {
                document.querySelector('#luas_image').setAttribute('src', e.target.result);
            }
            //Ler a imagem
            reader.readAsDataURL(e.files[0]);
        }
    }

    //Função que permite que quando a imagem é clicada, permitir a inserção de uma imagem
    function triggerClickPlanetSkin() {
        document.querySelector('#LuasSkinInsert').click();
    }

    //Função que mostra a imagem que foi indicada
    function displayPlanetSkin(e) {
        if (e.files[0]) {
            //Novo file reader
            var reader = new FileReader();
            //Quando a imagem tiver sido carregada
            reader.onload = function(e) {
                document.querySelector('#luas_texture').setAttribute('src', e.target.result);
            }
            //Ler a imagem
            reader.readAsDataURL(e.files[0]);
        }
    }

    $(document).ready(function() {
        $('#send_luas').click(function() {
            var image_name = $('#LuasImageInsert').val();
            if (image_name == '') {
                alert("Por favor seleccione uma imagem");
                return false;
            } else {
                var extension = $('#LuasImageInsert').val().split('.').pop().toLowerCase();
                if (jQuery.inArray(extension, ['png', 'jpg', 'jpeg']) == -1) {
                    alert('Ficheiro de imagem inválido');
                    $('#LuasImageInsert').val('');
                    return false;
                }
            }
        });
        $('#send_luas').click(function() {
            var image_name = $('#LuasSkinInsert').val();
            if (image_name == '') {
                alert("Por favor seleccione uma imagem");
                return false;
            } else {
                var extension = $('#LuasSkinInsert').val().split('.').pop().toLowerCase();
                if (jQuery.inArray(extension, ['png', 'jpg', 'jpeg']) == -1) {
                    alert('Ficheiro de textura inválido');
                    $('#LuasSkinInsert').val('');
                    return false;
                }
            }
        });

    });
</script>