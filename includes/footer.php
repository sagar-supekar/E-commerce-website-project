<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <style>

        footer {
            background-color:#262634;
            color: white;
            padding-top: 40px;
            padding-bottom: 20px;
            bottom: 0;
        }

        footer h5 {
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
        }

        footer .text-muted {
            color: #ccc !important;
        }

        footer ul li a {
            color: #cccccc;
            font-size: 14px;
            text-decoration: none;
        }

        footer ul li a:hover {
            color: #f1f1f1;
            text-decoration: underline;
        }

        footer .social-media a {
            display: inline-block;
            margin-right: 15px;
        }

        footer .social-media a:hover {
            color: #007bff;
        }

        footer .footer-bottom p {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            footer .footer-container {
                text-align: center;
            }

            footer .footer-container .col-md-4 {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Footer Section -->
    <footer class=" text-white py-4">
        <div class="container">
            <div class="row">
      
                <div class="col-md-4">
                    <p class="small text-muted">EzyBuy - Empowering Innovation</p>
                </div>

                <div class="col-md-4">
                    <h5 class="text-uppercase mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="#about" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="#services" class="text-white text-decoration-none">Services</a></li>
                        <li><a href="#contact" class="text-white text-decoration-none">Contact</a></li>
                        <li><a href="#privacy" class="text-white text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Social Media Section -->
                <div class="col-md-4">
                    <h5 class="text-uppercase mb-3">Connect with Us</h5>
                    <ul class="list-unstyled social-media">
                        <li><a href="https://facebook.com" target="_blank" class="text-white text-decoration-none">Facebook</a></li>
                        <li><a href="https://twitter.com" target="_blank" class="text-white text-decoration-none">Twitter</a></li>
                        <li><a href="https://linkedin.com" target="_blank" class="text-white text-decoration-none">Linkedin</a></li>
                        <li><a href="https://instagram.com" target="_blank" class="text-white text-decoration-none">Instagram</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom Section -->
            <div class="row mt-4 footer-bottom">
                <div class="col-12 text-center">
        
                    <ul class="list-unstyled social-media d-flex justify-content-center">
                        <li><a href="https://facebook.com" target="_blank" class="text-white text-decoration-none mx-3"><i class="fab fa-facebook"></i></a></li>
                        <li><a href="https://twitter.com" target="_blank" class="text-white text-decoration-none mx-3"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="https://linkedin.com" target="_blank" class="text-white text-decoration-none mx-3"><i class="fab fa-linkedin"></i></a></li>
                        <li><a href="https://instagram.com" target="_blank" class="text-white text-decoration-none mx-3"><i class="fab fa-instagram"></i></a></li>
                    </ul>
                    <p class="small text-muted">&copy; 2024 EzyBuy. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
