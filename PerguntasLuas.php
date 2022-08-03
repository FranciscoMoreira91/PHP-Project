<?php
//Início de sessão na página
session_start();
//Requires necessários para o correto funcionamento da página
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php';
//Guardar o IDLua da lua numa variável de sessão
$_SESSION["IDLua"] = $_GET["id"];
$idPergunta = getPerguntasLuas($_SESSION["IDLua"]);
$idLua = getLuaDetails($_SESSION["IDLua"]);
$idLua = $idLua->fetch_assoc();
$sistema = getSistema($idLua["SistemasFK"]);
if ($sistema['Repositorio'] != "Publico") {
  if ($sistema['Repositorio'] != "Privado") {
    if ($sistema["UtilizadorFK"] != $_SESSION["userID"]) {
      if ($_SESSION["role"] != ("supervisor" || "admin")) {
        header('location: ../Index.php');
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<style>
#maincontainer{
  margin-top: 60px;
}
</style>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PlanetarySystemGO</title>
  <link rel="stylesheet" href="../../CSS/PerguntasPlaneta.css" />
</head>

<body>
  <div id="mainContainer" class="container body-content">
    <h2 id=pageTitle><?php echo traduzir("LabelPerguntasLua") ?> <?php echo $idLua["Nome"] ?></h2>
    <div class="btn-toolbar mb-3">
      <div class="btn-group mr-2">
        <a class="btn btn-outline-secondary" href="../Sistemas/Sistema.php?id=<?php echo $idLua["SistemasFK"] ?> ">
          <?php echo traduzir("BTN_ReturnPlanetList") ?>
        </a>
      </div>
      <div class="btn-group">
        <?php
        if ($sistema["UtilizadorFK"] == $_SESSION["userID"]) {
        ?>
          <a class="btn btn-primary" href="../Luas/CriarPerguntaLuas.php?id=<?php echo $idLua["IDLua"] ?>">
            <?php echo traduzir("LabelAddQuestion") ?>
          </a>
        <?php } ?>
      </div>
    </div>

    <table class="table table-striped table-hover table-light">
      <tr>
        <th>
          <?php echo traduzir("LabelQuestion") ?>
        </th>
        <th>
          <?php echo traduzir("LabelRespostaCorreta") ?>
        </th>
        <th></th>
      </tr>


      <?php while ($row = $idPergunta->fetch_assoc()) { ?>

        <tr class=" <?PHP echo ($row["Ativa"] == '1') ? 'table-primary' : 'table-secondary' ?>">
          <?php $resposta = getRespostasCertasLuas($row["ID"]) ?>
          <th><?php
              echo $row["Ativa"] ? "" : "(Inativa) ";
              echo $row["Pergunta"];
              ?>
          </th>
          <th> <?php echo $resposta->fetch_assoc()["textoDaResposta"] ?></th>
          <th>
            <?php
            if ($sistema["UtilizadorFK"] == $_SESSION["userID"]) {
            ?>
              <a class="btn btn-sm mr-1 btn-outline-secondary" href="../Luas/EditarPerguntaLuas.php?id=<?php echo $row["ID"] ?>"><?php echo traduzir("BTN_Edit") ?></a>
              <a class="btn btn-sm btn-outline-danger" href="../Luas/ApagarPerguntaLuas.php?id=<?php echo $row["ID"] ?>"><?php echo traduzir("BTN_Delete") ?></a>
            <?php
            }
            ?>
          </th>

        </tr>

      <?php } ?>
    </table>
  </div>
</body>

</html>