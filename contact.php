<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Your reCAPTCHA secret key
    $recaptchaSecret = '6Lf88SoqAAAAAF6E_F_RddQaarS1OCbR2EqDtAzP';
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        echo '<script>alert("Please complete the CAPTCHA."); window.history.back();</script>';
        exit;
    }

    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"]));

    if (empty($name) || empty($email) || empty($message)) {
        echo '<script>alert("All fields are required.")</script>';
        exit;
    }

    require 'PHPMailer/PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer/PHPMailer.php';
    require 'PHPMailer/PHPMailer/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'himlandveda@gmail.com';
        $mail->Password   = 'azvheoaqcgrzrfha';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('himlandveda@gmail.com', 'contact');
        $mail->addAddress('himlandveda@gmail.com', 'himland');

        $mail->isHTML(true);
        $mail->Subject = "user will connect with u through website ";
        $mail->Body    = "Sender Name - $name <br>  Sender Email - $email  <br> message - $message";

        $mail->send();
        $successMessage = "Thank you! Your message has been successfully sent.";
    } catch (Exception $e) {
        $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
     


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Himland Veda</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=PT+Serif:wght@400;700&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
       <script src="https://www.google.com/recaptcha/api.js" async defer></script>


       
        
    </head>
    
<style>
.contact-form {
    max-width: 100%;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.contact-form .form-label {
    font-weight: bold;
    color: #333;
}

.contact-form .form-control {
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px;
    font-size: 16px;
}

.contact-form .form-control:focus {
    border-color: rgb(17, 17, 67);
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.contact-form .btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

.contact-form .g-recaptcha {
    margin-bottom: 15px;
}

.quick-contact-item .text {
  
    margin-left: 10px;
    color: #333;
    font-size: 22px;
}

.quick-contact-item i {
    font-size: 35px;
    color:rgb(17, 17, 67) ;
    margin-right: 10px;
}


</style>
    


    <body>

      
<!-- Navbar start -->
<div class="container-fluid sticky-top px-0">
        <div class="container-fluid topbar d-none d-lg-block">
            <div class="container px-0">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex flex-wrap">
                            <a href="delivery.php" class="me-4 text-light"><i class="fas fa-solid fa-truck"></i> Check Delivery</a>
                            <a href="tel:+919463008876" class="me-4 text-light"><i class="fas fa-phone-alt  me-2"></i>+91 9463008876</a>
                            <a href="mailto:info@himlandveda.com" class="text-light"><i class="fas fa-envelope me-2"></i>info@himlandveda.com</a>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex align-items-center justify-content-end">
                            <a href="#" class="me-3 btn-square border rounded-circle nav-fill"><i
                                    class="fab fa-facebook-f"></i></a>
                           
                            <a href="#" class="me-3 btn-square border rounded-circle nav-fill"><i
                                    class="fab fa-instagram"></i></a>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid bg-light">
            <div class="container px-0">
                <nav class="navbar navbar-light navbar-expand-xl">
                    <a href="index.php" class="navbar-brand">
                        <h1 class="text-primary display-4"><img src="img/logo.png" width="160"></h1>
                    </a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars "></span>
                    </button>
             
       
                    <div class="collapse navbar-collapse bg-light py-3" id="navbarCollapse">
                        <div class="navbar-nav mx-auto border-top">
                            <a href="index.php" class="nav-item nav-link active">Home</a>
                            <a href="about.php" class="nav-item nav-link">About</a>
                           <a href="terms.php" class="nav-item nav-link">Terms & Condition</a>
                            <a href="refund.php" class="nav-item nav-link">Refund</a>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap pt-xl-0">
                            <a href="https://wa.me/+919463008876" class=" btn btn-primary-outline-0 rounded-circle btn-lg-square"><i class="bi bi-whatsapp"></i></a>
                               
                            <a href="contact.php" class="btn  rounded-pill py-3 px-4 ms-4" style="color:white;" >Contact</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

   
  <div class="container">
    <div class="row ">
        <!-- Left side: Contact Information -->
        <div class="col-lg-6 mb-5 mb-lg-0 mt-5">
            <div class="quick-contact-item d-flex align-items-center mb-4">
               <i class="bi bi-house-door-fill"></i>
                <address class="text ms-2">
                     Pathran, Dist Patiala, Pin Code: 147105
                </address>
            </div>
            <div class="quick-contact-item d-flex align-items-center mb-4">
                <i class="fas fa-phone-alt me-2"></i>
                <address class="text ms-2">
                   +91 946300887
                </address>
            </div>
            <div class="quick-contact-item d-flex align-items-center mb-4">
                <i class="fas fa-envelope me-2"></i>
                <address class="text ms-2">
                 info@himlandveda.com
                </address>
            </div>
        </div>

        <!-- Right side: Contact Form -->
        <div class="col-lg-6 ml-auto">
            <form id="contactForm" class="contact-form mt-5 mb-5" method="post" action="" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message:</label>
                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="6Lf88SoqAAAAAAuRu01zbdGSNBbtisyf25__NpWi"></div>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

 <!-- Footer Start -->
 
 <div class="container-fluid footer py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <div class="footer-item">
                        <h4 class="mb-4 text-white">Himland Veda</h4>
                        <h6 class="text-white">At Himland Veda, we harness the power of nature and the wisdom of Ayurveda to provide effective, natural solutions for your well-being. Our flagship product, Himland Veda Capsule and Cream Combo for Piles, is crafted by experts to manage symptoms and prevent recurrence. We blend traditional Ayurvedic knowledge with modern research, ensuring our products are safe, effective, and aligned with your health goals. Trust Himland Veda for a balanced, fulfilling life.</h6>
                        <a href="privacy-policy.php" class="nav-item nav-link"><i class="fas fa-angle-right me-2"></i>Privacy Policy</a>
                       
                        <a href="refundandcancel.php" class="nav-item nav-link"><i class="fas fa-angle-right me-2"></i>Refund & Cancellation</a>
                        <a href="shippinganddelivery.php" class="nav-item nav-link"><i class="fas fa-angle-right me-2"></i>Shipping & Delivery</a>
                         <a href="Disclaimer.php" class="nav-item nav-link"><i class="fas fa-angle-right me-2"></i>Disclaimer</a>
                    </div>
                </div>
               
                <div class="col-md-6 col-lg-6 col-xl-5">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-white">About Us</h4>
                        <h6 class="text-white mb-0">Himland Veda is dedicated to bringing you the best of Ayurvedic wellness. Our approach combines the ancient wisdom of Ayurveda with modern science to create natural, effective products that support your health. Our signature offering, Himland Veda Capsule and Cream Combo for Piles, is designed by experts to help alleviate symptoms and reduce the risk of recurrence. At Himland Veda, we prioritize your well-being, offering trusted solutions for a healthier, more balanced life.</h6>
                       
                        <h4 class="my-4 text-white">Address</h4>
                        <p class="mb-0"><i class="fas fa-map-marker-alt fotter-icon me-2"></i> Pathran, Dist Patiala, Pin Code: 147105</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-white">Follow Us</h4>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Faceboock</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Instagram</a>
                       
                        <h4 class="my-4 text-white">Contact Us</h4>
                        <p class="mb-0"><i class="fas fa-envelope fotter-icon  me-2"></i> info@himlandveda.com</p>
                        <p class="mb-0"><i class="fas fa-phone fotter-icon  me-2"></i> +91 9463008876</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright py-4">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-4 text-center text-md-start mb-md-0">
                    <span class="text-light "><a href="#" class="star"><i class="fas fa-copyright me-2"></i>Himlandveda</a>, All right reserved.</span>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-center">
                        <a href="" class="btn btn-light btn-light-outline-0 btn-sm-square rounded-circle me-2"><i
                                class="fab fa-facebook-f"></i></a>
                       
                        <a href="" class="btn btn-light btn-light-outline-0 btn-sm-square rounded-circle me-2"><i
                                class="fab fa-instagram"></i></a>
                       
                    </div>
                </div>
                <div class="col-md-4 text-center text-md-end text-white">
                    <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                    <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                    <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                    Designed By <a class="border-bottom star" href="https://www.buildupnet.com/">Buildupnet</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->






  <!-- JavaScript Libraries -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

 
    <script src="js/main.js"></script>
    <script>
        $(document).ready(function(){
            $('.thumbnails img').on('click', function() {
                var index = $(this).data('slide-to');
                $('#carouselExample').carousel(index);
            });
        });
    </script>
     <script>
        document.querySelectorAll('.faq-question').forEach(function(question) {
            question.addEventListener('click', function() {
                var answer = this.nextElementSibling;
                var displayStyle = window.getComputedStyle(answer).display;
                if (displayStyle === 'none') {
                    answer.style.display = 'block';
                } else {
                    answer.style.display = 'none';
                }
            });
        });

        document.getElementById('toggle-btn').addEventListener('click', function() {
            var additionalFaqs = document.querySelector('.additional-faqs');
            var isShown = window.getComputedStyle(additionalFaqs).display === 'block';
            if (isShown) {
                additionalFaqs.style.display = 'none';
                this.textContent = 'Show More';
            } else {
                additionalFaqs.style.display = 'block';
                this.textContent = 'Show Less';
            }
        });
    </script>
    <script>
    function validateForm() {
        var response = grecaptcha.getResponse();
        if(response.length == 0) { 
            alert("Please complete the CAPTCHA.");
            return false;
        }
        return true;
    }

    <?php if (!empty($successMessage)) : ?>
        alert("<?php echo $successMessage; ?>");
    <?php endif; ?>

    <?php if (!empty($errorMessage)) : ?>
        alert("<?php echo $errorMessage; ?>");
    <?php endif; ?>
    </script>
    
    
   

    </body>

</html>