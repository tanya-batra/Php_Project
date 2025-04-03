<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $pincode = $conn->real_escape_string($_POST['pincode']);
    $state = $conn->real_escape_string($_POST['state']);
    $district = $conn->real_escape_string($_POST['district']);
    $address = $conn->real_escape_string($_POST['address']);

   
    if (isset($_FILES['image'])) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageExt, $allowedExt) && $_FILES['image']['error'] === 0) {
            $imageNewName = uniqid('', true) . "." . $imageExt;
            $imageDestination = "uploads/" . $imageNewName;

            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                
                $sql = "INSERT INTO payment (firstname, lastname, phone, email, pincode, state, district, address, image) 
                        VALUES ('$firstname', '$lastname', '$phone', '$email', '$pincode', '$state', '$district', '$address', '$imageNewName')";

                if ($conn->query($sql) === TRUE) {
                    sendMail($email, $firstname, $lastname, $phone, $address, $imageNewName);
                    echo "<script>alert('order placed successful and detail mail');</script>";
                } else {
                   echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
               echo "<script>('There was an error uploading the image.');</script>";
            }
        } else {
            echo "Invalid image type or error uploading.";
        }
    }
}


function sendMail($clientEmail, $firstname, $lastname, $phone, $address, $image)
{
    require 'PHPMailer/PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer/PHPMailer.php';
    require 'PHPMailer/PHPMailer/SMTP.php';
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tanyabatra949@gmail.com'; 
        $mail->Password   = 'cxbp qekp zgrr eqli';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender & Recipient
        $mail->setFrom('tanyabatra949@gmail.com', 'tanya');
        $mail->addAddress($clientEmail, $firstname);
       

        // Attach uploaded image
        $mail->addAttachment("uploads/" . $image);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "Payment Confirmation";
        $mail->Body    = "<p>Hello $firstname $lastname,</p>
                          <p>Thank you for your payment.Your order has been placed. Below are your details:</p>
                          <p><strong>Phone:</strong> $phone</p>
                          <p><strong>Address:</strong> $address</p>
                          <p>Best Regards,<br>Himland veda </p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
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
@media (max-width: 768px) {
    .row {
        flex-direction: column;
        align-items: center;
    }

    .col-md-5 {
        order: 1;
        margin-bottom: 0; 
    }

    .col-md-1 {
        order: 2;
        margin-top: 0;
        margin-bottom: 0; 
    }

    .col-md-6 {
        order: 3;
    }

    .carousel-inner img, .carousel-inner video {
        width: 100%;
        height: auto;
    }

    .thumbnails img {
        width: 60px;
        margin: 0 5px;
    }

   
    .container {
        padding: 0;
        margin: 0;
    }

   
    .row {
        margin-left: 0;
        margin-right: 0;
    }
    
    .carousel-item {
	position: relative;
	min-height: 50vh;
}
}


.appointment-modal-container {
    display: flex;
    justify-content: center; 
    align-items: flex-start; 
    padding: 30px;
    flex-direction: row;
}

.appointment-form-section {
    flex: 1; 
    padding: 30px;
    max-width: 600px; 
    width: 100%;
    margin-right: 20px; 
}

.appointment-image-section {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    min-width: 200px; 
}

.appointment-image-section img {
    max-width: 100%; 
    max-height: 400px;
    width: auto;
}

.form-fields-container {
    display: flex;
    flex-direction: column;
    gap: 20px; 
}

.form-field {
    width: 100%; 
}

.form-control {
    padding: 15px; 
    border-radius: 8px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    color: #333;
    font-size: 16px;
}

.form-select {
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
}

.btn {
    padding: 15px;
    font-size: 18px;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #28a745;
    color: black;
}


.image-preview-container {
    display: none;
    text-align: center;
}

.image-preview {
    max-width: 100%;
    max-height: 300px;
}


@media (max-width: 1200px) {
    .appointment-modal-container {
        flex-direction: row; 
    }

    .appointment-form-section {
        max-width: 600px; 
    }
}

@media (max-width: 768px) {
    .appointment-modal-container {
        flex-direction: column; 
        text-align: center;
    }

    .appointment-form-section {
        max-width: 100%;
        padding: 15px;
    }

    .appointment-image-section {
        margin-top: 20px;
    }

    .btn {
        font-size: 16px;
    }

    .appointment-image-section img {
        max-height: 250px; 
    }
}

@media (max-width: 480px) {
    .btn {
        font-size: 14px; 
        padding: 12px;
    }

    .appointment-image-section img {
        max-height: 200px; 
    }
}

@media (min-width: 576px) {
    .modal-dialog {
        max-width: 900px;
        margin: 1.75rem auto;
    }
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
                            <a href="delivery.php" class="me-4 text-light"><i class="fas fa-solid fa-truck"></i> Check  Delivery</a>
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
                         

                            <a href="contact.php" class="btn  rounded-pill py-3 px-4 ms-4" style="color:white;">Contact</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

 
 <div class="container mt-4">
        <div class="row">    
            <div class="col-md-1">
                <div class="thumbnails">
                    <img src="img/1.jpeg" class="img-thumbnail mb-2" alt="Thumbnail 1" data-target="#carouselExample" data-slide-to="0">
                    <img src="img/2.jpeg" class="img-thumbnail mb-2" alt="Thumbnail 2" data-target="#carouselExample" data-slide-to="1">
                    <img src="img/3.jpeg" class="img-thumbnail mb-2" alt="Thumbnail 3" data-target="#carouselExample" data-slide-to="2">
                    <img src="img/4.jpeg" class="img-thumbnail mb-2" alt="Thumbnail 4" data-target="#carouselExample" data-slide-to="3">
                         <img src="img/5.jpg" class="img-thumbnail mb-2" alt="Thumbnail 5" data-target="#carouselExample" data-slide-to="4"> 
                </div>
            </div>
            
            <div class="col-md-5">
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="img/1.jpeg" class="d-block w-150" alt="..." height="420px ">
                        </div>
                        <div class="carousel-item">
                            <img src="img/2.jpeg" class="d-block w-150" alt="..." height="420px">
                        </div>
                        <div class="carousel-item">
                            <img src="img/3.jpeg" class="d-block w-150" alt="..." height="420px">
                        </div>
                        <div class="carousel-item">
                            <img src="img/4.jpeg" class="d-block w-150" alt="..." height="420px ">
                        </div>
                         <div class="carousel-item">
                             <video class="d-block w-100" height="420px" controls>
                            <source src="img/1.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-md-6 ">
                <h2>Piles Bound Combo : For Managing Piles Naturally</h2>
                <p class="rating">⭐⭐⭐⭐☆ <span>887 reviews</span></p>
                <p class="price">Rs. 897.00 <span class="original-price">Rs. 1340.00</span> <span class="discount">SAVE 33%</span></p>
                <p>Trusted by 1 Million+ Happy Customers</p>
                <p>Himland Veda Capsule and Cream Combo for Piles is here to help with moderate piles symptoms. It stops bleeding in 3-4 days, reduces pain and swelling in less than 2 weeks, and relieves constipation in less than 5 days. It also shrinks pile mass within 15-20 days.</p>
                <div class="sizes">
                    <!--<h5>Select a Size: Pack of 3</h5>-->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card pack-card">
                                <div class="card-body">
                                    <!--<h6 class="card-title">PACK OF 3</h6>-->
                                    <p class="card-text save-text">Save Rs.496/-</p>
                                    <p class="price-text">Rs.897 <span class="original-price">Rs.1340</span></p>
                                    <p class="caps-text">30 CAPS + Cream</p>
                                    <p class="recommend-text">Expert Recommended</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card pack-card">
                                <div class="card-body">
                                    <img src="img/gmp-quality-logo.png" style="height=180px; width:180px">
                                    <h5 style=" color:#28a745; font-size:11px;" class="fw-bold">Our Formula is approved by State Licensing Authority Ayush, Shimla(H.P.)</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="quantity mt-3">
                    <h5>Quantity:</h5>
                    <input type="number" class="form-control" value="1" min="1" >
                </div>
                <div class="mt-3">
                                           <button class="btn rounded-pill py-3 px-5 mb-3 me-3" href="#" data-bs-toggle="modal" data-bs-target="#appointmentModal" style="width:100%">Buy Now</button>
                                          
                                             
                </div>
                <div class="prepaid-orders">
                    <div class="benefit-icon">
                        <img src="img/free_doctor_consulation.jpg" alt="Free Expert Consultation">
                        <p>Free Expert Consultation</p>
                    </div>
                    <div class="benefit-icon">
                        <img src="img/dispatch_times.jpg" alt="Free Shipping on Prepaid">
                        <p> Free Shipping on Prepaid</p>
                    </div>
                    
                    <div class="benefit-icon">
                        <img src="img/100_-Ayurvedic.png" alt="100% Ayurvedic">
                        <p> 100% Ayurvedic</p>
                    </div>
                    
                    <!--<div class="benefit-icon">-->
                    <!--    <img src="img/cash_on_delivery.jpg" alt="Cash on Delivery">-->
                    <!--    <p>Cash on Delivery</p>-->
                    <!--</div>-->
                   
                </div>
        
                <div class="guaranteed-checkout">
                   <i class="bi bi-lock-fill"></i>
                    GUARANTEED SAFE CHECKOUT
                </div>
    
                </div>
            </div>
                
            </div>
        </div>
     
    
        <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="text-uppercase">Get In Touch</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="appointment-modal-container">
                    <!-- Form Section -->
                    <div class="appointment-form-section">
                        <form id="contactForm" class="php-email-form"  method="POST" enctype="multipart/form-data" >
                            <div class="form-fields-container">
                                <!-- Form fields -->
                                <div class="form-field">
                                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="First Name" required>
                                </div>
                                <div class="form-field">
                                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Last Name" required>
                                </div>
                                <div class="form-field">
                                    <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{5}-[0-9]{5}" placeholder="01234-56789"  required>
                                </div>
                                <div class="form-field">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                </div>
                                <div class="form-field">
                                    <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Pincode" required>
                                </div>
                                <div class="form-field">
                                    <select class="form-select" id="state" name="state" required onchange="updateDistricts()">
                                        <option value="" selected disabled>State</option>
                                        <option value="AndhraPradesh">Andhra Pradesh</option>
                                        <option value="Maharashtra">Maharashtra</option>
                                        <option value="UttarPradesh">Uttar Pradesh</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <select class="form-select" id="district" name="district" required onchange="updateCities()">
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <textarea class="form-control" name="address" id="address" cols="30" rows="5" placeholder="Address" required></textarea>
                                </div>

                                <!-- Image Upload Section -->
                                <div class="form-field">
                                    <input type="file" class="form-control" name="image" id="image" accept="image/*" required onchange="previewImage(event)">
                                    <div id="imagePreviewContainer" class="image-preview-container">
                                        <h4>Preview:</h4>
                                        <img id="imagePreview" class="image-preview" />
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-field">
                                    <button type="submit" class="btn submit-btn">Pay NOW</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Image Section (Parallel to the form) -->
                    <div class="appointment-image-section">
                        <img src="QR.jpeg" alt="Image Description" class="img-fluid" id="modalImage">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



 <!-- Navigation Bar using divs -->
 <div class="navbar">
    <a href="#keybenfits" class="nav-item nav-link active">Key Benefits</a>
    <a href="#Ingredients" class="nav-item nav-link ">Ingredients</a>
    <a href="#HowtoUse"class="nav-item nav-link ">How to Use</a>
    <a href="#ProductDetails"class="nav-item nav-link ">Product Details</a>
    <a href="#FAQs"class="nav-item nav-link ">FAQs</a>
  </div>

  <div class="container mt-2" id="keybenfits" >
    <h2>Key Benefits - Piles Bound Combo</h2>
    <div class="row">
        <div class="col-md-3 col-6
text-center">
            <img src="img/care1.jpg" alt="Controls bleeding within 7-10 days" class="img-fluid">
            <p>Controls bleeding within 3-4 days*</p>
        </div>
        <div class="col-md-3 col-6 text-center">
            <img src="img/care2.jpg" alt="Relieves pain & swelling within 10-15 days" class="img-fluid">
            <p>Relieves pain & swelling within 10-15 days*</p>
        </div>
        <div class="col-md-3 col-6 text-center">
            <img src="img/care3.jpg" alt="Eases constipation within 2-5 days" class="img-fluid">
            <p>Eases constipation within 2-5 days*</p>
        </div>
        <div class="col-md-3 col-6 text-center">
            <img src="img/care4.jpg" alt="Helps in shrinking pile mass within 15-20 days" class="img-fluid">
            <p>Helps in shrinking pile mass within 15-20 days*</p>
        </div>
    </div>
    <p class="text-center">*Applicable for moderate piles symptoms - grade 1 & grade 2 piles. Prescribed dosage, fibre-rich diet, 4 L of water a day, no prolonged sitting of over 1 hour is recommended for best results.</p>
</div>

<div class="container mt-2" id="Ingredients">   

    <h2 >Key Ingredients - Piles Bound Combo</h2>
    <div class="row">
        <div class="col-md-3 col-6 text-center">
            <img src="img/neem.jpg" alt="Mahaneemb" class="img-fluid">
            <h3>Mahaneemb</h3>
            <p>Fights against infection and reduces swelling</p>
        </div>
        <div class="col-md-3 col-6 text-center">
            <img src="img/guggul.jpg" alt="Triphala Guggul" class="img-fluid">
            <h3>Triphala Guggul</h3>
            <p>Helps relieve pain, swelling & burning</p>
        </div>
        <div class="col-md-3 col-6 text-center">
            <img src="img/daruharidra.jpg" alt="Daruharidra" class="img-fluid">
            <h3>Daruharidra</h3>
            <p>Reduces inflammation to prevent infections</p>
        </div>
        <div class="col-md-3 col-6 text-center">
            <img src="img/lajju.jpg" alt="Lajjalu" class="img-fluid">
            <h3>Lajjalu</h3>
            <p>Controls bleeding, aiding in piles relief</p>
        </div>
    </div>
    <p>Other ingredients: Haritaki, Nagkesar, Khadir, Kumari, Kashishadi Taila</p>
</div>
<div class="container mb-3" id="HowtoUse">   

    <h2>How to Use - Piles Bound Combo</h2>
    <p>Pour a glass of lukewarm water after meals.</p>
    <div class="row">
        <div class="col-md-6 col-sm-12  col-12 pt-2  mb-3 " style="background-color:rgb(246, 246, 249);">
            <h3> 1</h3>
            <img src="img/cap1.jpg" alt="Lajjalu" class="img-fluid" style="border-radius: 5px; margin-left: 20px;">
            <p class="me-2">For burning and icheses take 1 capsule twice daily</p>
        </div>
        <div class="col-md-6 col-sm-12   col-12  pt-2 mb-3" style="background-color:rgb(246, 246, 249);">
            <h3> 2</h3>
            <img src="img/cap2.jpg" alt="Lajjalu" class="img-fluid"  style="border-radius: 5px; margin-left: 20px;">
            <p  class="me-2">For pain and bleeding take 2 capsule twice daily</p>
        </div>
    </div>
</div>

<div class="container mt-2" >   

    <h2 >Key Ingredients - Piles Bound Combo</h2>
    <div class="row">
        <div class="col-md-3 col-6 text-center">
            <img src="img/neem.jpg" alt="Mahaneemb" class="img-fluid">
            <h3>Mahaneemb</h3>
            <p>Fights against infection and reduces swelling</p>
        </div>
        <div class="col-md-3 col-6 text-center">
            <img src="img/guggul.jpg" alt="Triphala Guggul" class="img-fluid">
            <h3>Triphala Guggul</h3>
            <p>Helps relieve pain, swelling & burning</p>
        </div>
        <div class="col-md-3 col-6 text-center">
            <img src="img/daruharidra.jpg" alt="Daruharidra" class="img-fluid">
            <h3>Daruharidra</h3>
            <p>Reduces inflammation to prevent infections</p>
        </div>
        <div class="col-md-3 col-6 text-center">
            <img src="img/lajju.jpg" alt="Lajjalu" class="img-fluid">
            <h3>Lajjalu</h3>
            <p>Controls bleeding, aiding in piles relief</p>
        </div>
    </div>
    <p>Other ingredients: Haritaki, Nagkesar, Khadir, Kumari, Kashishadi Taila</p>
</div>
<div class="container mb-3" id="ProductDetails">   

    <h2>Product Details</h2>
    <h4>Himland Veda Capsule and Cream for Piles Relief specially curated by doctors to manage moderate symptoms..</h4>
    <div class="row">
        <div class="col-md-2 col-4 text-center">
            <img src="img/Curated_by_Ayurvedic_Experts.jpg" alt="Lajjalu" class="img-fluid">
        </div>
        <div class="col-md-2 col-4 text-center">
            <img src="img/Safe_for_Long-term_Use.jpg" alt="Lajjalu" class="img-fluid">
        </div>
        <div class="col-md-2 col-4 text-center">
            <img src="img/No_Heavy_Metals.jpg" alt="Lajjalu" class="img-fluid">
        </div>
        <div class="col-md-2 col-4 text-center">
            <img src="img/Ethically_Sourced_Ingredients.jpg" alt="Lajjalu" class="img-fluid">
        </div>
        <!--<div class="col-md-2 col-4 text-center">-->
        <!--    <img src="img/ISO_90012015.jpg" alt="Lajjalu" class="img-fluid">-->
        <!--</div>-->
        <div class="col-md-2 col-4 text-center">
            <img src="img/gmp-quality-logo.png" alt="Lajjalu" class="img-fluid">
         <h5 style=" color:#28a745; font-size:11px;" class="fw-bold">Our Formula is approved by State Licensing Authority Ayush, Shimla(H.P.)</h5>
        </div>
    </div>
    <p class="mt-4">Himland Veda Capsule and Cream Combo for Piles is carefully curated by doctors to assist in managing moderate symptoms and minimize the chance of reoccurrence. It includes Ayurvedic capsules and Herbo Pilocare Cream.</p>
</div>

<div class="container faq-section" id="FAQs">
    <h2 class="faq-title">FAQs</h2>
    <ul>
    <li class="faq-item">
        <p class="faq-question">If I have bleeding piles, can the Piles Care Combo help?</p>
        <div class="faq-answer">
            <p>Yes, Himland Veda Capsule and Cream Combo for Piles is effective for both bleeding and non-bleeding piles. It contains Ayurvedic herbs like Nagkesar and Mochras, which aid in controlling bleeding.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">How long should I continue with Himland Veda Capsule and Cream for Piles Relief?</p>
        <div class="faq-answer">
            <p>It's advisable to use the Capsule and Cream Combo for Piles Relief for at least three months for better results.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">What dietary recommendations should I follow while using the Piles Care Combo?</p>
        <div class="faq-answer">
            <p>It is recommended to maintain a balanced diet rich in fiber, including leafy vegetables, sprouts, and yogurt, and to adhere to regular meal times while ensuring adequate hydration with 3-4 liters of water per day.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Are there any adverse effects associated with using the Piles Care Combo?</p>
        <div class="faq-answer">
            <p>No, Himland Veda Capsule and Cream Combo for Piles is formulated with selective Ayurvedic ingredients known not to cause adverse effects when used in the recommended dosages.</p>
        </div>
    </li>
    <hr>
    <div class="additional-faqs">
    <li class="faq-item">
        <p class="faq-question">Is Herbo Pilocare Cream safe for use during pregnancy?</p>
        <div class="faq-answer">
            <p>No, Herbo Pilocare Cream is not recommended for pregnant women.</p>
        </div>
    </li>
   
    <hr>
    <li class="faq-item">
        <p class="faq-question">Can I use the Capsule and Cream Combo for Piles Relief for an extended period?</p>
        <div class="faq-answer">
            <p>Yes, both products in the combo are made from Ayurvedic ingredients and are safe for long-term use as per the recommended dosages.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Is the Himland Veda Capsule and Cream for Piles Relief effective for fistula?</p>
        <div class="faq-answer">
            <p>Yes, Himland Veda Capsule and Cream for Piles Relief can be employed as supportive therapy for fistula.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Can I consume tea and coffee while undergoing treatment with the Piles Care Combo?</p>
        <div class="faq-answer">
            <p>It's advisable to minimize the intake of tea and coffee during treatment.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Is there a risk of recurrence if I discontinue the Capsule and Cream Combo for Piles Relief after completing the prescribed course?</p>
        <div class="faq-answer">
            <p>If the recommended course is completed and a proper diet and active lifestyle are maintained, the likelihood of recurrence is minimal.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Can Ayurvedic medicine cure piles?</p>
        <div class="faq-answer">
            <p>Himland Veda Capsule and Cream for Piles Relief is an Ayurvedic medicine for piles designed to alleviate symptoms such as bleeding, pain, and constipation.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Which ointment effectively treats piles?</p>
        <div class="faq-answer">
            <p>Himland Veda Herbo Pilocare Cream, an Ayurvedic piles cream made with 12 natural ingredients, offers quick relief from piles or hemorrhoids and may help prevent recurrence.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Will Himland Veda Capsule and Cream Combo for Piles cure my piles?</p>
        <div class="faq-answer">
            <p>The Capsule and Cream Combo for Piles Relief can provide relief for moderate piles symptoms. Severe cases should be discussed with a medical professional.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Does the Piles Care Combo alleviate pain?</p>
        <div class="faq-answer">
            <p>Himland Veda Capsule and Cream for Piles Relief contains Camphor and Khadir, which helps in pain reduction. It also includes Yastimadhu and Tankan, which promote wound healing.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">What is the typical duration of piles?</p>
        <div class="faq-answer">
            <p>The duration varies from person to person. Small hemorrhoids may resolve within a few days, while larger ones causing pain or bleeding may take longer. The Capsule and Cream Combo for Piles Relief, comprising Piles care capsules and Herbo Pilocare Cream, can expedite healing and provide relief.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">What causes piles?</p>
        <div class="faq-answer">
            <p>Piles occur due to increased pressure in the lower rectum, leading to the stretching, bulging, or swelling of veins around the anus. Contributing factors include straining during bowel movements, chronic diarrhea, obesity, heavy lifting, and prolonged sitting on the toilet.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">How can piles be alleviated?</p>
        <div class="faq-answer">
            <p>Shop Himalnd Veda Capsule and Cream Combo for Piles and incorporate Sitz baths twice daily, while avoiding heavy lifting and prolonged sitting, can help alleviate piles and prevent recurrence.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Is bleeding common with hemorrhoids?</p>
        <div class="faq-answer">
            <p>Experiencing bleeding from hemorrhoids can occur if a hemorrhoid becomes filled with too much blood, potentially leading to bursting. Treatment may vary depending on severity, with some cases requiring medical attention.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Can I use Himland Veda Capsule and Cream for Piles Relief long with other medications or supplements?</p>
        <div class="faq-answer">
            <p>It's advisable to consult with a healthcare professional before starting any new medication or supplement to ensure there are no potential interactions.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">How do I consume the Capsule and Cream Combo for Piles Relief?</p>
        <div class="faq-answer">
            <p>For the Piles Care Capsules, typically, take 1 capsule twice a day for burning and itching. For pain and bleeding, take 2 capsules twice a day. It's usually advised to take the capsules with lukewarm water after meals. Apply the Herbo Pilocare Cream before and after defecation using the provided applicator.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Are there any age restrictions for using Himaland Veda Capsule and Cream Combo for Piles?</p>
        <div class="faq-answer">
            <p>HImalnd Veda Capsule and Cream Combo for Piles is safe for adults of all ages.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">How should I store the Capsule and Cream Combo for Piles Relief to maintain its effectiveness?</p>
        <div class="faq-answer">
            <p>Store Himland Veda Capsule and Cream for Piles Relief in a cool, dry place away from direct sunlight and moisture to maintain its potency.</p>
        </div>
    </li>
    <hr>
    <li class="faq-item">
        <p class="faq-question">Can I purchase the Piles Care Combo without a prescription?</p>
        <div class="faq-answer">
            <p>You can buy Himland Veda Capsule and Cream Combo for Piles without a prescription.</p>
        </div>
    </li>
    <hr>
    </div></ul>
    
<button id="toggle-btn" class="btn  mt-3 mb-3" style="width: 100%;">Show More</button>
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

    <div id="qrcode-container" style="display:none; margin-top: 20px;">
        <h3>Scan this QR code to complete payment</h3>
        <img id="qrcode-img" src="QR.jpeg" alt="QR Code" width="256" height="256">
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
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

  <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>document.addEventListener('DOMContentLoaded', function () {
    // Toggle answer visibility when clicking on a question
    const faqItems = document.querySelectorAll('.faq-question');
    faqItems.forEach(function (item) {
        item.addEventListener('click', function () {
            const answer = this.nextElementSibling;
            answer.style.display = answer.style.display === 'none' || !answer.style.display ? 'block' : 'none';
        });
    });

    // Toggle between showing all or only a few FAQ items
    const toggleBtn = document.getElementById('toggle-btn');
    const additionalFaqs = document.querySelector('.additional-faqs');
    let showMore = false;

    toggleBtn.addEventListener('click', function () {
        showMore = !showMore;
        if (showMore) {
            additionalFaqs.style.display = 'block';
            toggleBtn.textContent = 'Show Less';
        } else {
            additionalFaqs.style.display = 'none';
            toggleBtn.textContent = 'Show More';
        }
    });

    // Initially hide additional FAQs
    additionalFaqs.style.display = 'none';
});
</script>

   

    <script> 
function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('imagePreview');
                var previewContainer = document.getElementById('imagePreviewContainer');
                output.src = reader.result;
                previewContainer.style.display = 'block';  // Show the preview container
            };
            reader.readAsDataURL(event.target.files[0]);
        }

    
    </script>
     <script>
  const data = {
      
      "AndhraPradesh": {
    "Anantapur": ["Anantapur", "Hindupur", "Gooty", "Dharmavaram", "Tadipatri"],
    "Chittoor": ["Chittoor", "Tirupati", "Madanapalle", "Punganur", "Puttur"],
    "East Godavari": ["Kakinada", "Rajahmundry", "Amalapuram", "Peddapuram", "Mandapeta"],
    "Guntur": ["Guntur", "Narasaraopet", "Mangalagiri", "Tenali", "Ponnur"],
    "Krishna": ["Vijayawada", "Machilipatnam", "Gudivada", "Nuzvid", "Pedana"],
    "Kurnool": ["Kurnool", "Nandyal", "Adoni", "Dhone", "Yemmiganur"],
    "Nellore": ["Nellore", "Gudur", "Kavali", "Venkatagiri", "Sullurpeta"],
    "Prakasam": ["Ongole", "Chirala", "Markapur", "Kandukur", "Giddalur"],
    "Srikakulam": ["Srikakulam", "Palasa", "Amadalavalasa", "Tekkali", "Ichchapuram"],
    "Visakhapatnam": ["Visakhapatnam", "Anakapalle", "Bheemunipatnam", "Narsipatnam", "Gajuwaka"],
    "Vizianagaram": ["Vizianagaram", "Bobbili", "Parvathipuram", "Salur", "S.Kota"],
    "West Godavari": ["Eluru", "Tadepalligudem", "Bhimavaram", "Tanuku", "Narasapuram"],
    "YSR Kadapa": ["Kadapa", "Proddatur", "Pulivendula", "Rayachoti", "Jammalamadugu"]
},

"ArunachalPradesh": {
    "Anjaw": ["Hawai", "Hayuliang"],
    "Changlang": ["Changlang", "Jairampur", "Miao"],
    "Dibang Valley": ["Anini"],
    "East Kameng": ["Seppa", "Chayangtajo"],
    "East Siang": ["Pasighat", "Ruksin", "Mebo"],
    "Kamle": ["Raga"],
    "Kra Daadi": ["Palin"],
    "Kurung Kumey": ["Koloriang"],
    "Lepa Rada": ["Basar"],
    "Lohit": ["Tezu", "Namsai"],
    "Longding": ["Longding"],
    "Lower Dibang Valley": ["Roing"],
    "Lower Siang": ["Likabali", "Basar"],
    "Lower Subansiri": ["Ziro", "Yachuli"],
    "Namsai": ["Namsai", "Chongkham"],
    "Pakke Kessang": ["Laying Tuting", "Seijosa"],
    "Papum Pare": ["Yupia", "Naharlagun", "Itanagar"],
    "Shi Yomi": ["Tato", "Mechuka"],
    "Siang": ["Boleng", "Pangin"],
    "Tawang": ["Tawang", "Lumla"],
    "Tirap": ["Khonsa", "Deomali"],
    "Upper Siang": ["Yingkiong", "Tuting"],
    "Upper Subansiri": ["Daporijo"],
    "West Kameng": ["Bomdila", "Dirang", "Rupa"],
    "West Siang": ["Aalo", "Along", "Kamba"]
},

"Assam": {
    "Baksa": ["Mushalpur", "Barama", "Tamulpur"],
    "Barpeta": ["Barpeta", "Barpeta Road", "Howly"],
    "Biswanath": ["Biswanath Chariali", "Gohpur"],
    "Bongaigaon": ["Bongaigaon", "Bijni", "Abhayapuri"],
    "Cachar": ["Silchar", "Lakhipur", "Sonai"],
    "Charaideo": ["Sonari", "Sapekhati"],
    "Chirang": ["Kajolgaon", "Bijni"],
    "Darrang": ["Mangaldoi", "Sipajhar"],
    "Dhemaji": ["Dhemaji", "Silapathar", "Jonai"],
    "Dhubri": ["Dhubri", "Gauripur", "Bilasipara"],
    "Dibrugarh": ["Dibrugarh", "Naharkatia", "Moran"],
    "Dima Hasao": ["Haflong", "Maibang", "Umrangso"],
    "Goalpara": ["Goalpara", "Lakhipur", "Dudhnoi"],
    "Golaghat": ["Golaghat", "Bokakhat", "Sarupathar"],
    "Hailakandi": ["Hailakandi", "Lala"],
    "Hojai": ["Hojai", "Doboka", "Lanka"],
    "Jorhat": ["Jorhat", "Teok", "Mariani"],
    "Kamrup": ["Amingaon", "Palashbari", "North Guwahati"],
    "Kamrup Metropolitan": ["Guwahati", "Dispur"],
    "Karbi Anglong": ["Diphu", "Bokajan", "Hamren"],
    "Karimganj": ["Karimganj", "Badarpur", "Patharkandi"],
    "Kokrajhar": ["Kokrajhar", "Gossaigaon", "Bhowraguri"],
    "Lakhimpur": ["North Lakhimpur", "Dhakuakhana", "Narayanpur"],
    "Majuli": ["Garamur", "Kamalabari"],
    "Morigaon": ["Morigaon", "Jagiroad", "Lahorighat"],
    "Nagaon": ["Nagaon", "Hojai", "Kampur"],
    "Nalbari": ["Nalbari", "Tihu", "Ghograpar"],
    "Sivasagar": ["Sivasagar", "Nazira", "Amguri"],
    "Sonitpur": ["Tezpur", "Dhekiajuli", "Biswanath Chariali"],
    "South Salmara-Mankachar": ["Hatsingimari", "Mankachar"],
    "Tinsukia": ["Tinsukia", "Makum", "Digboi"],
    "Udalguri": ["Udalguri", "Tangla", "Mazbat"],
    "West Karbi Anglong": ["Hamren", "Baithalangso"]
},

"Bihar": {
    "Araria": ["Araria", "Forbesganj", "Jogbani"],
    "Arwal": ["Arwal", "Karpi"],
    "Aurangabad": ["Aurangabad", "Daudnagar", "Obra"],
    "Banka": ["Banka", "Amarpur", "Katoria"],
    "Begusarai": ["Begusarai", "Barauni", "Teghra"],
    "Bhagalpur": ["Bhagalpur", "Naugachia", "Kahalgaon"],
    "Bhojpur": ["Arrah", "Piro", "Jagdispur"],
    "Buxar": ["Buxar", "Dumraon", "Itarhi"],
    "Darbhanga": ["Darbhanga", "Benipur", "Jale"],
    "East Champaran": ["Motihari", "Raxaul", "Chakia"],
    "Gaya": ["Gaya", "Bodh Gaya", "Sherghati"],
    "Gopalganj": ["Gopalganj", "Barauli", "Mirganj"],
    "Jamui": ["Jamui", "Jhajha", "Sikandra"],
    "Jehanabad": ["Jehanabad", "Makhdumpur", "Kako"],
    "Kaimur": ["Bhabua", "Mohania", "Ramgarh"],
    "Katihar": ["Katihar", "Manihari", "Kadwa"],
    "Khagaria": ["Khagaria", "Parbatta", "Mansi"],
    "Kishanganj": ["Kishanganj", "Thakurganj", "Bahadurganj"],
    "Lakhisarai": ["Lakhisarai", "Barahiya", "Surajgarha"],
    "Madhepura": ["Madhepura", "Murliganj", "Udakishunganj"],
    "Madhubani": ["Madhubani", "Jainagar", "Jhanjharpur"],
    "Munger": ["Munger", "Jamalpur", "Kharagpur"],
    "Muzaffarpur": ["Muzaffarpur", "Motipur", "Kanti"],
    "Nalanda": ["Bihar Sharif", "Rajgir", "Harnaut"],
    "Nawada": ["Nawada", "Rajauli", "Warisaliganj"],
    "Patna": ["Patna", "Danapur", "Barh", "Maner"],
    "Purnia": ["Purnia", "Dhamdaha", "Banmankhi"],
    "Rohtas": ["Sasaram", "Dehri", "Bikramganj"],
    "Saharsa": ["Saharsa", "Simri Bakhtiyarpur", "Salkhua"],
    "Samastipur": ["Samastipur", "Dalsinghsarai", "Rosera"],
    "Saran": ["Chhapra", "Marhaura", "Dighwara"],
    "Sheikhpura": ["Sheikhpura", "Barbigha", "Sheikhopur Sarai"],
    "Sheohar": ["Sheohar", "Piprahi"],
    "Sitamarhi": ["Sitamarhi", "Bairgania", "Riga"],
    "Siwan": ["Siwan", "Mairwa", "Barharia"],
    "Supaul": ["Supaul", "Birpur", "Triveniganj"],
    "Vaishali": ["Hajipur", "Mahnar Bazar", "Lalganj"],
    "West Champaran": ["Bettiah", "Narkatiaganj", "Bagaha"]
},

"Chhattisgarh": {
    "Balod": ["Balod", "Dalli Rajhara", "Gunderdehi"],
    "Baloda Bazar": ["Baloda Bazar", "Bhatapara", "Simga"],
    "Balrampur": ["Balrampur", "Ramanujganj", "Rajpur"],
    "Bastar": ["Jagdalpur", "Kondagaon", "Lohandiguda"],
    "Bemetara": ["Bemetara", "Berla", "Saja"],
    "Bijapur": ["Bijapur", "Bhairamgarh", "Bhopalpatnam"],
    "Bilaspur": ["Bilaspur", "Mungeli", "Ratanpur"],
    "Dantewada": ["Dantewada", "Geedam", "Barsoor"],
    "Dhamtari": ["Dhamtari", "Kurud", "Magarlod"],
    "Durg": ["Durg", "Bhilai", "Charoda", "Dhamdha"],
    "Gariaband": ["Gariaband", "Chhura", "Mainpur"],
    "Gaurela-Pendra-Marwahi": ["Pendra", "Gaurela", "Marwahi"],
    "Janjgir-Champa": ["Janjgir", "Champa", "Naila", "Akaltara"],
    "Jashpur": ["Jashpur", "Pathalgaon", "Kunkuri"],
    "Kabirdham": ["Kawardha", "Pandariya", "Sahaspur"],
    "Kanker": ["Kanker", "Pakhanjore", "Charama"],
    "Kondagaon": ["Kondagaon", "Narayanpur", "Makdi"],
    "Korba": ["Korba", "Katghora", "Dipka"],
    "Koriya": ["Baikunthpur", "Manendragarh", "Chirmiri"],
    "Mahasamund": ["Mahasamund", "Bagbahara", "Saraipali"],
    "Mungeli": ["Mungeli", "Lormi", "Pathariya"],
    "Narayanpur": ["Narayanpur", "Abujhmad"],
    "Raigarh": ["Raigarh", "Kharsia", "Sarangarh"],
    "Raipur": ["Raipur", "Tilda", "Arang"],
    "Rajnandgaon": ["Rajnandgaon", "Dongargarh", "Chhuikhadan"],
    "Sukma": ["Sukma", "Chhindgarh", "Dornapal"],
    "Surajpur": ["Surajpur", "Premnagar", "Bishrampur"],
    "Surguja": ["Ambikapur", "Lakhanpur", "Sitapur"]
},

"Gujarat": {
    "Ahmedabad": ["Ahmedabad", "Sanand", "Dholka", "Bavla"],
    "Amreli": ["Amreli", "Savarkundla", "Bagasara", "Lathi"],
    "Anand": ["Anand", "Vallabh Vidyanagar", "Khambhat", "Borsad"],
    "Aravalli": ["Modasa", "Dhansura", "Malpur"],
    "Banaskantha": ["Palanpur", "Deesa", "Dhanera", "Tharad"],
    "Bharuch": ["Bharuch", "Ankleshwar", "Jambusar", "Valia"],
    "Bhavnagar": ["Bhavnagar", "Palitana", "Mahuva", "Talaja"],
    "Botad": ["Botad", "Gadhada"],
    "Chhota Udaipur": ["Chhota Udaipur", "Bodeli", "Pavi Jetpur"],
    "Dahod": ["Dahod", "Zalod", "Limkheda"],
    "Dang": ["Ahwa"],
    "Devbhoomi Dwarka": ["Dwarka", "Okha", "Khambhalia"],
    "Gandhinagar": ["Gandhinagar", "Kalol", "Mansa"],
    "Gir Somnath": ["Veraval", "Kodinar", "Una"],
    "Jamnagar": ["Jamnagar", "Dwarka", "Kalavad", "Lalpur"],
    "Junagadh": ["Junagadh", "Keshod", "Mangrol", "Manavadar"],
    "Kheda": ["Nadiad", "Kheda", "Kapadvanj", "Mahudha"],
    "Kutch": ["Bhuj", "Gandhidham", "Mandvi", "Mundra"],
    "Mahisagar": ["Lunawada", "Balasinor", "Virpur"],
    "Mehsana": ["Mehsana", "Vijapur", "Unjha", "Visnagar"],
    "Morbi": ["Morbi", "Wankaner", "Halvad"],
    "Narmada": ["Rajpipla", "Kevadia", "Dediapada"],
    "Navsari": ["Navsari", "Gandevi", "Jalalpore"],
    "Panchmahal": ["Godhra", "Halol", "Kalol", "Ghoghamba"],
    "Patan": ["Patan", "Sidhpur", "Harij", "Radhanpur"],
    "Porbandar": ["Porbandar", "Ranavav", "Kutiyana"],
    "Rajkot": ["Rajkot", "Gondal", "Jamnagar", "Dhoraji"],
    "Sabarkantha": ["Himmatnagar", "Idar", "Prantij"],
    "Surat": ["Surat", "Bardoli", "Olpad", "Kamrej"],
    "Surendranagar": ["Surendranagar", "Wadhwan", "Limbdi", "Dhrangadhra"],
    "Tapi": ["Vyara", "Songadh"],
    "Vadodara": ["Vadodara", "Dabhoi", "Padra", "Savli"],
    "Valsad": ["Valsad", "Vapi", "Pardi", "Umargam"]
},

"Haryana": {
    "Ambala": ["Ambala", "Ambala Cantt", "Naraingarh"],
    "Bhiwani": ["Bhiwani", "Charkhi Dadri", "Loharu"],
    "Charkhi Dadri": ["Charkhi Dadri", "Badhra", "Baund Kalan"],
    "Faridabad": ["Faridabad", "Ballabhgarh"],
    "Fatehabad": ["Fatehabad", "Tohana", "Ratia"],
    "Gurugram": ["Gurugram", "Sohna", "Pataudi", "Manesar"],
    "Hisar": ["Hisar", "Hansi", "Narnaund"],
    "Jhajjar": ["Jhajjar", "Bahadurgarh", "Berara"],
    "Jind": ["Jind", "Narwana", "Julana", "Uchana"],
    "Kaithal": ["Kaithal", "Pundri", "Kalayat"],
    "Karnal": ["Karnal", "Gharaunda", "Nilokheri", "Taraori"],
    "Kurukshetra": ["Kurukshetra", "Thanesar", "Shahbad", "Pehowa"],
    "Mahendragarh": ["Mahendragarh", "Narnaul", "Kanina"],
    "Nuh": ["Nuh", "Ferozepur Jhirka", "Punhana"],
    "Palwal": ["Palwal", "Hathin", "Hodal"],
    "Panchkula": ["Panchkula", "Kalka", "Pinjore"],
    "Panipat": ["Panipat", "Samalkha", "Israna"],
    "Rewari": ["Rewari", "Dharuhera", "Bawal"],
    "Rohtak": ["Rohtak", "Meham", "Kalanaur"],
    "Sirsa": ["Sirsa", "Ellenabad", "Dabwali"],
    "Sonipat": ["Sonipat", "Gohana", "Ganaur", "Kharkhoda"],
    "Yamunanagar": ["Yamunanagar", "Jagadhri", "Radaur", "Chhachhrauli"]
},

"HimachalPardesh": {
    "Bilaspur": ["Bilaspur", "Ghumarwin", "Jhandutta"],
    "Chamba": ["Chamba", "Bhattiyat", "Dalhousie"],
    "Hamirpur": ["Hamirpur", "Nadaun", "Sujanpur"],
    "Kangra": ["Dharamshala", "Kangra", "Palampur", "Nagrota"],
    "Kinnaur": ["Reckong Peo", "Kalpa", "Powari"],
    "Kullu": ["Kullu", "Manali", "Bhuntar"],
    "Lahaul and Spiti": ["Keylong", "Kaza", "Udaipur"],
    "Mandi": ["Mandi", "Sundernagar", "Padhar"],
    "Shimla": ["Shimla", "Theog", "Chopal", "Naldehra"],
    "Sirmaur": ["Nahan", "Paonta Sahib", "Rajban"],
    "Solan": ["Solan", "Kasauli", "Nalagarh"],
    "Una": ["Una", "Amb", "Haroli"]
},

"JammuKashmir": {
    "Jammu": ["Jammu", "Udhampur", "Kathua", "R.S. Pura"],
    "Samba": ["Samba", "Vijaypur", "Ghagwal"],
    "Ramban": ["Ramban", "Banihal", "Udhampur"],
    "Doda": ["Doda", "Bhaderwah", "Assar"],
    "Kishtwar": ["Kishtwar", "Padder", "Thathri"],
    "Poonch": ["Poonch", "Surankote", "Mendhar"],
    "Rajouri": ["Rajouri", "Nowshera", "Sunderbani"],
    "Anantnag": ["Anantnag", "Bijbehara", "Pahalgam"],
    "Kulgam": ["Kulgam", "Qazigund", "D.H. Pora"],
    "Pulwama": ["Pulwama", "Rajpora", "Tral"],
    "Shopian": ["Shopian", "Zainapora", "Hirpora"],
    "Srinagar": ["Srinagar", "Ganderbal", "Budgam"],
    "Bandipora": ["Bandipora", "Sumbal", "Gurez"],
    "Baramulla": ["Baramulla", "Sopore", "Pattan"],
    "Kupwara": ["Kupwara", "Handwara", "Lolab"],
    "Jammu Division": ["Jammu", "Udhampur", "Kathua", "Doda"],
    "Kashmir Division": ["Srinagar", "Anantnag", "Baramulla", "Kupwara"]
},

"Goa": {
    "North Goa": ["Panaji", "Mapusa", "Bicholim", "Pernem", "Porvorim"],
    "South Goa": ["Margao", "Vasco da Gama", "Quepem", "Cortalim", "Canacona"]
},

"Jharkhand": {
    "Bokaro": ["Bokaro Steel City", "Chas", "Bokaro"],
    "Chatra": ["Chatra", "Gumla", "Kunda"],
    "Deoghar": ["Deoghar", "Madhupur", "Jarmundi"],
    "Dhanbad": ["Dhanbad", "Jharia", "Beltola"],
    "Dumka": ["Dumka", "Jama", "Sarsabad"],
    "East Singhbhum": ["Jamshedpur", "Ghatshila", "Chakradharpur"],
    "Garhwa": ["Garhwa", "Ranka", "Bharso"],
    "Giridih": ["Giridih", "Dumri", "Bagodar"],
    "Godda": ["Godda", "Madhupur", "Poreyahat"],
    "Gumla": ["Gumla", "Ratu", "Bishunpur"],
    "Hazaribagh": ["Hazaribagh", "Bagodar", "Barkagaon"],
    "Jamtara": ["Jamtara", "Nala", "Karma"],
    "Khunti": ["Khunti", "Tango", "Murhu"],
    "Koderma": ["Koderma", "Jhumri Tilaiya", "Satgawan"],
    "Latehar": ["Latehar", "Balumath", "Chak"],
    "Lohardaga": ["Lohardaga", "Kuru", "Silli"],
    "Palamu": ["Daltonganj", "Panki", "Hariharganj"],
    "Ranchi": ["Ranchi", "Bariatu", "Kanke"],
    "Sahebganj": ["Sahebganj", "Rajmahal", "Udhwa"],
    "Seraikela-Kharsawan": ["Seraikela", "Kuchai", "Kharsawan"],
    "Simdega": ["Simdega", "Thethaitangar", "Kersai"],
    "West Singhbhum": ["Chaibasa", "Noamundi", "Kera"],
    "Ranchi": ["Ranchi", "Kanke", "Hatu"]
},

"Karnataka": {
    "Bagalkot": ["Bagalkot", "Badami", "Hungund"],
    "Ballari": ["Ballari", "Hospet", "Bellary"],
    "Belagavi": ["Belagavi", "Hubli", "Bailhongal"],
    "Bengaluru Rural": ["Bengaluru", "Doddaballapura", "Devanahalli"],
    "Bengaluru Urban": ["Bengaluru", "Koramangala", "Whitefield"],
    "Bidar": ["Bidar", "Basavakalyan", "Humnabad"],
    "Chamarajanagar": ["Chamarajanagar", "Yelandur", "Kollegal"],
    "Chikkaballapur": ["Chikkaballapur", "Nandi Hills", "Chintamani"],
    "Chikmagalur": ["Chikmagalur", "Kudremukh", "Tarikere"],
    "Chitradurga": ["Chitradurga", "Hosadurga", "Davanagere"],
    "Dakshina Kannada": ["Mangaluru", "Udupi", "Puttur"],
    "Davangere": ["Davangere", "Harihar", "Jagalur"],
    "Dharwad": ["Dharwad", "Hubli", "Navalgund"],
    "Gadag": ["Gadag", "Nargund", "Gajendragarh"],
    "Hassan": ["Hassan", "Channarayapatna", "Arasikere"],
    "Haveri": ["Haveri", "Ranebennur", "Byadgi"],
    "Kalaburagi": ["Kalaburagi", "Sedam", "Shorapur"],
    "Kodagu": ["Madikeri", "Somwarpet", "Virajpet"],
    "Kolar": ["Kolar", "Malur", "Bangalore"],
    "Koppal": ["Koppal", "Kushtagi", "Yelburga"],
    "Mandya": ["Mandya", "Maddur", "Srirangapatna"],
    "Mysuru": ["Mysuru", "Nanjangud", "Hunsur"],
    "Raichur": ["Raichur", "Lingasugur", "Manvi"],
    "Ramanagara": ["Ramanagara", "Kanakapura", "Magadi"],
    "Shimoga": ["Shimoga", "Sagar", "Shikaripur"],
    "Tumakuru": ["Tumakuru", "Tiptur", "Kunigal"],
    "Udupi": ["Udupi", "Karkala", "Kundapur"],
    "Uttara Kannada": ["Karwar", "Yellapur", "Dandeli"],
    "Vijayapura": ["Vijayapura", "Bijapur", "Bagalkot"],
    "Yadgir": ["Yadgir", "Shahpur", "Gogi"]
},

"Kerala": {
    "Alappuzha": ["Alappuzha", "Cherthala", "Mannanchery"],
    "Ernakulam": ["Kochi", "Aluva", "Muvattupuzha"],
    "Idukki": ["Munnar", "Thodupuzha", "Pallom"],
    "Kannur": ["Kannur", "Payyannur", "Thalassery"],
    "Kasaragod": ["Kasaragod", "Bekal", "Pallikkara"],
    "Kollam": ["Kollam", "Paravur", "Punalur"],
    "Kottayam": ["Kottayam", "Changanassery", "Pampady"],
    "Kozhikode": ["Kozhikode", "Vadakara", "Koyilandy"],
    "Malappuram": ["Malappuram", "Perinthalmanna", "Ponnani"],
    "Palakkad": ["Palakkad", "Ottapalam", "Kongad"],
    "Pathanamthitta": ["Pathanamthitta", "Thiruvalla", "Adoor"],
    "Thrissur": ["Thrissur", "Irinjalakuda", "Chalakudy"],
    "Trivandrum": ["Thiruvananthapuram", "Neyyattinkara", "Kovalam"],
    "Wayanad": ["Kalpetta", "Sultan Bathery", "Mananthavady"]
},

"MadhyaPradesh": {
    "Agar Malwa": ["Agar", "Ujjain", "Sarangpur"],
    "Alirajpur": ["Alirajpur", "Jobat", "Dabhoi"],
    "Anuppur": ["Anuppur", "Pushprajgarh", "Kawardha"],
    "Ashoknagar": ["Ashoknagar", "Mungaoli", "Chanderi"],
    "Balaghat": ["Balaghat", "Malanjkhand", "Katangi"],
    "Barwani": ["Barwani", "Anjad", "Rajpur"],
    "Betul": ["Betul", "Agar", "Multai"],
    "Bhind": ["Bhind", "Gohad", "Mau"],
    "Bhopal": ["Bhopal", "Habibganj", "Hoshangabad"],
    "Burhanpur": ["Burhanpur", "Khaknar", "Nepanagar"],
    "Chhatarpur": ["Chhatarpur", "Khajuraho", "Rajnagar"],
    "Chhindwara": ["Chhindwara", "Pachmarhi", "Sausar"],
    "Damoh": ["Damoh", "Patera", "Kukshi"],
    "Datia": ["Datia", "Seondha", "Bhander"],
    "Dewas": ["Dewas", "Dewas", "Ujjain"],
    "Dhar": ["Dhar", "Dhar", "Manawar"],
    "Dindori": ["Dindori", "Gadchiroli", "Bichhiya"],
    "Guna": ["Guna", "Raghogarh", "Chachoura"],
    "Gwalior": ["Gwalior", "Morena", "Dabra"],
    "Harda": ["Harda", "Harda", "Sarangpur"],
    "Hoshangabad": ["Hoshangabad", "Narmadapuram", "Itarsi"],
    "Indore": ["Indore", "Mhow", "Depalpur"],
    "Jabalpur": ["Jabalpur", "Patan", "Sihora"],
    "Jhabua": ["Jhabua", "Petlawad", "Rajasthan"],
    "Katni": ["Katni", "Maihar", "Sleemanabad"],
    "Khandwa": ["Khandwa", "Burhanpur", "Harsud"],
    "Khargone": ["Khargone", "Kasrawad", "Jhiranya"],
    "Mandla": ["Mandla", "Mandla", "Bichhia"],
    "Mandsaur": ["Mandsaur", "Neemuch", "Pipliyapala"],
    "Morena": ["Morena", "Gwalior", "Dabra"],
    "Narsinghpur": ["Narsinghpur", "Gadarwara", "Sagar"],
    "Satna": ["Satna", "Maihar", "Rampur"],
    "Sehore": ["Sehore", "Ashta", "Bhopal"],
    "Seoni": ["Seoni", "Lakhnadon", "Kawardha"],
    "Shahdol": ["Shahdol", "Jaisinghnagar", "Amarpur"],
    "Shajapur": ["Shajapur", "Agar", "Shujalpur"],
    "Sheopur": ["Sheopur", "Sabalgarh", "Kachnaria"],
    "Shivpuri": ["Shivpuri", "Kolaras", "Pachore"],
    "Sidhi": ["Sidhi", "Majhgaon", "Churhat"],
    "Singrauli": ["Singrauli", "Waidhan", "Chitrangi"],
    "Tikamgarh": ["Tikamgarh", "Baldeogarh", "Jhansi"],
    "Ujjain": ["Ujjain", "Nagda", "Bajna"],
    "Umaria": ["Umaria", "Bandhavgarh", "Shahdol"],
    "Vidisha": ["Vidisha", "Gyaraspur", "Kurwai"],
    "Zhola": ["Zhola", "Chachoura", "Raghogarh"]
},

"Maharashtra": {
    "Ahmednagar": ["Ahmednagar", "Shrirampur", "Junnar"],
    "Akola": ["Akola", "Murtizapur", "Patur"],
    "Amravati": ["Amravati", "Morshi", "Achalpur"],
    "Aurangabad": ["Aurangabad", "Jalna", "Paithan"],
    "Beed": ["Beed", "Ambajogai", "Patoda"],
    "Bhandara": ["Bhandara", "Tumsar", "Mowad"],
    "Bhiwandi": ["Bhiwandi", "Kalyan", "Thane"],
    "Buldhana": ["Buldhana", "Khamgaon", "Malkapur"],
    "Chandrapur": ["Chandrapur", "Warora", "Gadchiroli"],
    "Dhule": ["Dhule", "Shahada", "Sakri"],
    "Gadchiroli": ["Gadchiroli", "Aheri", "Desaiganj"],
    "Gondia": ["Gondia", "Tirora", "Sakti"],
    "Hingoli": ["Hingoli", "Khipro", "Jalna"],
    "Jalgaon": ["Jalgaon", "Parola", "Chopda"],
    "Jalna": ["Jalna", "Badnapur", "Mantha"],
    "Kolhapur": ["Kolhapur", "Ichalkaranji", "Karvir"],
    "Latur": ["Latur", "Ausa", "Nilanga"],
    "Mumbai": ["Mumbai", "Thane", "Borivali"],
    "Nagpur": ["Nagpur", "Katol", "Ramtek"],
    "Nanded": ["Nanded", "Biloli", "Hadgaon"],
    "Nandurbar": ["Nandurbar", "Navapur", "Akkalkuwa"],
    "Nashik": ["Nashik", "Sinnar", "Malegaon"],
    "Osmanabad": ["Osmanabad", "Tuljapur", "Kallam"],
    "Palghar": ["Palghar", "Vasai", "Nalasopara"],
    "Parbhani": ["Parbhani", "Jintur", "Sonpeth"],
    "Pune": ["Pune", "Pimpri-Chinchwad", "Hinjewadi"],
    "Raigad": ["Raigad", "Alibag", "Pen"],
    "Ratnagiri": ["Ratnagiri", "Rajapur", "Dapoli"],
    "Sangli": ["Sangli", "Kupwad", "Miraj"],
    "Satara": ["Satara", "Koregaon", "Mahabaleshwar"],
    "Sindhudurg": ["Sindhudurg", "Oros", "Malvan"],
    "Solapur": ["Solapur", "Karmala", "Mangalvedhe"],
    "Thane": ["Thane", "Dombivli", "Kalyan"],
    "Wardha": ["Wardha", "Arvi", "Hinganghat"],
    "Washim": ["Washim", "Karanja", "Mangrulpir"],
    "Yavatmal": ["Yavatmal", "Pusad", "Digras"]
},

"Manipur": {
    "Bishnupur": ["Bishnupur", "Moirang", "Nambol"],
    "Chandel": ["Chandel", "Chakpikarong", "Moreh"],
    "Churachandpur": ["Churachandpur", "Lamka", "Henglep"],
    "Imphal East": ["Imphal", "Kangchup", "Lamsang"],
    "Imphal West": ["Imphal", "Thangmeiband", "Hiyangthang"],
    "Jiribam": ["Jiribam", "Nungba", "Yairipok"],
    "Kakching": ["Kakching", "Kakching Khunou", "Kakching Hiyanglam"],
    "Senapati": ["Senapati", "Mao", "Maram"],
    "Tamenglong": ["Tamenglong", "Tamei", "Nungba"],
    "Thoubal": ["Thoubal", "Wangjing", "Kakching"],
    "Ukhrul": ["Ukhrul", "Mao", "Khangkhui"]
},

"Meghalaya": {
    "East Garo Hills": ["Williamnagar", "Bajengdoba", "Rongrenggre"],
    "East Jaintia Hills": ["Khliehriat", "Jowai", "Nartiang"],
    "East Khasi Hills": ["Shillong", "Cherrapunji", "Mawsynram"],
    "North Garo Hills": ["Rongjeng", "Kharkutta", "Resubelpara"],
    "Ri-Bhoi": ["Nongpoh", "Mawhati", "Umran"],
    "South Garo Hills": ["Bongalgre", "Baghmara", "Hahim"],
    "South West Garo Hills": ["Ampati", "Betasing", "Mahendraganj"],
    "South West Khasi Hills": ["Mawkyrwat", "Ranikor", "Nongstoin"],
    "West Garo Hills": ["Tura", "Dalu", "Dadenggre"],
    "West Jaintia Hills": ["Jowai", "Sutnga", "Khliehriat"],
    "West Khasi Hills": ["Nongstoin", "Mairang", "Mawkyrwat"]
},

"Mizoram": {
    "Aizawl": ["Aizawl", "Saitual", "Tawipui"],
    "Champhai": ["Champhai", "Zokhawthar", "Vairengte"],
    "Kolasib": ["Kolasib", "Hnahthial", "Darlawn"],
    "Lawngtlai": ["Lawngtlai", "Biate", "Tuipang"],
    "Lunglei": ["Lunglei", "Khawzawl", "Bualpui"],
    "Mamit": ["Mamit", "Khawbung", "Tuilut"],
    "Serchhip": ["Serchhip", "Lungpher", "Tlangnuam"],
    "Siaha": ["Siaha", "Lunglei", "Chawngte"],
    "Aizawl": ["Aizawl", "Saitual", "Tawipui"]
},

"Nagaland": {
    "Dimapur": ["Dimapur", "Kohima", "Chumukedima"],
    "Kiphire": ["Kiphire", "Pungro", "Kiusam"],
    "Kohima": ["Kohima", "Tseminyu", "Jotsoma"],
    "Mokokchung": ["Mokokchung", "Longkhum", "Chuchuyimlang"],
    "Mon": ["Mon", "Longwa", "Mokokchung"],
    "Peren": ["Peren", "Ruzhazho", "Lapang"],
    "Phek": ["Phek", "Meluri", "Chozuba"],
    "Tuensang": ["Tuensang", "Longkhim", "Shamator"],
    "Wokha": ["Wokha", "Chukitong", "Bhandari"],
    "Zunheboto": ["Zunheboto", "Atoizu", "Noklak"]
},

"Mizoram": {
    "Aizawl": ["Aizawl", "Saitual", "Tawipui", "Khawzawl"],
    "Champhai": ["Champhai", "Zokhawthar", "Vairengte", "Serchhip"],
    "Kolasib": ["Kolasib", "Hnahthial", "Darlawn", "Bilkhawthlir"],
    "Lawngtlai": ["Lawngtlai", "Biate", "Tuipang", "Khawzawl"],
    "Lunglei": ["Lunglei", "Khawzawl", "Bualpui", "Saitual"],
    "Mamit": ["Mamit", "Khawbung", "Tuilut", "Hawlhlu"],
    "Serchhip": ["Serchhip", "Lungpher", "Tlangnuam", "N. Saimi"],
    "Siaha": ["Siaha", "Lunglei", "Chawngte", "Hengchang"]
},

"Nagaland": {
    "Dimapur": ["Dimapur", "Kohima", "Chumukedima", "Mangkolemba"],
    "Kiphire": ["Kiphire", "Pungro", "Kiusam", "Sitimi"],
    "Kohima": ["Kohima", "Tseminyu", "Jotsoma", "Kohima Village"],
    "Mokokchung": ["Mokokchung", "Longkhum", "Chuchuyimlang", "Mangkolemba"],
    "Mon": ["Mon", "Longwa", "Mokokchung", "Noklak"],
    "Peren": ["Peren", "Ruzhazho", "Lapang", "Khomong"],
    "Phek": ["Phek", "Meluri", "Chozuba", "Phek Town"],
    "Tuensang": ["Tuensang", "Longkhim", "Shamator", "Chongkham"],
    "Wokha": ["Wokha", "Chukitong", "Bhandari", "Sanis"],
    "Zunheboto": ["Zunheboto", "Atoizu", "Noklak", "Phek"]
},

"Odisha": {
    "Angul": ["Angul", "Talcher", "Pallahara"],
    "Balangir": ["Balangir", "Kantabanji", "Titlagarh"],
    "Baleswar": ["Balasore", "Soro", "Bhadrak"],
    "Bargarh": ["Bargarh", "Barpali", "Bhatli"],
    "Bhadrak": ["Bhadrak", "Dhamra", "Basudevpur"],
    "Boudh": ["Boudh", "Sonepur", "Athmallik"],
    "Cuttack": ["Cuttack", "Jagatpur", "Barambadi"],
    "Debagarh": ["Deogarh", "Reamal", "Baliapala"],
    "Dhenkanal": ["Dhenkanal", "Kamakhyanagar", "Bhuban"],
    "Ganjam": ["Ganjam", "Berhampur", "Chikiti"],
    "Gajapati": ["Gajapati", "Parlakhemundi", "R Udayagiri"],
    "Jagatsinghpur": ["Jagatsinghpur", "Naugaon", "Kujang"],
    "Jajpur": ["Jajpur", "Kantilo", "Sukinda"],
    "Jharsuguda": ["Jharsuguda", "Laikera", "Kolabira"],
    "Kalahandi": ["Kalahandi", "Bhawanipatna", "Kesinga"],
    "Kandhamal": ["Phulbani", "Baliguda", "Kalinga"],
    "Kendrapara": ["Kendrapara", "Aul", "Rajkanika"],
    "Kendujhar": ["Keonjhar", "Barbil", "Joda"],
    "Khurda": ["Khurda", "Bhubaneswar", "Jatni"],
    "Koraput": ["Koraput", "Jeypore", "Sunabeda"],
    "Malkangiri": ["Malkangiri", "Mathili", "Kalimela"],
    "Nabarangpur": ["Nabarangpur", "Umerkote", "Kodinga"],
    "Nayagarh": ["Nayagarh", "Daspalla", "Khandapada"],
    "Nuapada": ["Nuapada", "Khariar", "Komna"],
    "Rayagada": ["Rayagada", "Gunupur", "Padmapur"],
    "Sambalpur": ["Sambalpur", "Jharsuguda", "Rairakhol"],
    "Subarnapur": ["Subarnapur", "Birmaharajpur", "Sonepur"],
    "Sundargarh": ["Sundargarh", "Rourkela", "Biramitrapur"]
},

"Punjab": {
    "Amritsar": ["Amritsar", "Tarn Taran", "Khem Karan"],
    "Barnala": ["Barnala", "Mandi Gobindgarh", "Sangrur"],
    "Bathinda": ["Bathinda", "Mansa", "Talwandi Sabo"],
    "Faridkot": ["Faridkot", "Kotkapura", "Fattuwal"],
    "Fatehgarh Sahib": ["Fatehgarh Sahib", "Mandi Gobindgarh", "Sirhind"],
    "Fazilka": ["Fazilka", "Abohar", "Khuian Sarwar"],
    "Gurdaspur": ["Gurdaspur", "Pathankot", "Batala"],
    "Hoshiarpur": ["Hoshiarpur", "Dasuya", "Garhshankar"],
    "Jalandhar": ["Jalandhar", "Phagwara", "Kapurthala"],
    "Kapurthala": ["Kapurthala", "Sultanpur Lodhi", "Jalandhar"],
    "Ludhiana": ["Ludhiana", "Khanna", "Payal"],
    "Mansa": ["Mansa", "Budhlada", "Sardulgarh"],
    "Moga": ["Moga", "Kotar", "Moga"],
    "Mohali": ["Mohali", "Chandigarh", "Panchkula"],
    "Muktsar": ["Muktsar", "Sri Muktsar Sahib", "Guru Har Sahai"],
    "Nawanshahr": ["Nawanshahr", "Balachaur", "Khamano"],
    "Pathankot": ["Pathankot", "Dinankpur", "Sujanpur"],
    "Patiala": ["Patiala", "Rajpura", "Samana"],
    "Rupnagar": ["Rupnagar", "Nangal", "Kiratpur Sahib"],
    "Sangrur": ["Sangrur", "Lehra"],
    "SAS Nagar": ["SAS Nagar", "Mohali", "Panchkula"],
    "Tarn Taran": ["Tarn Taran", "Khem Karan", "Bhikhiwind"]
},

"Rajasthan": {
    "Ajmer": ["Ajmer", "Kishangarh", "Nasirabad"],
    "Alwar": ["Alwar", "Bhiwadi", "Rajgarh"],
    "Banswara": ["Banswara", "Mandal", "Sagwara"],
    "Baran": ["Baran", "Chhabra", "Kishanganj"],
    "Barmer": ["Barmer", "Jaisalmer", "Rani"],
    "Bharatpur": ["Bharatpur", "Kumher", "Deeg"],
    "Bhilwara": ["Bhilwara", "Mandal", "Raipur"],
    "Bikaner": ["Bikaner", "Nokha", "Pugal"],
    "Bundi": ["Bundi", "Keshoraipatan", "Indergarh"],
    "Chittorgarh": ["Chittorgarh", "Nimbahera", "Ratangarh"],
    "Churu": ["Churu", "Sardarshahar", "Taranagar"],
    "Dausa": ["Dausa", "Bandikui", "Lalsot"],
    "Dholpur": ["Dholpur", "Bari", "Rajakhera"],
    "Dungarpur": ["Dungarpur", "Sagwara", "Banswara"],
    "Ganganagar": ["Ganganagar", "Anupgarh", "Padampur"],
    "Hanumangarh": ["Hanumangarh", "Pilibanga", "Nohar"],
    "Jaisalmer": ["Jaisalmer", "Sam", "Pokhran"],
    "Jalore": ["Jalore", "Sirohi", "Bali"],
    "Jhalawar": ["Jhalawar", "Kota", "Bhadra"],
    "Jhunjhunu": ["Jhunjhunu", "Nawalgarh", "Churu"],
    "Jodhpur": ["Jodhpur", "Osian", "Pali"],
    "Kota": ["Kota", "Baran", "Sawanpura"],
    "Nagaur": ["Nagaur", "Merta", "Didwana"],
    "Pali": ["Pali", "Rani", "Marwar"],
    "Rajasthan": ["Ajmer", "Alwar", "Banswara"],
    "Rajsamand": ["Rajsamand", "Kankroli", "Nathdwara"],
    "Sawai Madhopur": ["Sawai Madhopur", "Ranthambore", "Chauth Ka Barwara"],
    "Sikar": ["Sikar", "Laxmangarh", "Khandela"],
    "Sirohi": ["Sirohi", "Pindwara", "Abu Road"],
    "Tonk": ["Tonk", "Deoli", "Malpura"],
    "Udaipur": ["Udaipur", "Rajsamand", "Kumbhalgarh"]
},

"Sikkim": {
    "East Sikkim": ["Gangtok", "Ranipool", "Nathula"],
    "West Sikkim": ["Gyalshing", "Pelling", "Yuksom"],
    "North Sikkim": ["Mangan", "Lachen", "Lachung"],
    "South Sikkim": ["Namchi", "Ravangla", "Jorethang"]
},

"TamilNadu": {
    "Chennai": ["Chennai", "Tambaram", "T Nagar"],
    "Coimbatore": ["Coimbatore", "Pollachi", "Karamadai"],
    "Cuddalore": ["Cuddalore", "Chidambaram", "Kurinjipadi"],
    "Dharmapuri": ["Dharmapuri", "Harur", "Pennagaram"],
    "Dindigul": ["Dindigul", "Kodaikanal", "Natham"],
    "Erode": ["Erode", "Bhavani", "Perundurai"],
    "Kanchipuram": ["Kanchipuram", "Sriperumbudur", "Poonamallee"],
    "Kanyakumari": ["Nagercoil", "Kanyakumari", "Padmanabhapuram"],
    "Karur": ["Karur", "Dindigul", "Aravakurichi"],
    "Krishnagiri": ["Krishnagiri", "Hosur", "Denkanikottai"],
    "Madurai": ["Madurai", "Sholavandan", "Vadipatti"],
    "Nagapattinam": ["Nagapattinam", "Kumbakonam", "Thiruvarur"],
    "Namakkal": ["Namakkal", "Rasipuram", "Kolli Hills"],
    "Nilgiris": ["Ooty", "Coonoor", "Gudalur"],
    "Perambalur": ["Perambalur", "Veppanthattai", "Ariyalur"],
    "Pudukottai": ["Pudukottai", "Aranthangi", "Karaikudi"],
    "Ramanathapuram": ["Ramanathapuram", "Kothandam", "Rameswaram"],
    "Salem": ["Salem", "Attur", "Yercaud"],
    "Sivaganga": ["Sivaganga", "Karaikudi", "Sivaganga"],
    "Thanjavur": ["Thanjavur", "Kumbakonam", "Tiruvarur"],
    "Theni": ["Theni", "Bodinayakkanur", "Cumbum"],
    "Thoothukudi": ["Tuticorin", "Kovilpatti", "Thoothukudi"],
    "Tiruchirappalli": ["Tiruchirappalli", "Srirangam", "Manapparai"],
    "Tirunelveli": ["Tirunelveli", "Nagercoil", "Tenkasi"],
    "Tiruppur": ["Tiruppur", "Udumalpet", "Palladam"],
    "Tiruvallur": ["Tiruvallur", "Poonamallee", "Sriperumbudur"],
    "Tiruvannamalai": ["Tiruvannamalai", "Polur", "Arni"],
    "Vellore": ["Vellore", "Katpadi", "Vaniyambadi"],
    "Viluppuram": ["Viluppuram", "Kallakurichi", "Tindivanam"],
    "Virudhunagar": ["Virudhunagar", "Sivakasi", "Aruppukottai"]
},
"Telangana": {
    "Adilabad": ["Adilabad", "Kumram Bheem", "Nirmal"],
    "Bhadradri Kothagudem": ["Bhadrachalam", "Kothagudem", "Paloncha"],
    "Hyderabad": ["Hyderabad", "Secunderabad", "Charminar"],
    "Jagtial": ["Jagtial", "Korutla", "Metpally"],
    "Jagtial": ["Jagtial", "Korutla", "Metpally"],
    "Jangoan": ["Jangoan", "Jangaon", "Raiparthy"],
    "Jayashankar Bhupalpally": ["Warangal", "Bhupalpally", "Jaya Shankar"],
    "Jogulamba Gadwal": ["Gadwal", "Kulkacherla", "Damaragidda"],
    "Kamareddy": ["Kamareddy", "Bichkunda", "Kompally"],
    "Karimnagar": ["Karimnagar", "Huzurabad", "Jagitial"],
    "Khammam": ["Khammam", "Kothagudem", "Palvancha"],
    "Mahabubabad": ["Mahabubabad", "Maripeda", "Narsampet"],
    "Mahabubnagar": ["Mahabubnagar", "Nagarkurnool", "Wanaparthy"],
    "Mancherial": ["Mancherial", "Bellampalli", "Mandamarri"],
    "Medak": ["Medak", "Sangareddy", "Narsapur"],
    "Medchal-Malkajgiri": ["Medchal", "Malkajgiri", "Keesara"],
    "Nagarkurnool": ["Nagarkurnool", "Mahbubnagar", "Wanaparthy"],
    "Nalgonda": ["Nalgonda", "Bhuvanagiri", "Devarakonda"],
    "Nirmal": ["Nirmal", "Adilabad", "Kumram Bheem"],
    "Peddapalli": ["Peddapalli", "Manthani", "Ramagundam"],
    "Rajanna Sircilla": ["Sircilla", "Peddapalli", "Gadwal"],
    "Ranga Reddy": ["Ranga Reddy", "Shamshabad", "Malkajgiri"],
    "Warangal": ["Warangal", "Hanamkonda", "Kazipet"],
    "Yadadri Bhuvanagiri": ["Bhuvanagiri", "Yadagirigutta", "Raigiri"]
},


"Tripura": {
    "Agartala": ["Agartala", "Udaipur", "Sonamura"],
    "Dhalai": ["Ambassa", "Dhalai", "Manu"],
    "Khowai": ["Khowai", "Teliamura", "Khowai Town"],
    "North Tripura": ["Dharmanagar", "Kanchanpur", "Panisagar"],
    "Sepahijala": ["Bishalgarh", "Sepahijala", "Jirania"],
    "South Tripura": ["Udaipur", "Belonia", "Sabroom"],
    "West Tripura": ["Agartala", "Jirania", "Kailashahar"]
}





  };

 
function updateDistricts() {
    const stateSelect = document.getElementById("state");
    const districtSelect = document.getElementById("district");

    const selectedState = stateSelect.value;

    // Clear previous districts
    districtSelect.innerHTML = "<option value=''>Select District</option>";

    if (selectedState) {
        const districts = Object.keys(data[selectedState]);

        districts.forEach(district => {
            const option = document.createElement("option");
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });
    }
}

 
</script>
</body>
</html>

