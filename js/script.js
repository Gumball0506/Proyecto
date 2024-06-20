document.getElementById('postForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;
    const imageInput = document.getElementById('image');

    if (imageInput.files && imageInput.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const imageUrl = e.target.result;
            const postId = Date.now().toString(); 

            const post = {
                id: postId,
                title: title,
                image: imageUrl,
                description: description,
                date: new Date().toLocaleString(),
                location: 'Desconocido', 
                contact: 'contacto@ejemplo.com'
            };

            savePost(post);
            displayPost(post);
            document.getElementById('postForm').reset();
        };

        reader.readAsDataURL(imageInput.files[0]);
    }
});

function savePost(post) {
    try {
        let posts = JSON.parse(localStorage.getItem('posts')) || [];
        posts.push(post);
        localStorage.setItem('posts', JSON.stringify(posts));
        console.log('Post saved:', post);
    } catch (error) {
        console.error('Error saving post:', error);
    }
}

function displayPost(post) {
    const postDiv = document.createElement('div');
    postDiv.classList.add('post');
    postDiv.dataset.id = post.id;

    const postTitle = document.createElement('h2');
    postTitle.textContent = post.title;

    const postImage = document.createElement('img');
    postImage.src = post.image;

    const postDescription = document.createElement('p');
    postDescription.textContent = post.description;

    postDiv.appendChild(postTitle);
    postDiv.appendChild(postImage);
    postDiv.appendChild(postDescription);

    postDiv.addEventListener('click', function() {
        window.location.href = `/index.html?id=${post.id}`;
    });

    document.querySelector('.posts-container').appendChild(postDiv);
}

document.addEventListener('DOMContentLoaded', function() {
    try {
        let posts = JSON.parse(localStorage.getItem('posts')) || [];
        posts.forEach(displayPost);
        console.log('Posts loaded:', posts);
    } catch (error) {
        console.error('Error loading posts:', error);
    }

    getLocalStorageSize();
});

function getLocalStorageSize() {
    let total = 0;
    for (let x in localStorage) {
        if (localStorage.hasOwnProperty(x)) {
            total += ((localStorage[x].length + x.length) * 2);
        }
    }
    console.log('LocalStorage size (bytes):', total);
    return total;
}