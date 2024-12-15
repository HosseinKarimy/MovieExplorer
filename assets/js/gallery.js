const images = document.querySelectorAll('.gallery-thumbnails>img')
const mainImage = document.getElementById('mainImage')

images.forEach((image)=> image.addEventListener('click' , 
    () => {
        mainImage['src'] = image.src;
    }
));