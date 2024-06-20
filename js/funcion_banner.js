// Seleccionar el elemento del slider y sus secciones
const slider = document.querySelector("#slider");
let sliderSection = document.querySelectorAll(".slider__section");
let sliderSectionlast = sliderSection[sliderSection.length - 1];

// Seleccionar los botones de control del slider
const btnleft = document.querySelector("#btn-left");
const btnright = document.querySelector("#btn-right");

// Mover la última sección al inicio del slider al cargar la página
slider.insertAdjacentElement("afterbegin", sliderSectionlast);

// Función para mover el slider hacia la derecha
function moverDerecha() {
  // Obtener la primera sección del slider
  let sliderSectionfirst = document.querySelectorAll(".slider__section")[0];

  // Mover el slider hacia la izquierda (dejar la segunda sección visible)
  slider.style.marginLeft = "-200%";
  slider.style.transition = "all 0.5s";

  // Luego de la transición, resetear el estilo y mover la primera sección al final
  setTimeout(function () {
    slider.style.transition = "none";
    slider.insertAdjacentElement("beforeend", sliderSectionfirst);
    slider.style.marginLeft = "-100%";
  }, 500); // 500ms de timeout, debe coincidir con la duración de la transición
}

// Función para mover el slider hacia la izquierda
function moverIzquierda() {
  // Obtener todas las secciones del slider
  let sliderSection = document.querySelectorAll(".slider__section");
  let sliderSectionlast = sliderSection[sliderSection.length - 1];

  // Mover el slider hacia la derecha (dejar la penúltima sección visible)
  slider.style.marginLeft = "0";
  slider.style.transition = "all 0.5s";

  // Luego de la transición, resetear el estilo y mover la última sección al inicio
  setTimeout(function () {
    slider.style.transition = "none";
    slider.insertAdjacentElement("afterbegin", sliderSectionlast);
    slider.style.marginLeft = "-100%";
  }, 500); // 500ms de timeout, debe coincidir con la duración de la transición
}

// Evento para mover el slider hacia la derecha cuando se hace clic en el botón derecho
btnright.addEventListener("click", function () {
  moverDerecha();
});

// Evento para mover el slider hacia la izquierda cuando se hace clic en el botón izquierdo
btnleft.addEventListener("click", function () {
  moverIzquierda();
});

// Intervalo para mover automáticamente el slider hacia la derecha cada 5 segundos
setInterval(function () {
  moverDerecha();
}, 5000);
