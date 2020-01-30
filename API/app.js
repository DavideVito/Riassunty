let cliccato = false;

let baseURL = "https://riassunty.altervista.org/";

function fetchIndirizzi() {
    sessionStorage.clear();
    $.ajax({
        url: baseURL + "API/indirizzi.php",
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
                container.className = "container-fluid";
                container.style="text-align:center;";

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
                    cerchio.id = "circle"

                    let a = document.createElement("a");
                    a.href = "#";
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
    let resFetch = await fetch(baseURL + "API/materie.php?indirizzo=" +
        indirizzoHovered);
    let risposta = await resFetch.json();
    return risposta;
}

async function fetchAnni() {
    let resFetch = await fetch(baseURL + "API/ottieniAnni.php");
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