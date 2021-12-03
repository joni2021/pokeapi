let $ = function (val) {
    return document.querySelectorAll(val).length > 1 ? document.querySelectorAll(val) : document.querySelector(val);
};

const form = $("form");

const apiUrl = "api/actions/buscar_pokemon.php";
const errorGif = "assets/img/psydock.gif";
const successGif = "assets/img/psydock.gif";

/*
* Data que va a recibir el mensaje de error
* @var message
*/
let message = {
    status: "danger",
    message: ""
}

form.addEventListener('submit', function (ev) {
    ev.preventDefault();

    showLoader();

    $("#content").className = "container mt-5 d-none fade";


    const pokemon = $('input[type=search]').value.trim();

    if (is_null(pokemon)) {
        message = {
            status: "danger",
            message: "Por favor indicá algún dato del pokemon"
        }

        showMessage(message);
        hideLoader();
        return false;
    }

    fetch(apiUrl + "?pokemon=" + encodeURIComponent(pokemon),{
        method: 'GET',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(response => {
        hideMessage();
        hideLoader();

        showPokemons(response.data)

        $("#content").className = "container mt-5 d-block";
    })
    .catch(error => {
        message = {
            status: "danger",
            message: error
        }
        showMessage(message);
        hideLoader();
    })

})


/*
* Muestra el mensaje de error
*
* @param message
 */
let showMessage = function (message) {
    $('#errors').innerHTML = `
    <div class="alert alert-${message.status} d-flex align-items-center" role="alert">
      <img width="76" alt="error" src="${message.status === 'danger' ? errorGif : successGif}" />
      <div>
        ${message.message}
      </div>
    </div>`;
}

/*
* Oculta el mensaje de error
 */
let hideMessage = function () {
    let alert = $(".alert");

    if(!alert){
        return false;
    }

    $('.alert').parentElement.removeChild(alert);
}

/*
* Muestra el loader
 */
let showLoader = function () {
    $("#loader").innerHTML = `<img src="assets/img/pokedex-loader.gif" alt="loader image" class="img-responsive d-block m-auto w-25"><p class="text-center"> Buscando...</p>`;

}

/*
* Oculta el loader
 */
let hideLoader = function () {
    let img = $("#loader img");
    let p = $("#loader p");

    if(!img){
        return false;
    }

    img.parentElement.removeChild(img);
    p.parentElement.removeChild(p);
}


/*
* Chequea si un valor está vacío
*
* @param val String
* @return String
 */
function is_null(val) {
    return "" === val.trim() || !val
}

/*
* Muestra el listado de pokemons
*
* @param pokemons Array
*/
function showPokemons(pokemons)
{
    let row = $("#content > .row");

    row.innerHTML = '';

    let output = '';

    pokemons.forEach((pokemon) => {
        let types = getProperties(pokemon.types, 'name');
        let abilities = getProperties(pokemon.abilities, 'name');

        output += `
        <div class="col-12 col-lg-6 my-3">
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

    row.innerHTML = output;

}

/*
* Devuelve las propiedades relacionadas del pokemon
*
* @param props Array
* @param pos
* @param value
* @return properties Array
*/
function getProperties(props, value)
{
    let properties = [];

    if(props.length){
        properties = props.map((prop) => prop[value]);
    }

    return properties;
}
