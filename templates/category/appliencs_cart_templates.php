<?php
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-img-top {
      height: 250px; /* Set a fixed height for the images */
      object-fit: cover; /* Ensures the images cover the area without distortion */
    }
    .card {
      margin: 0 auto; /* Center align each card horizontally */
    }
    .con {
      display: flex;
      justify-content: center;
      padding-bottom:100px;
    }
    .rw {
      justify-content: center; /* Align cards in the center of the row */
    }
    .fc{
        padding-right:10px;
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
  <h2 class="text-center">Appliences</h2>
  <div class="container con my-5 mb-5">
    <div class="row rw">
      <!-- Card 1 -->
      <div class="col-md-4 d-flex justify-content-center fc">
        <div class="card" style="width: 18rem;">
          <img class="card-img-top p-2" src="/E-commerce website/admin/uploads/wm2.jpeg" alt="Washing Machine">
          <div class="card-body">
            <h5 class="text-center">Price: ₹ 30,000</h5>
            <a href="/E-commerce website/templates/category/washing_machine.php" class="btn btn-primary w-100">Washing Machine</a>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4 d-flex justify-content-center">
        <div class="card" style="width: 28rem;">
          <img class="card-img-top p-2" src="/E-commerce website/admin/uploads/rf3.jpeg" alt="Refrigerator">
          <div class="card-body">
            <h5 class="text-center">Price: ₹ 45,000</h5>
            <a href="/E-commerce website/templates/category/refregirator.php" class="btn btn-primary w-100">Refrigerator</a>
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
