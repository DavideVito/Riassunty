let cliccato = false;

function fetchIndirizzi() {
    sessionStorage.clear();
    $.ajax({
        url: "http://riassunty.altervista.org/API/indirizzi.php",
        method: "POST",
        crossDomain: true,
        success: async function (data) {
            async function stampa(dati, i) {
                let li = document.createElement("li");
                let a = document.createElement("a");
                //a.name = "Indirizzo";
                a.innerText = dati.Indirizzo;
                a.href = "#section" + i;
                //$(a).on("click", () => {
                ///    window.location.href = ;
                //});

                li.appendChild(a);
                li.style = "cursor: pointer; list-style-type: none; padding-left: 10px"

                //li.addEventListener("click", () => scorlla(a));


                olHTML.append(li);
                let risultati = await fetchMaterie(dati.Indirizzo);


                let sezioni = $("#out2");


                let sezione = document.createElement("section");
                sezione.id = "section" + i;
                let nomeClasseSezione = "sezione" + i;
                sezione.className = nomeClasseSezione;

                let container = document.createElement("div");
                container.className = "container";

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
                riga.className = "row";


                let nomeClasseSeparatore = "separator" + i;
                for (let j = 0; j < risultati.length; j++) {

                    //debugger;
                    let contenitore = document.createElement("div");
                    contenitore.id = "container";

                    let bottone = document.createElement("button");
                    bottone.className = "learn-more";

                    let spanCerchio = document.createElement("span");
                    spanCerchio.className = "circle";
                    spanCerchio.setAttribute('aria-hidden', 'true');

                    let spanFreccia = document.createElement("span");
                    spanFreccia.className = "icon arrow";

                    let spanTesto = document.createElement("span");
                    spanTesto.className = "button-text";

                    spanTesto.innerText = risultati[j].Materia;

                    spanCerchio.appendChild(spanFreccia);


                    bottone.appendChild(spanCerchio);
                    bottone.appendChild(spanTesto);




                    /* 
                    let nomeClasse = "materia" + j;
                    console.log(nomeClasse);
                    //nomeClasse = "";
                    let contenitore = document.createElement("div");
                    contenitore.className = "col-sm " + nomeClasse;
                    contenitore.style = "cursor: pointer; font-size: 20px;";
                    let p = document.createElement("p");
                    p.innerHTML =*/
                    /*
                    $(bottone).on('click', async function () 
                    {
                        let divAnni = document.createElement("div");
                        let testoMateria = risultati[j].Materia;
                        if ($(this).text() != testoMateria) {
                            $(this).text(testoMateria);
                            return;
                        }

                        testoMateria = "Chiudi";
                        let risultatiAnni = await fetchAnni();

                        divAnni.id = "divAnni";

                        for (let k = 0; k < risultatiAnni.length; k++) {
                            let anno = risultatiAnni[k];
                            let testo = document.createElement("p");
                            testo.innerText = anno;

                            $(testo).on("click", () => {
                                sessionStorage.clear();
                                sessionStorage.materiaCliccata = risultati[j].IDMateria;
                                sessionStorage.annoCliccato = anno;
                                window.location.href = "RiassuntiMaterie.html";
                            });
                            divAnni.appendChild(testo);
                        }
                        $(this).text(testoMateria);

                        contenitore.appendChild(divAnni);
                    });*/
                    contenitore.appendChild(bottone);
                    riga.appendChild(contenitore);
                    //contenitore.appendChild(p);
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

            $('a[href*="#"]').on('click', function (e) {
                $('html,body').animate({
                        scrollTop: $($(this).attr('href')).offset().top - 100
                    },
                    500);
                e.preventDefault();
            });


        }
    })
}
async function fetchMaterie(indirizzoHovered) {
    let resFetch = await fetch("API/materie.php?indirizzo=" +
        indirizzoHovered);
    let risposta = await resFetch.json();
    return risposta;
}

async function fetchAnni() {
    let resFetch = await fetch("API/ottieniAnni.php");
    let risposta = await resFetch.json();
    return risposta;
}



$(window).on("load", () => {
    fetchIndirizzi();
    /*$("#brand").on("click", () => {
        $("html, body").animate({
            scrollTop: 0
        });

    });*/
    document.getElementById("brand").style = "cursor: pointer"

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
};