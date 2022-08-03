<?php
//Início de sessão na página
session_start();
ob_start();
//Requires necessários para o correto funcionamento da página
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php';
//Obter o ID da lua
$idLua = $_GET["id"];
//$idSistema = $_GET["IdSistema"];
//Obter as informações do sistema onde o planeta está inserido
$result = getSistema($result["systemID"]);
//Obter os dados da lua em questão
$result = getLuaDetails($idLua);
//Obter o array associativo desses dados
$result = mysqli_fetch_assoc($result);
//URL para ser usado na parte das traduções
$_SESSION['url'] = basename($_SERVER['PHP_SELF']);
$_SESSION['url'] = $_SESSION['url'] . "?id=" . $idLua . "&";
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php';
//Caso o utilizador não esteja verificado não deixa aceder
if (isset($_SESSION["verified"])) {
	//Caso o utilizador não esteja verificado
	if ($_SESSION["verified"] == 0) {
		header('location: ConfirmationSentEmail.php');
	}
}
//Caso tenha sido apagado ou não exista
if ($result['Visible'] == 0) {
	header('location: ../Index.php');
}
?>

<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PlanetarySystemGO</title>
	<link rel="stylesheet" href="../../CSS/LuasDetails.css" />
</head>

<body>
	<div class="container body-content">
		<div id="mainContainer">
			<div class="btn-toolbar mb-3">
				<div class="btn-group mr-2">
					<a class="btn btn-outline-secondary" href="../Sistemas/Sistema.php?id=<?php echo $_SESSION["systemID"] ?>">
						<?php echo traduzir("BTN_VoltarListaSistemas") ?>
					</a>
				</div>
			</div>
			<!-- Dados da lua -->
			<div id="luasData">
				<h3 id="luasName"><?php echo $result["Nome"] ?></h3>
				<div id="rightContainer">
					<textarea name="descrLuas" id="descrLuas" readonly><?php echo $result['Descricao'] ?></textarea>
				</div>
			</div>

			<div class="row">
				<div class="col-9">
					<table id="dataTable">
						<tr>
							<td>
								<label><?php echo traduzir("PlaceholderVelocidadeLuas") ?></label>
							</td>
							<td>
								<label><?php echo $result["Velocidade"] ?></label>
							</td>
						</tr>
						<tr>
							<td>
								<label><?php echo traduzir("PlaceholderVelocidadeRotaçãoLuas") ?></label>
							</td>
							<td>
								<label><?php echo $result["VelocidadeRotacao"] ?></label>
							</td>
						</tr>
						<tr>
							<td>
								<label><?php echo traduzir("PlaceholderTamanhoLuas") ?></label>
							</td>
							<td>
								<label><?php echo $result["Tamanho"] ?></label>
							</td>
						</tr>
						<tr>
							<td>
								<label><?php echo traduzir("LabelDistanciaFoco1ElipseOrbitamoon") ?></label>
							</td>
							<td>
								<label><?php echo $result["DistanciaFoco1"] ?></label>
							</td>
						</tr>
						<tr>
							<td>
								<label><?php echo traduzir("LabelDistanciaFoco2ElipseOrbitamoon") ?></label>
							</td>
							<td>
								<label><?php echo $result["DistanciaFoco2"] ?></label>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-3">
					<?php
					echo '<img src="data:image/jpeg;base64,' . base64_encode($result['Imagem']) . '" />';
					?>
				</div>
			</div>

			<a class="btn btn-outline-secondary" href="../Luas/EditarLuas.php?id=<?php echo $idLua ?>"> <?php echo traduzir("BTN_EditarLua") ?></a>
			<a class="btn btn-outline-secondary" href="../Luas/PerguntasLuas.php?id=<?php echo $idLua ?>"> <?php echo traduzir("BTN_PerguntaLua") ?></a>
			<div class="btn-group btn-group-sm">
				<a class="btn btn-outline-danger" href="../Luas/ApagarLuas.php?id=<?php echo $idLua ?>"><?php echo traduzir("BTN_ApagarLua") ?></a>
			</div>
		</div>
	</div>
</body>

</html>