<?php 
session_start();
ob_start();
include($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php');
include($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php');
$idLua = getLuaDetails($_SESSION["IDLua"]);
$idLua = $idLua->fetch_assoc();
$sistema = getSistema($idLua["SistemasFK"]);
if($sistema["UtilizadorFK"] != $_SESSION["userID"]){
  if($_SESSION["roleID"] != ("supervisor" || "admin")){
    header('location: ../Index.php');
  }
}
?>

<head>
  <link rel="stylesheet" href="../../CSS/bootstrap.css" />
  <link rel="stylesheet" href="../CSS/site.css" />
</head>

<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST'){     
      $result = deletePerguntaLuas($_SESSION["perguntaID"]); 
      echo $result;
      if($result){
        header("location: ../Luas/PerguntasLuas.php?id=".$_SESSION["IDLua"]);
      }
      else{
        echo "<h3>" . traduzir("ErrorEmail") . "</h3";
      }
    }
    else{
      $idPergunta = $_GET["id"];
      $_SESSION["perguntaID"] = $idPergunta;
    }
?>

<style>
  #mainC {
    margin-top: 60px;
  }
</style>
<?php 
  $idPergunta = getPerguntaLuaDetails($_SESSION["perguntaID"])->fetch_assoc();
?>
<h3><?php echo traduzir("LabelAskDelete")?></h3>
<div id="mainC">
    <dl class="dl-horizontal">
        <dt>
            <?php echo traduzir("LabelPerguntaSelecionada")?>
        </dt>
        <dd>
            <?php echo $idPergunta["Pergunta"]?> 
        </dd>
    </dl>

    <form id="myForm" action="ApagarPerguntaLuas.php" method="post">
        <div class="form-actions no-color">        
          <button class="btn btn-info">
            <a style="color: white" href="./PerguntasLuas.php?id=<?php echo$_SESSION["IDLua"]?>" ><?php echo traduzir("BTN_Return")?></a>
          </button>
          <input type="submit" value="<?php echo traduzir("LavelConfirmarApagar")?>" class="btn btn-danger"> 
        </div> 
    </form>
</div>