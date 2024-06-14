document.addEventListener("DOMContentLoaded", function () {
  const slider = document.getElementById("slider");
  const imageFolder = "/imagenes/"; // Carpeta de imágenes
  const imageFiles = ["img1.png", "img2.png", "img3.png", "img4.png"]; // Lista de imágenes

  // Generar dinámicamente las imágenes en el slider
  imageFiles.forEach((file) => {
    const div = document.createElement("div");
    div.classList.add("slider__section");
    const img = document.createElement("img");
    img.src = imageFolder + file;
    img.alt = "";
    img.classList.add("slider__img");
    div.appendChild(img);
    slider.appendChild(div);
  });

  const btnLeft = document.querySelector("#btn-left");
  const btnRight = document.querySelector("#btn-right");

  let sliderSections = document.querySelectorAll(".slider__section");
  let lastSliderSection = sliderSections[sliderSections.length - 1];

  slider.insertAdjacentElement("afterbegin", lastSliderSection);

  function moveRight() {
    let firstSliderSection = document.querySelectorAll(".slider__section")[0];
    slider.style.marginLeft = "-200%";
    slider.style.transition = "all 0.9s";
    setTimeout(function () {
      slider.style.transition = "none";
      slider.insertAdjacentElement("beforeend", firstSliderSection);
      slider.style.marginLeft = "-100%";
    }, 300);
  }

  function moveLeft() {
    let sliderSections = document.querySelectorAll(".slider__section");
    let lastSliderSection = sliderSections[sliderSections.length - 1];
    slider.style.marginLeft = "0";
    slider.style.transition = "all 0.9s";
    setTimeout(function () {
      slider.style.transition = "none";
      slider.insertAdjacentElement("afterbegin", lastSliderSection);
      slider.style.marginLeft = "-100%";
    }, 300);
  }

  btnRight.addEventListener("click", function () {
    moveRight();
  });

  btnLeft.addEventListener("click", function () {
    moveLeft();
  });

  setInterval(function () {
    moveRight();
  }, 5000);
});
