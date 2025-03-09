let currentImage = 0;
const images = document.querySelectorAll('.carousel-image');
const carouselNavButtons = document.querySelectorAll('.carousel-nav .nav-btn');

function changeImage(index) {
  images[currentImage].classList.remove('active');
  carouselNavButtons[currentImage].classList.remove('active');
  currentImage = index;
  images[currentImage].classList.add('active');
  carouselNavButtons[currentImage].classList.add('active');
}

// Auto-rotate images every 9 seconds
setInterval(() => {
  changeImage((currentImage + 1) % images.length);
}, 9000);

document.querySelector('.place-order').addEventListener('click', () => {
    window.location.href = 'Status.html';
})