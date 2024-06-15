const slider = document.querySelector("#slider");
let sliderSection = document.querySelectorAll(".slider__section");
let sliderSectionlast = sliderSection[sliderSection.length - 1];
const btnleft = document.querySelector("#btn-left");
const btnright = document.querySelector("#btn-right");
slider.insertAdjacentElement("afterbegin", sliderSectionlast);
function moverDerecha() {
  let sliderSectionfirst = document.querySelectorAll(".slider__section")[0];
  slider.style.marginleft = "-200%";
  slider.style.transition = "all 0.5s";
  setTimeout(function () {
    slider.style.transition = "none";
    slider.insertAdjacentElement("beforeend", sliderSectionfirst);
    slider.style.marginleft = "-100%";
  }, 100);
}
function moverIzquierda() {
  let sliderSection = document.querySelectorAll(".slider__section");
  let sliderSectionlast = sliderSection[sliderSection.length - 1];
  slider.style.marginleft = "0";
  slider.style.transition = "all 0.5s";
  setTimeout(function () {
    slider.style.transition = "none";
    slider.insertAdjacentElement("afterbegin", sliderSectionlast);
    slider.style.marginleft = "-100%";
  }, 100);
}
btnright.addEventListener("click", function () {
  moverDerecha();
});
btnleft.addEventListener("click", function () {
  moverIzquierda();
});
setInterval(function () {
  moverDerecha();
}, 5000);
