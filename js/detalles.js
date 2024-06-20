document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');

    let posts = JSON.parse(localStorage.getItem('posts')) || [];
    const post = posts.find(p => p.id === postId);

    if (post) {
        displayPostDetails(post);
    } else {
        document.getElementById('postDetails').innerHTML = '<p>Publicación no encontrada</p>';
    }
});

function displayPostDetails(post) {
    const postDetailsDiv = document.getElementById('postDetails');

    const postTitle = document.createElement('h2');
    postTitle.textContent = post.title;

    const postImage = document.createElement('img');
    postImage.src = post.image;

    const postDescription = document.createElement('p');
    postDescription.innerHTML = `<span>Descripción:</span> ${post.description}`;

    const postDate = document.createElement('p');
    postDate.innerHTML = `<span>Fecha de publicación:</span> ${post.date}`;

    const postLocation = document.createElement('p');
    postLocation.innerHTML = `<span>Ubicación:</span> ${post.location}`;

    const postContact = document.createElement('p');
    postContact.innerHTML = `<span>Contacto:</span> ${post.contact}`;

    postDetailsDiv.appendChild(postTitle);
    postDetailsDiv.appendChild(postImage);
    postDetailsDiv.appendChild(postDescription);
    postDetailsDiv.appendChild(postDate);
    postDetailsDiv.appendChild(postLocation);
    postDetailsDiv.appendChild(postContact);
}