<?php 

require "Connessione.php";


$connessione = new Connessione();

if(isset($_FILES['pdfDaCaricare']))
{

    $filePDF = $_FILES['pdfDaCaricare']['tmp_name'];
    $fileImg = $_FILES['fotoDaCaricare']['tmp_name'];


    move_uploaded_file($filePDF, "../Riassunti/".$_FILES['pdfDaCaricare']['name']);

    move_uploaded_file($fileImg, "../Immagini/".$_FILES['fotoDaCaricare']['name']);
    
    $connessione->inserisci($_FILES['pdfDaCaricare']['name'], $_FILES['fotoDaCaricare']['name'], $_POST['indirizzi'], $_POST['materie'], $_POST['anno']);
}

?>
<body>
<br><br>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script>


$(window).on("load", () => {
    for (let i = 0; i < 5; i++) {
            let option = document.createElement("option");
            option.value = i + 1 ;
            option.innerText = i + 1;
            document.getElementById("anno").appendChild(option);
    }})



function fetchIndirizzi() {
    sessionStorage.clear();
    $.ajax({
        url: "indirizzi.php",
        method: "POST",
        success: async function (data) {
            let olHTML = $("#outJS");
            for (let i = 0; i < data.length; i++) {
                
                

                let option = document.createElement("option");
      			option.value = data[i].Indirizzo;
                option.innerText = data[i].Indirizzo;
                document.getElementById("indirizzi").appendChild(option);

				$("#indirizzi").on("change", async data => {
                    let e = document.getElementById("indirizzi");
                    let risultato = await fetchMaterie(e.options[e.selectedIndex].value);
				    disegnaSecondaParte(risultato);
                });
			}
            disegnaSecondaParte(await fetchMaterie(data[0].Indirizzo));
            }
    })
}

function disegnaSecondaParte(data)
{
    document.getElementById("materie").innerHTML = "";
    for (let i = 0; i < data.length; i++) {
            let option = document.createElement("option");
            option.value = data[i].IDMateria;
            option.innerText = data[i].Materia;
            document.getElementById("materie").appendChild(option);
    }
}

async function fetchMaterie(indirizzoHovered) {
    let resFetch = await fetch("materie.php?indirizzo=" +
        indirizzoHovered);
    let risposta = await resFetch.json();
    return risposta;
}

$(window).on("load", fetchIndirizzi);

function scorlla(to) {
    window.location = window.location.href + to.id;
}
</script>
  
  <style>
      img{
          cursor: pointer;
          width: 50px;
          height: 50px;
      }
  body
  {
  
  	background-color: #ffc0cb;
  	color: #7FFFD4;
    font-size: 2em;
  }
  
  
  </style>
  
  <style>
      @import url('https://fonts.googleapis.com/css?family=Roboto&display=swap');
      *
      {
      	font-family: "Roboto"
      }
   
   </style>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap-grid.css" integrity="sha256-ioAA5G7gdssuN24SL2ByxTMiyg5m5PnP3I4TS5hNIYA=" crossorigin="anonymous" />
    <title>Carica Riassunto</title>
	<form style="margin-left: 5%"action="culo.php" method="post" enctype="multipart/form-data">


     

	<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="form-group">
            <label for="pdfDaCaricare">Seleziona un riassunto BROOO</label>
            <input type="file" class="form-control-file" name="pdfDaCaricare" id="pdfDaCaricare">
        </div>

        <div class="form-group">
            <label for="fotoDaCaricare">Seleziona la foto BROOO</label>
            <input type="file" class="form-control-file" name="fotoDaCaricare" id="fotoDaCaricare">
        </div>

        <div class="form-group">
        <label for="indirizzi">Scegli l'indirizzo</label>
        <select class="form-control" name="indirizzi" id="indirizzi"> </select>


        </div>

        <div class="form-group">
        <label for="materie">Metti La materia</label>
        <select class="form-control" name="materie" id="materie"></select>


        </div>

        <div class="form-group">
        <label for="materie">Seleziona l'anno</label>
        <select class="form-control" name="anno" id="anno"></select>


        </div>

        <button type="submit" value="Carica" class="btn btn-primary mb-2">Carica</button>
        </div>
            <div class="col-md-2"></div>
    </div>

    
</form>
<?php 
    
    $riassunti = $connessione->mostraRiassunto();
    
    $tabella = "<center><br><br><br><table><tr><td>Immagine</td><td>Nome</td><td>Id</td><td>Data</td><td>Elimina</td></tr>";
    foreach($riassunti as $riassunto)
    {
        $titolo = $riassunto['Titolo'];
        $data = $riassunto['DataPubblicazione'];
        $id = $riassunto['IDRiassunto'];
        
        
        $immagineElimina = "<img src='https://img.icons8.com/cotton/2x/delete-sign--v2.png' onclick='eliminaRiassunto(".json_encode($riassunto).")'>";
        $immagineRiassunto = "<img src='../" . $riassunto['UrlIMG'] . "'>";
        $tabella .= "<tr><td>$immagineRiassunto</td><td>$titolo</td><td>$id</td><td>$data</td><td>$immagineElimina</td></tr>";

    }

    echo $tabella."</center>";
    
    ?> 
    
    
    <body><script>
        function eliminaRiassunto(riassunto)
        {
        	let id = riassunto.IDRiassunto;
            let ris = confirm("Sei sicuro di voler cancellare: " + riassunto.Titolo + "\nCaricato il: " + riassunto.DataPubblicazione + "?");
            if(ris)
            {
                ris = confirm("Sicuro sicuro fra?");
                if(ris)
                {
                    alert("Comandi te");
                    
                    $.ajax(
                        {
                            url: "eliminaRiassunto.php", 
                            data: {id: id}, 
                            method: "POST", 
                            success: function(data) 
                            {
                                location.reload();
                            }
                        }
                        )
                }
            }

            /*
            */
        }
    </script>