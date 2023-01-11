let files = [], formData = new FormData();
let dbObjects, dbFiles;

dragArea = document.querySelector('.drag-area'),
input = document.querySelector('.drag-area input'),
button = document.querySelector('.upload-card button'),
select = document.querySelector('.drag-area .select-file'),
container = document.querySelector('.upload-container');

if (input.getAttribute('data-files') && input.getAttribute('data-files') !== null) {
	dbObjects = JSON.parse(input.dataset.files);
	dbFiles = dbObjects.map(obj => {
		return new File([atob(obj.content)], obj.name, { type: obj.type });
	});

	let list = new DataTransfer();
	for (let i = 0; i < dbFiles.length; i++) {
		if (!files.some(e => e.name == dbFiles.name)) files.push(dbFiles[i])
	}

	showImages();
}

/** CLICK LISTENER */
select.addEventListener('click', () => input.click());

/* INPUT CHANGE EVENT */
input.addEventListener('change', () => {
	let file = input.files;

	// if user select no image
	if (file.length == 0) return;

	for (let i = 0; i < file.length; i++) {
		if (file[i].type.split("/")[0] != 'image') continue;
		if (!files.some(e => e.name == file[i].name)) files.push(file[i])
	}

	updateInputFiles();
	showImages();
});

/** SHOW IMAGES */
async function showImages() {

	// Create a list of promises for each file in `files`
	const filePromises = files.map(async (curr, index) => {
		const url = '../assets/images/listings/' + curr.name;
		// Try to get the file using `$.get`
		try {
			await $.get(url);
			return `
		  <div class="image">
			<span onclick="delImage(${index})">&times;</span>
			<img src="${url}" />
		  </div>
		`;
		} catch (error) {
			// If the file doesn't exist, create an object URL for the file
			return `
		  <div class="image">
			<span onclick="delImage(${index})">&times;</span>
			<img src="${URL.createObjectURL(curr)}" />
		  </div>
		`;
		}
	});

	// Wait for all promises to resolve
	const fileHTML = await Promise.all(filePromises);
	// Set the inner HTML of `container` to the concatenated HTML of the files
	container.innerHTML = fileHTML.join('');
}

/* DELETE IMAGE */
function delImage(index) {

	if (typeof dbObjects !== 'undefined') {
		for (let i = 0; i < dbObjects.length; i++) {
			if (files[index].name == dbObjects[i].name) {
				$.ajax({
					type: 'POST',
					url: 'deleteImage/',
					data: { idFotoBisnis: dbObjects[i].id },
					success: function(response) {
						console.log('Image deleted from database');
					},
					error: function() {
						console.error('An error occurred while deleting the image from the database');
					}
				});
			}
		}
	}

	files.splice(index, 1);

	updateInputFiles();
	showImages();
}


/* DRAG & DROP */
dragArea.addEventListener('dragover', e => {
	e.preventDefault()
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
	for (let i = 0; i < file.length; i++) {
		/** Check selected file is image */
		if (file[i].type.split("/")[0] != 'image') continue;
		if (!files.some(e => e.name == file[i].name)) files.push(file[i])
	}

	updateInputFiles();
	showImages();
});

/* UPDATE INPUT FILES */
function updateInputFiles() {
	let list = new DataTransfer();
	for (let i = 0; i < files.length; i++) {
		list.items.add(files[i]);
	}
	input.files = list.files;
}