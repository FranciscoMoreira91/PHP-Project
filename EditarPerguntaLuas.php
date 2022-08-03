<?php 
session_start();
ob_start();
include($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/navbar.php');
include($_SERVER['DOCUMENT_ROOT'] . './PlanetarySystemTPSI/SolarSystemGO-2020/Assets/database.php');
$idPergunta = $_GET["id"];
$pergunta = getPerguntaLuaDetails($idPergunta);
var_dump($pergunta);
$pergunta = $pergunta->fetch_assoc();
$txtPergunta = $pergunta["Pergunta"];
$ativa = "";
$respostas = getRespostasLuas($pergunta["ID"]);
$idLua = getLuaDetails($_SESSION["IDLua"]);
$idLua = $idLua->fetch_assoc();
$sistema = getSistema($idLua["SistemasFK"]);
if($sistema["UtilizadorFK"] != $_SESSION["userID"]){
  if($_SESSION["role"] != ("admin" || "supervisor")){
    header('location: ../Index.php');
  }
}
?>

<style>
  #mainC{
    margin-top: 60px;
  }
  #EditQuestionTitle{
    text-align: center;
    font-family: Open Sans;
  }
</style>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $txtRespostas = array($_POST["resposta0"], $_POST["resposta1"], $_POST["resposta2"], $_POST["resposta3"]); //texto das respostas
  $respostasID = array($_POST["resposta0ID"], $_POST["resposta1ID"], $_POST["resposta2ID"], $_POST["resposta3ID"]); //ID's das respostas
  echo "***********************************".$_POST["auxAtiva"];
  if( $_POST["auxAtiva"] === "true"){
    $flagAtiva = 1;
  }else{
    $flagAtiva = 0;
  }
  //Executar os comandos sql e verificar e foi concluido com sucesso
  if(editPerguntaLuas($perguntaID, $_POST["pergunta"], $flagAtiva, $txtRespostas, $respostasID, $_POST["respostaCerta"])){      
    header("location: ./PerguntasLuas.php?id=".$_SESSION["IDLua"]);
  }else{
    echo "<h1>Ocorreu um erro, por favor tente novamente</h1>";
  };
}
else{
  $ativa = "true";
  if($pergunta["Ativa"]==0) $ativa = "false";
}
?>

<!-- TODO: homogenizar a importação do bootstrap -->
<head>
  <link rel="stylesheet" href="../../CSS/bootstrap.css" />
  <link rel="stylesheet" href="../CSS/site.css" />
</head>

<div id="mainC" class="container body-content"> 
  <h2 id="EditQuestionTitle"><?php echo traduzir("TitleEditQuestion")?></h2>
  <form action="" enctype="multipart/form-data" method="post"> 
    <div class="form-horizontal">

      <div class="form-group">
        <label class="control-label col-md-2"><?php echo traduzir("LabelQuestion")?></label>
        <div class="col-md-10">                                  
          <input class="form-control text-box single-line" data-val="true" data-val-required="<?php echo traduzir("DataValueRequiredPergunta")?>" name="pergunta" type="text" required value="<?php echo $txtPergunta?>">
        </div>
      </div>

      <div class="form-group">  
        <label class="control-label col-md-2" ><?php echo traduzir("QuestionActive")?></label>
        <div class="col-md-10">
          <input class="form-control check-box" id="ativa" name="checkboxAtiva" onclick="checkAtiva()" type="checkbox" value="true"  <?php if($ativa == "true") echo "checked"  ?>>
<?php if($ativa =="true"){ ?>          
          <input type="hidden" id="auxAtiva" name="auxAtiva" value="true">
<?php }else{($ativa =="true") ?>
          <input type="hidden" id="auxAtiva" name="auxAtiva" value="false">
<?php };?> 
        </div>
      </div>

      <table class="table">
            <tr class="form-group">
                <td class="col-md-10" style="border:0px"><?php echo traduzir("LabelRespostas")?></td> 
                <td class="col-md-10" style="border:0px"><?php echo traduzir("LabelRespostaCerta")?></td>  
            </tr>
<?php for ($i=0; $i < 4; $i++) { $row = $respostas->fetch_assoc()  ?>
            <tr class="form-group">
              <td class="col-md-10" style="border:0px">                      
                <input class="form-control text-box single-line" data-val="true" data-val-required="<?php echo traduzir("DataValueRequiredResposta")?>" name="resposta<?php echo $i?>" type="text" value="<?php echo $row["textoDaResposta"] ?>" required>
                <input hidden value="<?php echo $row["ID"]?>" name="resposta<?php echo $i?>ID"> <!--hidden para mandar id's das respostas que vou precisar -->
              </td>
              <td class="col-md-10" style="border:0px">  
                <input class="form-control check-box" data-val="true" data-val-required="<?php echo traduzir("DataValueRequiredCorreta")?>"
                        id="certa<?php echo $i?>"  onclick="checkboxcheck(this.id)" name="respostaCerta" type="checkbox" value="<?php echo $i?>"
                         <?php if($row["certa"] == 1) echo "checked"?>>
              </td>
            </tr>
<?php }?> 
      </table>
      <div class="col-md-offset-2 col-md-10"> 
        <input type="submit" value="<?php echo traduzir("Save")?>" class="btn btn-success">   
        <a class="btn btn-outline-secondary" href="./PerguntasLuas.php?id=<?php echo $pergunta["LuasFK"]?>"><?php echo traduzir("GoBack")?></a>
      </div>
      
    </div>
  </form>
</div>







<script>
  //recebe o nome da checkbox que foi carregada, para colocar o atribute checked a false nas outras
  function checkboxcheck(t) {
    for (j = 0; j < 4; j++) {
      i = "certa" + j;
      if (i != t) {
        document.getElementById(i).checked = false;
      }
    }
  };
  //Para a checkbox relativa a se a pergunta está ativa
  function checkAtiva() {
    let valAtual = document.getElementById("auxAtiva").value;
    if(valAtual == "true") valAtual="false";
    else valAtual ="true";
    document.getElementById("auxAtiva").value = valAtual;
  }
</script>
