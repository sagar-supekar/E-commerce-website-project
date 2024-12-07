<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
   
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Carousel Container */
    .carousel-container {
      position: relative;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto; 
      overflow: hidden;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    }

    /* Carousel */
    .carousel {
      display: flex;
      transition: transform 0.5s ease-in-out;
    }

    /* Carousel Slide */
    .carousel-slide {
      min-width: 100%;
      transition: opacity 0.5s;
    }

    .carousel-slide img {
      width: 100%;
      display: block;
      border-radius: 8px;
    }

    /* Controls */
    .carousel-controls {
      position: absolute;
      top: 50%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      transform: translateY(-50%);
    }

    .carousel-controls button {
      background: rgba(0, 0, 0, 0.5);
      border: none;
      color: #fff;
      font-size: 2rem;
      cursor: pointer;
      padding: 10px;
      border-radius: 50%;
      outline: none;
      transition: background 0.3s;
    }

    .carousel-controls button:hover {
      background: rgba(255, 255, 255, 0.7);
      color: #000;
    }

    /* Optional: Make sure it doesn't overlap other page content */
    .carousel-wrapper {
      margin-top: 20px;
    }

  </style>
</head>
<body>
  <div class="carousel-wrapper">
    <div class="carousel-container">
      <div class="carousel">
        <div class="carousel-slide">
          <img src="/E-commerce website/admin/uploads/flipcart_image.webp" alt="Slide 1">
        </div>
        <div class="carousel-slide">
          <img src="/E-commerce website/admin/uploads/fp2.webp" alt="Slide 2">
        </div>
        <div class="carousel-slide">
          <img src="/E-commerce website/admin/uploads/fp3.webp" alt="Slide 3">
        </div>
        <div class="carousel-slide">
          <img src="/E-commerce website/admin/uploads/fp4.webp" alt="Slide 3">
        </div>
        <div class="carousel-slide">
          <img src="/E-commerce website/admin/uploads/fp5.webp" alt="Slide 3">
        </div>
      </div>
      <div class="carousel-controls">
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>
      </div>
    </div>
  </div>

  <script>
    const carousel = document.querySelector('.carousel');
    const slides = document.querySelectorAll('.carousel-slide');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    let currentIndex = 0;

    // Show Slide
    function showSlide(index) {
      carousel.style.transform = `translateX(${-index * 100}%)`;
    }

    // Next Slide
    nextButton.addEventListener('click', () => {
      currentIndex = (currentIndex + 1) % slides.length;
      showSlide(currentIndex);
    });

    // Previous Slide
    prevButton.addEventListener('click', () => {
      currentIndex = (currentIndex - 1 + slides.length) % slides.length;
      showSlide(currentIndex);
    });

    // Auto Play
    setInterval(() => {
      nextButton.click();
    }, 5000); // Change every 5 seconds
  </script>
</body>
</html>
