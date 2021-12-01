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
        showPokemons(response.data)
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
    $("#content > .row").innerHTML = '';

    let output = '';

    pokemons.forEach((pokemon) => {
        let types = getProperties(pokemon.types, "type", 'name');
        let abilities = getProperties(pokemon.abilities, 'ability', 'name');

        output += `
        <div class="col-6">
                <article class="card p-2 pokemons">
                    <div class="row">
                        <div class="col-auto">
                            <div class="pokedex">
                                <img src="${ pokemon.sprites.front_default }"
                                     alt="${ pokemon.name }">
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="h2">${ pokemon.name }</h3>
                            <div class="row">
                                <div class="col-6">
                                    <h4>Tipo</h4>
                                    <ul>
                                    `;

        types.forEach((type) => output += `<li>${ type }</li>`);

        output += `</ul>
                                </div>
                                <div class="col-6">
                                    <h4>Habilidades</h4>
                                    <ul>
                                    `;

        abilities.forEach((ability) => output += `<li>${ ability }</li>`);

        output += `</ul>
                                </div>
                                <div class="col-6">
                                    <h4>Experiencia</h4>
                                    <img src="assets/img/cp.png" alt="cp" width="30"> ${ pokemon.base_experience }
                                </div>
                                <div class="col-6">
                                    <h4>Tamaño</h4>
                                    <img src="assets/img/pokedex.png" alt="cp" width="30"> Altura: ${ pokemon.height } / Peso: ${ pokemon.weight }
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>`;



    })

    $("#content > .row").innerHTML = output;

}


function getProperties(props, pos, value)
{
    let properties = [];

    if(props.length){
        properties = props.map((prop) => prop[pos][value]);
    }

    return properties;
}
