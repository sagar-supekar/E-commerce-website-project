<?php
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Electronics</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      width: 18rem; /* Set a consistent width for all cards */
      height: 25rem; /* Set a consistent height for all cards */
      margin: 0 auto; /* Center align each card horizontally */
    }

    .card-img-top {
      height: 265px; /* Set a consistent height for all images */
      width: 100%; /* Make the image take the full width of the card */
      object-fit: cover;/* Ensure images cover the area proportionally without distortion */
    }

    .con {
      display: flex;
      justify-content: center;
      padding-bottom: 100px;
    }

    .rw {
      justify-content: center; /* Align cards in the center of the row */
    }
  </style>
</head>
<body>
<?php
 echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
 echo "
 <div class='d-flex justify-content-start ms-5 my-2' style='margin-top=10px;'>
     <a href='/E-commerce website/templates/welcome.php' class='text-decoration-none'>
         <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
     </a>
 </div>";
 
 ?>
  <h2 class="text-center">Electronics</h2>
  <div class="container con my-5 mb-5">
    <div class="row rw">
      <!-- Card 1 -->
      <div class="col-md-4 d-flex justify-content-center">
        <div class="card">
          <img class="card-img-top p-3" src="/E-commerce website/admin/uploads/apple.jpeg" alt="Laptop">
          <div class="card-body">
            <h5 class="text-center">Price: ₹ 80,000</h5>
            <a href="/E-commerce website/templates/category/laptop.php" class="btn btn-primary w-100">Laptop</a>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4 d-flex justify-content-center">
        <div class="card">
          <img class="card-img-top p-4" src="/E-commerce website/admin/uploads/smartwatch.jpeg" alt="Smart Watches">
          <div class="card-body">
            <h5 class="text-center">Price: ₹ 4,000</h5>
            <a href="/E-commerce website/templates/category/smartwatches.php" class="btn btn-primary w-100">Smart Watches</a>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-4 d-flex justify-content-center">
        <div class="card">
          <img class="card-img-top p-4" src="/E-commerce website/admin/uploads/earbud2.jpeg" alt="Earbuds">
          <div class="card-body">
            <h5 class="text-center">Price: ₹ 2,000</h5>
            <a href="/E-commerce website/templates/category/earbud.php" class="btn btn-primary w-100">Earbuds</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>
