let cliccato = false;
let anni = null;
let riassunti = [];
let massimo = 3;
let stato = "Meno";
var indirizzi = [];

let baseURL = window.location.href;
baseURL = baseURL.replace(
  new RegExp(/([a-zA-Z0-9\s_\\.\-\(\):])+(.html|.php)$/),
  ""
);
baseURL = "https://vps.lellovitiello.tk/Riassunty/";

function fetchIndirizzi() {
  sessionStorage.clear();
  let url = baseURL.replace(/#section(\d)/, "") + "API/indirizzi.php";

  $.ajax({
    url: url,
    method: "POST",
    beforeSend: () => {

      let gif='[{    "url": "https://i.pinimg.com/originals/90/80/60/9080607321ab98fa3e70dd24b2513a20.gif", "colori":[25, 31, 38]},{"url": "https://mir-s3-cdn-cf.behance.net/project_modules/disp/e8e6e333001507.569c8e662ff30.gif",    "colori":[75, 82, 93]},{    "url": "https://i.pinimg.com/originals/78/e8/26/78e826ca1b9351214dfdd5e47f7e2024.gif",    "colori":[255, 255, 255]},{    "url": "https://gifimage.net/wp-content/uploads/2018/10/black-background-loading-gif-7.gif",   "colori":[14, 17, 31]},{    "url": "https://digitalsynopsis.com/wp-content/uploads/2016/06/loading-animations-preloader-gifs-ui-ux-effects-18.gif",    "colori":[255, 255, 255]},{    "url": "https://miro.medium.com/max/1600/1*r4K1PRHfbKG7NpoRx22K4A.gif",    "colori":[255, 255, 255]},{   "url": "https://www.demilked.com/magazine/wp-content/uploads/2016/06/gif-animations-replace-loading-screen-11.gif",    "colori":[255, 255, 255]},{    "url": "https://static-steelkiwi-dev.s3.amazonaws.com/media/filer_public/f5/2d/f52dbbc7-f0fe-4ef7-9192-1580de2da276/543aa75c-67ff-4b98-b1b9-12054ef3fbe9.gif",   "colori":[255, 255, 255]},{    "url": "https://lh3.googleusercontent.com/proxy/ScTXur4sxqZMBqT1JJ9LnQ59Dd_5LulIoaSvlwX5zJX8WmLS_E3ik9hW7YT5sM54ldSMRlJ8hhaBv5NusIBSfXOT0yQoGJ7Qwyi1XsCWxaYwT3V3pJz1ri1NV5wlAWs",    "colori":[255, 255, 255]}]'
      let roba=JSON.parse(gif);
      let num=Math.floor(Math.random() * 10);
      let divCar=document.getElementById("caricamento")
      divCar.src=roba[num].url;
      $(divCar).css({
        backgroundColor:"rgb("+roba[num].colori[0]+","+roba[num].colori[1]+","+roba[num].colori[2]+");"
      })
      console.log(num);
      //sta caricando
    },
    success: async function(data) {
      //ha finito
      async function stampa(dati, i) {

        let li = document.createElement("li");
        let a = document.createElement("a");

        a.innerText = dati.Indirizzo;
        idMat = "materian" + i;
        
        a.href = "#section" + i;
        a.id = idMat;

        li.appendChild(a);
        li.className = "menu-item";

        olHTML.append(li);

        document.getElementById("mobile").appendChild(li);
        let risultati = await fetchMaterie(dati.Indirizzo);

        let sezioni = $("#out2");

        let sezione = document.createElement("section");
        sezione.id = "section" + i;
        let nomeClasseSezione = "sezione" + i;
        sezione.className = nomeClasseSezione;

        let container = document.createElement("div");
        container.className = "container-fluid";
        container.style = "text-align:center;";

        let heading = document.createElement("div");
        heading.id = "heading";
        heading.className = "row";
        heading.style = "margin-top: 125px";

        let divIndirizzo = document.createElement("div");
        divIndirizzo.className = "col-md";
        divIndirizzo.style = "margin-bottom: 80px";
        divIndirizzo.innerText = dati.Indirizzo;

        heading.appendChild(divIndirizzo);

        container.appendChild(heading);

        let riga = document.createElement("div");
        riga.className = "row justify-content-center";

        let nomeClasseSeparatore = "separator" + i;
        for (let j = 0; j < risultati.length; j++) {
          let button = document.createElement("div");
          button.className = "button";
          button.id = "button-3";

          let cerchio = document.createElement("div");
          cerchio.id = "circle";

          let a = document.createElement("a");
          button.addEventListener("click", () => {
            sessionStorage.materia = risultati[j].IDMateria;
            window.history.pushState({}, "", "materie.html");
            console.log(window.location.href);
            document.getElementById("outJS").innerHTML = "";
            document.getElementById("out2").innerHTML = "";

            prendiAnnieParsali();
          });

          a.innerText = risultati[j].Materia;

          button.appendChild(cerchio);
          button.appendChild(a);

          riga.appendChild(button);
        }

        container.appendChild(riga);
        sezione.append(container);

        sezioni.append(sezione);
        let divisore = document.createElement("div"); //separator
        divisore.className = nomeClasseSeparatore;
        sezioni.append(divisore);
      }

      let olHTML = $("#outJS");
      indirizzi = data;
      for (let i = 0; i < data.length; i++) {
        await stampa(data[i], i);
      }

      

      document.getElementById("caricamento").className="nascosto";

      $('a[href*="#"]').on("click", function(e) {
        $("html,body").animate(
          {
            scrollTop: $($(this).attr("href")).offset().top - 100
          },
          500
        );
        e.preventDefault();
      });
    }
  });
}
async function fetchMaterie(indirizzoHovered) {
  let resFetch = await fetch(
    baseURL.replace(/#section(\d)/, "") +
      "API/materie.php?indirizzo=" +
      indirizzoHovered
  );
  let risposta = await resFetch.json();
  return risposta;
}

async function fetchAnni() {
  let resFetch = await fetch(
    baseURL.replace(/#section(\d)/, "") + "API/ottieniAnni.php"
  );
  let risposta = await resFetch.json();
  return risposta;
}

async function fetchRiassunto(id) {
  let risposta = await fetch(
    baseURL.replace(/#section(\d)/, "") + "API/riassunto.php?id=" + id
  );
  let json = await risposta.json();
  return json;
}

function stampaBottoni(dove, risultati, quanto) {
  if (quanto > risultati.length) {
    quanto = risultati.length;
  }
  for (let j = 0; j < quanto; j++) {
    let colSM = document.createElement("div");
    colSM.className = "col-sm";

    let divContenitore = document.createElement("div");
    divContenitore.className = "azienda";
    divContenitore.style = "cursor: pointer";

    let divTesto = document.createElement("div");
    divTesto.className = "testo";
    divTesto.style = "text-align: center;";
    divTesto.setAttribute("align", "center");

    let divImmagine = document.createElement("div");
    divImmagine.className = "bgimg";

    divContenitore.addEventListener("click", async () => {
      if (sessionStorage.riassunto) {
        sessionStorage.removeItem(riassunto);
      }

      let riassunto = await fetchRiassunto(risultati[j].ID);

      sessionStorage.riassunto = JSON.stringify(riassunto[0]);
      window.location.href = "mostraRiassunto.html";
    });

    let filtroIn = "blur(7px)";
    let filtroOut = "blur(0px)";

    $(divContenitore).hover(
      () => {
        divTesto.textContent = risultati[j].Titolo;

        $(divImmagine).css({
          filter: filtroIn,
          webkitFilter: filtroIn,
          mozFilter: filtroIn,
          oFilter: filtroIn,
          msFilter: filtroIn,
          transition: "all 0.5 ease-out",
          "-webkit-transition": "all 0.5s ease-out",
          "-moz-transition": "all 0.5s ease-out",
          "-o-transition": "all 0.5s ease-out"
        });

        $(divContenitore).css({
          border: "10px solid black",
          transition: "all 0.5 ease-out",
          "-webkit-transition": "all 0.5s ease-out",
          "-moz-transition": "all 0.5s ease-out",
          "-o-transition": "all 0.5s ease-out"
        });
      },
      () => {
        divTesto.innerHTML = "";
        $(divImmagine).css({
          filter: filtroOut,
          webkitFilter: filtroOut,
          mozFilter: filtroOut,
          oFilter: filtroOut,
          msFilter: filtroOut,
          transition: "all 0.5 ease-out",
          "-webkit-transition": "all 0.5s ease-out",
          "-moz-transition": "all 0.5s ease-out",
          "-o-transition": "all 0.5s ease-out"
        });

        $(divContenitore).css({
          border: "0px solid black",
          transition: "all 0.5 ease-out",
          "-webkit-transition": "all 0.5s ease-out",
          "-moz-transition": "all 0.5s ease-out",
          "-o-transition": "all 0.5s ease-out"
        });
      }
    );

    let obj = $(divImmagine).css({
      "background-image":
        'url("' +
        baseURL.replace(/#section(\d)/, "") +
        risultati[j].URLImmagine +
        '")'
    });

    divContenitore.appendChild(divTesto);
    divContenitore.appendChild(divImmagine);
    colSM.appendChild(divContenitore);
    dove.appendChild(colSM);
  }
}

async function getRiassunti(anno, materia, i) {
  let olHTML = $("#outJS");

  let data = await fetchRiassunti(anno, materia);

  let li = document.createElement("li");
  let a = document.createElement("a");

  a.innerText = anno + "°";
  a.href = "#section" + i;

  li.appendChild(a);
  li.style = "cursor: pointer; list-style-type: none; padding-left: 10px";

  olHTML.append(li);

  let risultati = data;
  let sezioni = $("#out2");

  let sezione = document.createElement("section");
  sezione.id = "section" + i;
  let nomeClasseSezione = "sezione" + i;
  sezione.className = nomeClasseSezione;

  let container = document.createElement("div");
  container.className = "container-fluid";
  container.style = "text-align:center;";

  let heading = document.createElement("div");
  heading.id = "heading";
  heading.className = "row";
  heading.style = "margin-top: 125px";

  let divIndirizzo = document.createElement("div");
  divIndirizzo.className = "col-md";

  divIndirizzo.setAttribute("data-sort", anno);
  divIndirizzo.style = "margin-bottom: 80px";
  divIndirizzo.innerText = anno + String.fromCharCode(176);

  heading.appendChild(divIndirizzo);

  container.appendChild(heading);

  let riga = document.createElement("div");
  riga.className = "row justify-content-center";

  let nomeClasseSeparatore = "separator" + i;

  stampaBottoni(riga, risultati, 3);

  let oldRiga = riga;
  container.appendChild(riga);

  riga = document.createElement("div");
  riga.className = "row justify-content-center";

  let buttonAltro = document.createElement("div");
  buttonAltro.className = "button";
  buttonAltro.id = "button-3";

  let cerchioAltro = document.createElement("div");
  cerchioAltro.id = "circle";

  let aAltro = document.createElement("a");
  buttonAltro.addEventListener("click", () => {
    if (risultati.length == 0) {
      return;
    }

    oldRiga.innerHTML = "";

    if (stato === "Meno") {
      stampaBottoni(oldRiga, risultati, risultati.length);
      aAltro.innerText = "Mostra Meno";
      stato = "Altro";
      return;
    }
    if (stato === "Altro") {
      stampaBottoni(oldRiga, risultati, 3);
      aAltro.innerText = "Mostra Altro";
      stato = "Meno";
      return;
    }
  });

  aAltro.innerText = "Mostra Altro";

  buttonAltro.appendChild(cerchioAltro);
  buttonAltro.appendChild(aAltro);

  riga.appendChild(buttonAltro);

  container.appendChild(riga);

  sezione.append(container);

  sezioni.append(sezione);
  let divisore = document.createElement("div");
  divisore.className = nomeClasseSeparatore;
  sezioni.append(divisore);

  $('a[href*="#"]').on("click", function(e) {
    $("html,body").animate(
      {
        scrollTop: $($(this).attr("href")).offset().top - 100
      },
      500
    );
    e.preventDefault();
  });
}

async function parsaAnni(anni) {
  let i = 0;

  for (i = 0; i < anni.length; i++) {
    await getRiassunti(anni[i], sessionStorage.materia, i);
  }
}

async function prendiAnnieParsali() {
  /*document.getElementById("caricamentoDiv").className = "loading visibile";*/
  sessionStorage.removeItem("riassunto");
  anni = await fetchAnni();

  await parsaAnni(anni);
  //document.getElementById("caricamentoDiv").className = "nascosta";
}

jQuery(document).ready(function($) {
  $("#brand").on("click", () => {
    sessionStorage.clear();
    window.location.reload();
  });
});

$(window).on("load", async () => {
  sessionStorage.clear();
  document.getElementById("outJS").innerHTML = "";
  document.getElementById("out2").innerHTML = "";
  fetchIndirizzi();
});

function scorlla(to) {
  window.location = window.location.href + to.id;
}

function isInViewport(elemento) {
  var elementTop = $(elemento).offset().top;
  var elementBottom = elementTop + $(elemento).outerHeight();
  var viewportTop = $(window).scrollTop();
  var viewportBottom = viewportTop + $(window).height();
  return elementBottom > viewportTop && elementTop < viewportBottom;
}

$(document, window).on("scroll", () => {
  let elemento = document.getElementsByClassName("navShadow")[0];
  if (elemento === null || typeof elemento === "undefined") {
    if (
      document.getElementById("fotoLogo").src ==
      "https://riassunty.altervista.org/logoBIANCO.jpg"
    ) {
      document.getElementsByClassName("showMenu")[0].className =
        "showMenu bianco";
        elemento.style="color:white";
        for(let i=0;i<indirizzi.length;i++){
          document.getElementById("materian"+i).className="bianco";
        } 
        
      return;
    }

    document.getElementById("fotoLogo").src =
      "https://riassunty.altervista.org/logoBIANCO.jpg";
    document.getElementsByClassName("showMenu")[0].className =
      "showMenu bianco";
      elemento.style="color:white";
      for(let i=0;i<indirizzi.length;i++){
        document.getElementById("materian"+i).className="bianco";
      } 
    return;
  }

  if (
    document.getElementById("fotoLogo").src ==
    "https://riassunty.altervista.org/logoNERO.jpg"
  ) {
    document.getElementsByClassName("showMenu")[0].className = "showMenu nero";
    elemento.style="color:black;";
    for(let i=0;i<indirizzi.length;i++){
      document.getElementById("materian"+i).className="nero";
      document.getElementById("outJS").className = "showMenu neroTutto";
    } 
    return;
  }
  document.getElementById("fotoLogo").src =
    "https://riassunty.altervista.org/logoNERO.jpg";
    elemento.style="color:black;";
  document.getElementsByClassName("showMenu")[0].className = "showMenu nero";
  for(let i=0;i<indirizzi.length;i++){
    document.getElementById("materian"+i).className="nero";
    document.getElementById("outJS").className = "showMenu neroTutto";
  } 
});

async function fetchRiassunti(anno, materia) {
  let url =
    baseURL.replace(/#section(\d)/, "") +
    "API/anteprima.php?idMateria=" +
    Number(materia) +
    "&anno=" +
    Number(anno);

  let risposta = await fetch(url);

  console.log(url);

  let json = await risposta.json();
  return json;
}
