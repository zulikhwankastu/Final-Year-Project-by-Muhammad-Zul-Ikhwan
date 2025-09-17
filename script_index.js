// JavaScript for Testimonial Slider
let currentSlide = 0; // Current slide index
const slides = document.querySelectorAll('.testimonial-item'); // All testimonial items

// Function to navigate slides
function moveSlide(direction) {
    slides[currentSlide].classList.remove('active'); // Remove active class from the current slide
    currentSlide = (currentSlide + direction + slides.length) % slides.length; // Update slide index
    slides[currentSlide].classList.add('active'); // Add active class to the new slide
}

// Initialize slider to show the first slide
document.addEventListener('DOMContentLoaded', () => {
    slides[currentSlide].classList.add('active'); // Make the first slide active
});
