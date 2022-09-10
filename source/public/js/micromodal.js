'use strict'
const images = document.querySelectorAll('.image')

images.forEach(image => {
    image.addEventListener('click', function (e) {
        const imageName = e.target.dataset.id.substr(0, 6)
        const imageId = e.target.dataset.id.replace(imageName + '_', '')
        const imageFile = e.target.dataset.file
        const imagePath = e.target.dataset.path
        const modal = e.target.dataset.modal
        document.getElementById(imageName + '_thumbnail').src = imagePath + '/' + imageFile
        document.getElementById(imageName + '_hidden').value = imageId
        // MicroModal.close(modal);
    })
})  