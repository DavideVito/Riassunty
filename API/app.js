let cliccato = false;

let riassunti = [];
let massimo = 3;

let baseURL = window.location.href;
baseURL = baseURL.replace(new RegExp(/([a-zA-Z0-9\s_\\.\-\(\):])+(.html|.php)$/), "");
baseURL = "https://vps.lellovitiello.tk/Riassunty/";


function fetchIndirizzi() {
  sessionStorage.clear();
  let url = baseURL.replace(/#section(\d)/, "") + "API/indirizzi.php";

  $.ajax({
    url: url,
    method: "POST",
    beforeSend: () => {
      document.getElementById("loadingImage").className = "visibile";
    },
    success: async function (data) {
      document.getElementById("loadingImage").className = "nascosta";
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
            window.location.reload();
            //alert(sessionStorage.materia);
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
      for (let i = 0; i < data.length; i++) {
        await stampa(data[i], i);
      }

      $('a[href*="#"]').on("click", function (e) {
        $("html,body").animate({
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
    baseURL.replace(/#section(\d)/, "") + "API/materie.php?indirizzo=" + indirizzoHovered
  );
  let risposta = await resFetch.json();
  return risposta;
}

async function fetchAnni() {
  let resFetch = await fetch(baseURL.replace(/#section(\d)/, "") + "API/ottieniAnni.php");
  let risposta = await resFetch.json();
  return risposta;
}

async function fetchRiassunto(nome) {
  let risposta = await fetch(baseURL.replace(/#section(\d)/, "") + "API/riassunto.php?nome=" + nome);
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
      let riassunto = await fetchRiassunto(risultati[j].Titolo);

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

    let obj =
      $(divImmagine).css({
        'background-image': 'url("' + baseURL.replace(/#section(\d)/, "") + risultati[j].URLImmagine + '")'
      });


    divContenitore.appendChild(divTesto);
    divContenitore.appendChild(divImmagine);
    colSM.appendChild(divContenitore);
    dove.appendChild(colSM);
  }
}

async function getRiassunti(anno, materia, i) {
  //

  let olHTML = $("#outJS");

  var data = {
    idMateria: materia,
    anno: anno
  };

  let formData = new FormData();
  formData.append("idMateria", materia);
  formData.append("anno", anno);

  let risposta = await fetch(baseURL.replace(/#section(\d)/, "") + "API/anteprima.php", {
    method: "POST",
    body: formData
  });

  data = await risposta.json();

  let li = document.createElement("li");
  let a = document.createElement("a");

  a.innerText = anno;
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
  divIndirizzo.innerText = anno;

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
    //  ;

    if (risultati.length == 0) {
      return;
    }
    oldRiga.innerHTML = "";
    riga.innerHTML = "";

    stampaBottoni(oldRiga, risultati, risultati.length);
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

  $('a[href*="#"]').on("click", function (e) {
    $("html,body").animate({
        scrollTop: $($(this).attr("href")).offset().top - 100
      },
      500
    );
    e.preventDefault();
  });
}

async function parsaAnni(anni) {
  let i = 0;
  document.getElementById("loadingImage").className = "visibile";
  for (i = 0; i < anni.length; i++) {
    await getRiassunti(anni[i], sessionStorage.materia, i);
  }
  document.getE

  document.getElementById("loadingImage").className = "nascosta";
}

jQuery(document).ready(function ($) {
  $("#brand").on("click", () => {
    sessionStorage.clear();
    window.location.reload();
  });

  if (window.history && window.history.pushState) {
    window.history.replaceState("#p", null, "");

    $(window).on("popstate", function () {
      if (sessionStorage.materia) {
        sessionStorage.clear();
        window.location.reload();
      }
      return;
    });
  }
});

let anni = null;

$(window).on("load", async () => {
  if (sessionStorage.materia) {

    anni = await fetchAnni();
    await parsaAnni(anni);

  } else {
    fetchIndirizzi();
  }

  document.getElementById("brand").style = "cursor: pointer";
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
  if(elemento === null || typeof elemento === "undefined")
  {
    document.getElementById("fotoLogo").src = "https://riassunty.altervista.org/logoBIANCO.jpg";
    return;
  }
  document.getElementById("fotoLogo").src = "https://riassunty.altervista.org/logoNERO.jpg";

})

async function fetchRiassunti(anno, materia) {
  let risposta = await fetch(
    baseURL.replace(/#section(\d)/, "") +
    "API/anteprima.php?idMateria=" +
    Number(materia) +
    "&anno=" +
    Number(anno)
  );
  let json = await risposta.json();
  return json;
}