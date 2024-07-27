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
   

    const postDate = document.createElement('p');
    postDate.textContent = `Fecha de publicación: ${post.date}`;

    const postLocation = document.createElement('p');
    postLocation.textContent = `Ubicación: ${post.location}`;

    const postContact = document.createElement('p');
    postContact.textContent = `Contacto: ${post.contact}`;

    postDetailsDiv.appendChild(postTitle);
    postDetailsDiv.appendChild(postImage);
    postDetailsDiv.appendChild(postDescription);
    postDetailsDiv.appendChild(postDate);
    postDetailsDiv.appendChild(postLocation);
    postDetailsDiv.appendChild(postContact);
}

