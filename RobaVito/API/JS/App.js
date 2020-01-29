function getScuole() {
    console.log(window.location.href);
    $.ajax({
        url: "API/PHP/SelezionaScuola.php",
        success: function (data) {
            let s = JSON.parse(data);
            let scuole = [];
            for (let i = 0; i < s.length; i++) {
                let s1 = Scuola.parsa(s[i]);
                scuole.push(s1);
            }


            let daStampare = ``;
            for (scuola of scuole) {
                daStampare += scuola.stampa();
            }

            daStampare += "";
            document.getElementById("scuole").innerHTML = daStampare;
        }
    })
}

function getIndirizzi() {

debugger;
    let id = window.location.search.substring(4);
    if (!id) {
        window.location.href = "../"
    }
    console.log(id);
    let indirizzo = "../API/PHP/SelezionaTuttiIndirizzi.php?id=" + id;
    console.log(indirizzo);
    $.ajax({
        url: indirizzo,
        success: function (data) {
            let indirizziJSON = JSON.parse(data);
            let indirizziSeri = [];

            for (let i = 0; i < indirizziJSON.length; i++) {
                indirizziSeri.push(Indirizzo.parsa(indirizziJSON[i]))
            }
            let ds = "";
            for (indirizzo of indirizziSeri) {
                ds += indirizzo.stampa()
            }
            document.getElementById("out").innerHTML = ds;
        }
    })
}

function getRiassunti(id) {

    console.log(id);
    debugger;
    let indirizzo = "../API/PHP/GetRiassunti.php";
    console.log(indirizzo);
    $.ajax({
        url: indirizzo,
        data: {
            id
        },
        method: "POST",
        success: function (data) {
            if (data.length == 0) {
                alert("Non ci sono materie per questo corso");
                return;
            }
            sessionStorage.riassunti = data;
            window.location.href = "../VisualizzaRiassunti"
        }
    })
}

function getMaterie(id) {
    console.log(id)
    $.ajax({
        url: "../API/PHP/GetMaterie.php",
        data: {
            id
        },
        method: "POST",
        success: function (data) {
            debugger;
            if (data.length == 0) {
                alert("Non ci sono materie per questo corso");
                return;
            }


            sessionStorage.materie = data;

            window.location.href = "../VisualizzaMaterie"
        }
    })

}

function getAnniScolastici(id) {

    $.ajax({
        url: "../API/PHP/GetAnniScolastici.php",
        data: {
            id: id
        },
        method: "POST",
        success: function (data) {
            sessionStorage.anni = data;
            window.location.href = "../VisualizzaAnni/"


        }
    })
}

class File {
    constructor(nome, id, posizione) {
        this.nome = nome;
        this.id = id;
        this.posizione = posizione;
    }
    stampa() {
        return `<a href="../VisualizzaRiassunto/?id=${this.id}"> ${this.nome} </a>`
    }
    static parsa(scuolaJSON) {
        return new File(scuolaJSON.Nome, scuolaJSON.IDFile, scuolaJSON.Posizione);
    }
}

class Materia {
    constructor(nome, id) {
        this.nome = nome;
        this.id = id;
    }
    stampa() {
        return `<button onclick="getRiassunti(${this.id})"> ${this.nome} </button>`
    }
    static parsa(scuolaJSON) {
        return new Materia(scuolaJSON.Nome, scuolaJSON.IDMateria);
    }
}

class Scuola {
    constructor(nome, id) {
        this.nome = nome;
        this.id = id;
    }
    stampa() {
        return `<a href="VisualizzaIndirizzi/?id=${this.id}"> ${this.nome} </a>`
    }
    static parsa(scuolaJSON) {
        return new Scuola(scuolaJSON.Nome, scuolaJSON.IDScuola);
    }
}

class Indirizzo {
    constructor(nome, id) {
        this.nome = nome;
        this.id = id;
    }
    stampa() {
        return `<button onclick="getAnniScolastici(${this.id})"> ${this.nome} </button>`
    }
    static parsa(scuolaJSON) {
        return new Indirizzo(scuolaJSON.Nome, scuolaJSON.IDIndirizzo);
    }
}

class Anno {
    constructor(nome, id) {
        this.nome = nome;
        this.id = id;
    }
    stampa() {
        return `<button onclick="getMaterie(${this.id})"> ${this.nome} </button>`
    }
    static parsa(scuolaJSON) {
        return new Anno(scuolaJSON.Nome, scuolaJSON.IDAnno);
    }
}