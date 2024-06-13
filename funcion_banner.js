let currentIndex = 0;
let images = [];

document.getElementById('uploadButton').addEventListener('click', () => {
    const fileInput = document.getElementById('imageUpload');
    const file = fileInput.files[0];

    if (file) {
        saveImageLocally(file);
    }
});

function saveImageLocally(file) {
    const formData = new FormData();
    formData.append('image', file);

    // Simulación de guardar la imagen localmente
    const imgPath = URL.createObjectURL(file);
    const imgElement = document.createElement('img');
    imgElement.src = imgPath;
    imgElement.alt = 'Imagen de banner';
    imgElement.className = 'banner-image';
    document.getElementById('bannerImages').appendChild(imgElement);

    // Agregar imagen a la lista para el carrusel
    images.push(imgElement);

    // Actualizar el carrusel si es la primera imagen
    if (images.length === 1) {
        startCarousel();
    }

    // Simulación de respuesta exitosa
    alert('Imagen subida exitosamente');
}

function startCarousel() {
    setInterval(() => {
        showNextImage();
    }, 20000); // Cambiar de imagen cada 20 segundos
}

function showNextImage() {
    const totalImages = images.length;
    if (totalImages > 1) {
        images[currentIndex].style.transform = 'translateX(-100%)'; // Mueve la imagen actual hacia la izquierda

        currentIndex = (currentIndex + 1) % totalImages;

        images[currentIndex].style.transform = 'none'; // Asegura que la siguiente imagen esté en su posición original
    }
}
