/*

Image Upload JS

*/

let photo;
dragArea = document.querySelector('.drag-area'),
    visibleArea = document.querySelector('.drag-area .visible'),
    input = document.querySelector('.drag-area input'),
    button = document.querySelector('.upload-card button'),
    select = document.querySelector('.drag-area #select-file'),
    deleteBtn = document.querySelector('.drag-area #delete-file'),
    container = document.querySelector('.upload-container');

/* CLICK LISTENER */
select.addEventListener('click', () => input.click());

/* INPUT CHANGE EVENT */
input.addEventListener('change', () => {
    let file = input.files;

    // if user select no image
    if (file.length == 0) return;

    photo = file;

    updateInputFiles;
    showImages();
});

/** SHOW IMAGES */
function showImages() {
    input.files
    deleteBtn.style.display = 'block';
    dragArea.style.background = `url('${URL.createObjectURL(photo[0])}') center / cover`;
}

/* DELETE IMAGE */
function delImage() {
    photo = null;
    deleteBtn.style.display = 'none';
    dragArea.style.background = '#121212';

    updateInputFiles();
}

deleteBtn.addEventListener('click', () => delImage(0));

/* IMAGE HOVER */
$(dragArea).hover(function () {
    $(visibleArea).css({ 'box-shadow': 'inset 0 0 0 2000px rgba(0, 0, 0, 0.5)', 'opacity': '1' });
}, function () {
    $(visibleArea).css({ 'box-shadow': 'inset 0 0 0 2000px rgba(0, 0, 0, 0.3)', 'opacity': '0.4' });
});

/* DRAG & DROP */
dragArea.addEventListener('dragover', e => {
    dragArea.classList.add('dragover')
})

/* DRAG LEAVE */
dragArea.addEventListener('dragleave', e => {
    e.preventDefault()
    dragArea.classList.remove('dragover')
});

/* DROP EVENT */
dragArea.addEventListener('drop', e => {
    e.preventDefault()
    dragArea.classList.remove('dragover');

    let file = e.dataTransfer.files;

    if (file.type.split("/")[0] != 'image') return;

    photo = file;

    updateInputFiles
    showImages();
});

/* UPDATE INPUT FILES */

function updateInputFiles() {
    let list = new DataTransfer();
    for (let i = 0; i < photo.length; i++) {
        list.items.add(photo[i]);
    }
    input.files = list.files;
}

