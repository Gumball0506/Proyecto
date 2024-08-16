/*
    ----------------------------------------------------
    Comentario Anti-Copyright
    ----------------------------------------------------
    Este trabajo es realizado por:
    - Harold Ortiz Abra Loza
    - William Vega
    - Sergio Vidal
    - Elizabeth Campos
    - Lily Roque
    ----------------------------------------------------
    © 2024 Responsabilidad Social Universitaria. 
    Todos los derechos reservados.
    ----------------------------------------------------
*/

// Aquí puedes incluir tu código JavaScript.

document.addEventListener("DOMContentLoaded", function () {
  // Obtener el elemento del slider por su ID
  const slider = document.getElementById("slider");

  // Definir la carpeta de imágenes
  const imageFolder = "/imagenes/";

  // Lista de archivos de imágenes
  const imageFiles = ["img1.png", "img2.png", "img3.png", "img4.png"];

  // Generar dinámicamente las imágenes en el slider
  imageFiles.forEach((file) => {
    const div = document.createElement("div"); // Crear un contenedor para cada imagen
    div.classList.add("slider__section"); // Añadir la clase correspondiente
    const img = document.createElement("img"); // Crear el elemento de imagen
    img.src = imageFolder + file; // Establecer la ruta de la imagen
    img.alt = ""; // Añadir un atributo alt vacío
    img.classList.add("slider__img"); // Añadir la clase de la imagen
    div.appendChild(img); // Añadir la imagen al contenedor
    slider.appendChild(div); // Añadir el contenedor al slider
  });
  //para el slider banner apartir de aca
  // Obtener los botones de navegación por sus IDs
  const btnLeft = document.querySelector("#btn-left");
  const btnRight = document.querySelector("#btn-right");

  // Obtener todas las secciones del slider
  let sliderSections = document.querySelectorAll(".slider__section");
  // Obtener la última sección del slider
  let lastSliderSection = sliderSections[sliderSections.length - 1];

  // Mover la última sección al principio del slider
  slider.insertAdjacentElement("afterbegin", lastSliderSection);

  // Función para mover el slider hacia la derecha
  function moveRight() {
    let firstSliderSection = document.querySelectorAll(".slider__section")[0];
    slider.style.marginLeft = "-200%";
    slider.style.transition = "all 0.1s"; // Añadir transición
    setTimeout(function () {
      slider.style.transition = "none";
      slider.insertAdjacentElement("beforeend", firstSliderSection); // Mover la primera sección al final
      slider.style.marginLeft = "-100%";
    }, 500); // Esperar 300 ms antes de ejecutar el código
  }

  // Función para mover el slider hacia la izquierda
  function moveLeft() {
    let sliderSections = document.querySelectorAll(".slider__section");
    let lastSliderSection = sliderSections[sliderSections.length - 1];
    slider.style.marginLeft = "0";
    slider.style.transition = "all 0.1s"; // Añadir transición
    setTimeout(function () {
      slider.style.transition = "none";
      slider.insertAdjacentElement("afterbegin", lastSliderSection); // Mover la última sección al principio
      slider.style.marginLeft = "-100%";
    }, 500); // Esperar 300 ms antes de ejecutar el código
  }

  // Añadir eventos de clic a los botones de navegación
  btnRight.addEventListener("click", function () {
    moveRight();
  });

  btnLeft.addEventListener("click", function () {
    moveLeft();
  });

  // Mover el slider automáticamente cada 5 segundos
  setInterval(function () {
    moveRight();
  }, 5000);
});
