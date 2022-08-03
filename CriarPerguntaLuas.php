<?php
session_start();
ob_start();
include($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php');
include($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php');
$idLua = getLuaDetails($_SESSION["IDLua"]);
$idLua = $idLua->fetch_assoc();
$sistema = getSistema($idLua["SistemasFK"]);
if ($sistema["UtilizadorFK"] != $_SESSION["userID"]) {
  if ($_SESSION["role"] != ("admin" || "supervisor")) {
    header('location: ../Index.php');
  }
}
?>

<!-- TODO: homogenizar a importação do bootstrap -->

<head>
  <link rel="stylesheet" href="../../CSS/bootstrap.css" />
  <link rel="stylesheet" href="../CSS/site.css" />
</head>
<style>
  
  #mainC {
    margin-top: 60px;
  }

  #CreateQuestionTitle {
    text-align: center;
    font-family: Open Sans;
  }
</style>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $certa = "";
  //verificar se o utilizador escolheu uma resposta certa. Devia ser verificado antes do pedido POST
  for ($i = 0; $i < 4; $i++) {
    $aux = "Resposta_" . $i . "_certa";
    //ver se esta foi a resposta certa e guardar qual foi
    if (isset($_POST[$aux])) {
      $certa = $i;
      break;
    }
  }
  //houve resposta certa? temos de comparar também o tipo, provavelmente estava a considerar 0 e "" como iguais
  if ($certa !== "") {

    //se sim: 1. Adicionar elementos à BD. 2. reencaminhar para a listagem de perguntas
    $pergunta = $_POST["Pergunta"];
    $respostas = array($_POST["Resposta_0"], $_POST["Resposta_1"], $_POST["Resposta_2"], $_POST["Resposta_3"]);
    $idLua = $_SESSION["IDLua"];
    if (createPerguntaLuas($pergunta, $respostas, $certa,  $idLua)) {
      header("location: ../Luas/PerguntasLuas.php?id=" . $idLua);
    } else {
      echo "<h1>Ocorreu um erro, por favor tente novamente</h1>";
    }
  } else {
    //caso nao haja uma resposta correta
    echo '<h1 style="color:red;" >' . traduzir("SelectCorrectQuestion") . '</h1>';
  }
}
?>

<div id="mainC" class="container body-content">
  <h2 id="CreateQuestionTitle"><?php echo traduzir("TitleAddQuestion") ?></h2>
  <form id="myForm" action="CriarPerguntaLuas.php" method="POST">
    <div class="form-horizontal">

      <div class="form-group">
        <label class="control-label col-md-2"><?php echo traduzir("LabelQuestion") ?></label>
        <div class="col-md-10">
          <input class="form-control text-box single-line" data-val="true" data-val-required="<?php echo traduzir("DataValueRequiredPergunta") ?>" id="Pergunta" name="Pergunta" type="text" value="" required></input>
        </div>
      </div>

      <table class="table">
        <tr class="form-group">
          <td class="col-md-10" style="border:0px"><?php echo traduzir("LabelRespostas") ?></td>
          <td class="col-md-10" style="border:0px"><?php echo traduzir("LabelRespostaCerta") ?></td>
        </tr>
        <?php for ($i = 0; $i < 4; $i++) {  ?>
          <tr class="form-group">
            <td class="col-md-10" style="border:0px">
              <input class="form-control text-box single-line" data-val="true" data-val-required="<?php echo traduzir("DataValueRequiredResposta") ?>" id="Resposta_<?php echo $i ?>" name="Resposta_<?php echo $i ?>" type="text" value="" required>
            </td>
            <td class="col-md-10" style="border:0px">
              <input class="form-control check-box" data-val="true" data-val-required="<?php echo traduzir("DataValueRequiredCorreta") ?>" id="checkbox<?php echo $i ?>" onclick="checkboxcheck(this.id)" name="Resposta_<?php echo $i ?>_certa" type="checkbox" value="true">
            </td>
          </tr>

        <?php } ?>
      </table>

      <div class="form-group">
        <div class="col-md-offset-0 col-md-14" style="padding:12px">
          <input type="submit" value="<?php echo traduzir("BTN_AddQuesion") ?>" class="btn btn-success" />
          <a class="btn btn-outline-secondary" href="../Luas/PerguntasLuas.php?id=<?php echo $_SESSION["IDLua"] ?>"><?php echo traduzir("BTN_ReturnListQuestions") ?></a>
        </div>
      </div>

    </div>
  </form>
</div>
<script>
  //receve o nome da checkbox que foi carregada, para colocar o atribute checked a false nas outras
  function checkboxcheck(t) {
    for (j = 0; j < 4; j++) {
      i = "checkbox" + j;
      if (i != t) {
        document.getElementById(i).checked = false;
      }
    }
  }
</script>