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

document.addEventListener('DOMContentLoaded', function() {
    getLocalStorageSize();
});
