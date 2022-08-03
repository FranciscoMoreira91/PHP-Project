<?php
//Início de sessão na página
session_start();
ob_start();
//Requires necessários para o correto funcionamento da página
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php';
//Obter o ID do planeta
$id = $_GET["id"];
//Obter os dados do planeta em questão
$result = getPlanetaDetails($id);
//Obter o array associativo desses dados
$result = mysqli_fetch_assoc($result);
//URL para ser usado na parte das traduções
$_SESSION['url'] = basename($_SERVER['PHP_SELF']);
$_SESSION['url'] = $_SESSION['url'] . "?id=" . $id . "&";
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php';
//Caso o utilizador não esteja verificado não deixa aceder
if (isset($_SESSION["verified"])) {
	//Caso o utilizador não esteja verificado
	if ($_SESSION["verified"] == 0) {
		header('location: ConfirmationSentEmail.php');
	}
}
//Caso tenha sido apagado ou não exista
if ($result['visible'] == 0) {
	header('location: ../Index.php');
}
?>

<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PlanetarySystemGO</title>
	<link rel="stylesheet" href="../../CSS/AsteroidDetails.css" />
</head>

<body>
	<div class="container body-content">
		<div id="mainContainer">
			<div class="btn-toolbar mb-3">
				<div class="btn-group mr-2">
					<a class="btn btn-outline-secondary" href="../Sistemas/Sistema.php?id=<?php echo $result['SistemasFK'] ?>">
						<?php echo traduzir("BTN_VoltarListaSistemas") ?>
					</a>
				</div>
			</div>
			<!-- Dados do planeta -->
			<div id="asteroidData">
				<h3 id="asteroidName"><?php echo $result["Nome"] ?></h3>
				<div id="rightContainer">
					<textarea name="descrAsteroide" id="descrAsteroide" readonly><?php echo $result['Descricao'] ?></textarea>
				</div>
			</div>
			<table id="dataTable">
				<tr>
					<td>
						<label><?php echo traduzir("PlaceholderVelocidadeAsteroid") ?></label>
					</td>
					<td>
						<label><?php echo $result["Velocidade"] ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<label><?php echo traduzir("PlaceholderVelocidadeRotaçãoAsteroid") ?></label>
					</td>
					<td>
						<label><?php echo $result["VelocidadeRotacao"] ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<label><?php echo traduzir("PlaceholderTamanhoAsteroid") ?></label>
					</td>
					<td>
						<label><?php echo $result["Tamanho"] ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<label><?php echo traduzir("LabelDistanciaFoco1ElipseOrbita") ?></label>
					</td>
					<td>
						<label><?php echo $result["DistanciaFoco1"] ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<label><?php echo traduzir("LabelDistanciaFoco2ElipseOrbita") ?></label>
					</td>
					<td>
						<label><?php echo $result["DistanciaFoco2"] ?></label>
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>

</html>