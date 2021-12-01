let $ = function (val) {
    return document.querySelectorAll(val).length > 1 ? document.querySelectorAll(val) : document.querySelector(val);
};

const form = $("form");

const apiUrl = "api/actions/buscar_pokemon.php";
const errorGif = "assets/img/psydock.gif";
const successGif = "assets/img/psydock.gif";

let message = {
    status: "danger",
    message: ""
}

let showMessage = function (message) {
    $('#errors').innerHTML = `
    <div class="alert alert-${message.status} d-flex align-items-center" role="alert">
      <img width="76" src="${message.status === 'danger' ? errorGif : successGif}" />
      <div>
        ${message.message}
      </div>
    </div>`;
}

let hideMessage = function () {
    let alert = $(".alert");

    if(!alert){
        return false;
    }

    $('.alert').parentElement.removeChild(alert);
}


form.addEventListener('submit', function (ev) {
    ev.preventDefault();

    const pokemon = $('input[type=search]').value;

    if (is_null(pokemon)) {
        message = {
            status: "danger",
            message: "Por favor indicá algún dato del pokemon"
        }

        showMessage(message);
        return false;
    }

    fetch(apiUrl + "?pokemon=" + pokemon,{
        method: 'GET',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(response => {
        hideMessage();
        showMessage(response.data)
    })
    .catch(error => {
        message = {
            status: "danger",
            message: error
        }
        showMessage(message);
    })

})

function is_null(val) {
    return "" === val.trim() || !val
}

function showPokemons(pokemons)
{
    // TODO: Armar el card, hacer el foreach recorriendo los pokemons e insertando los cards en cada vuelta.
    let card = `
    `
}