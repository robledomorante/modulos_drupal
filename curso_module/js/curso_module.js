(function ($) {
  "use strict";

  $(document).ready(function () {
    let seriePokemon = [];
    let pElegido = [];
    let todosPokemon = [];

    $("#pokemons div p").hover(
      function () {
        let idImg = $(this).children().attr("data-id");
        let imagen = $("#pokemon-" + idImg);
        imagen.css("transition", "transform 500ms ease-in-out");
        imagen.css("transform", "scale(1.2)");
      },
      function () {
        let idImg = $(this).children().attr("data-id");
        let imagen = $("#pokemon-" + idImg);
        imagen.css("transition", "transform 150ms ease-in-out");
        imagen.css("transform", "scale(1)");
        imagen.css("z-index", "auto");
      }
    );
    // abrimos un mensaje modal con los datos del pokemon
    $("#pokemons div p").click(function () {
      $("#dialog").dialog({
        autoOpen: false,
        show: {
          effect: "blind",
          duration: 1000,
        },
        hide: {
          effect: "explode",
          duration: 1000,
        },
      });

      let idpokemon = $(this).children().attr("data-id");
      let npokemon = $(this).children().attr("alt");
      let imgpokemon = $(this).children().attr("src");

      pElegido["idpokemon"] = idpokemon;
      //pElegido["npokemon"] = npokemon;
      //pElegido["imgpokemon"] = imgpokemon;

      let comprobacion = $.inArray(pElegido["idpokemon"], seriePokemon);
      if (comprobacion == -1) {
        // Array normal
        seriePokemon.push(pElegido["idpokemon"]);
        // Array de objetos. Aquí tenemos toda la información sobre los pokemon
        todosPokemon.push({ id: idpokemon, nombre: npokemon, img: imgpokemon });
      }

      agregarPokemon(todosPokemon, seriePokemon, idpokemon);
    });

    var agregarPokemon = function (todosPokemon, seriePokemon, idpokemon) {
      // Buscamos la posición del elemento
      let posicion = seriePokemon.indexOf(idpokemon);
      console.log(seriePokemon);
      // destructuring del objeto
      const { id, nombre, img } = todosPokemon[posicion];

      // Vaciamos en el caso que este lleno la ventana de diálogo
      $("#dialog p").empty();

      // agregamos el nuevo pokemon a la ventana de diálogo
      $("#dialog p").append(" id del pokemon: " + id + "<br>");
      $("#dialog p").append(" Nombre del pokemon: " + nombre + "<br>");
      $("#dialog p").append("<img src='" + img + "' >");
      $("#dialog").dialog("open");
      setTimeout(function () {
        $("#dialog").dialog("close");
      }, 3000);

      // mostramos la lista de los pokemon seleccionados.

      listaElegida(todosPokemon);
    };

    var listaElegida = function (todosPokemon) {
      // vaciamos el div de mostrar los elegidos
      $("#mostrarAll").empty();
      // a través del array de objetos todosPokemon pintamos todos los elementos seleccionados
      $.each(todosPokemon, function (indice, pokemon) {
        // ocultar y mostrar el último elemento elegido
        let cantidad = todosPokemon.length - 1;
        let eMostrar = todosPokemon[cantidad].id;
        // desestructuramos el array
        const { id, nombre, img } = pokemon;
        // mostramos la lista de los elegidos
        $(
          "#mostrarAll"
        ).append(`<div class="item-pokemonpeq" id=pokemonpeq-${id} >
        <h5>${nombre}</h5><p><img src=${img} alt=${nombre} data-id=${id} ></p></div>`);
        $("#pokemonpeq-" + eMostrar).hide();
        $("#pokemonpeq-" + eMostrar).show(500);
        // si hacemos clic en un elemento de la lista elegida la borramos
        $("#pokemonpeq-" + id).hover(
          function () {
          //let imagen = $(this).children().children();
          let imagenPeq = $("#pokemonpeq-" + id);
          imagenPeq.css("transition", "transform 500ms ease-in-out");
          imagenPeq.css("transform", "scale(1.2)");
          let gImag = $("#pokemon-" + id);
          gImag.css("transition", "transform 500ms ease-in-out");
          gImag.css("transform", "scale(1.1)");
          gImag.css({borderStyle:"solid", borderWidth:"0.3rem", borderColor:"red"});
        },
        function () {
          let imagenPeq = $("#pokemonpeq-" + id);
          imagenPeq.css("transition", "transform 150ms ease-in-out");
          imagenPeq.css("transform", "scale(1)");
          imagenPeq.css("z-index", "auto");
          let gImag = $("#pokemon-" + id);
          gImag.css("transition", "transform 150ms ease-in-out");
          gImag.css("transform", "scale(1)");
          gImag.css("z-index", "auto");
          gImag.css({borderStyle:"solid", borderWidth:"0.2rem", borderColor:"#5cb8f9"});
        }
        );
        $("#pokemonpeq-" + id).click(function () {
          todosPokemon.splice(indice, 1);
          seriePokemon.splice(indice, 1);
          // Carga de nuevo la lista Elegida
          listaElegida(todosPokemon);
          let gImag = $("#pokemon-" + id);
          gImag.css("transition", "transform 150ms ease-in-out");
          gImag.css("transform", "scale(1)");
          gImag.css("z-index", "auto");
          gImag.css({borderStyle:"solid", borderWidth:"0.2rem", borderColor:"#5cb8f9"});

        });
      });
    };
  });
})(jQuery);
