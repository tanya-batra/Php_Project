<?php
session_start();
include('connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['phone']) && isset($_POST['firstname'])) {
    // Sanitize input data
    $firstname = mysqli_real_escape_string($con, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($con, trim($_POST['lastname']));
    $phone = mysqli_real_escape_string($con, trim($_POST['phone']));
    $state = mysqli_real_escape_string($con, trim($_POST['state']));
    $district = mysqli_real_escape_string($con, trim($_POST['district']));
    $pincode = mysqli_real_escape_string($con, trim($_POST['pincode']));
    $address = mysqli_real_escape_string($con, trim($_POST['address']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $payment_status = "pending";
    $datetime = date('Y-m-d H:i:s');
    
    // Retrieve the last bill number
    $result = mysqli_query($con, "SELECT bill_number FROM payments ORDER BY sno DESC LIMIT 1");
    $row = mysqli_fetch_assoc($result);

    // Increment the last bill number by 1
    if ($row) {
        $last_bill_number = (int) $row['bill_number'];  // Convert bill number to integer
        $new_bill_number = sprintf("%05d", $last_bill_number + 1);  // Format as 00001, 00002, etc.
    } else {
        // If no records exist, start from 00001
        $new_bill_number = "00001";
    }
    
    // Insert payment data with the new bill number
    $query = "INSERT INTO payments (firstname, lastname, phone, state, district, pincode, address, email, payment_status, datetime, bill_number) 
              VALUES ('$firstname', '$lastname', '$phone', '$state', '$district', '$pincode', '$address', '$email', '$payment_status', '$datetime', '$new_bill_number')";
    
    if (mysqli_query($con, $query)) {
        $_SESSION['OID'] = mysqli_insert_id($con);
        ?>
        <script>
            alert('Data inserted successfully. Order ID: <?php echo $_SESSION["OID"]; ?>. Bill Number: <?php echo $new_bill_number; ?>');
        </script>
        <?php
    } else {
        ?>
        <script>
            alert('Error inserting data: <?php echo mysqli_error($con); ?>');
        </script>
        <?php
        exit;
    }
}

if (isset($_POST['payment_id']) && isset($_SESSION['OID'])) {
    $payment_id = mysqli_real_escape_string($con, trim($_POST['payment_id']));
    
    // Update payment status to 'complete'
    $update_query = "UPDATE payments SET payment_status='complete', payment_id='$payment_id' WHERE sno='" . $_SESSION['OID'] . "'";
    
    if (mysqli_query($con, $update_query)) {
        ?>
        <script>
            alert('Payment updated successfully.');
        </script>
        <?php

        // Fetch payment details
        $result = mysqli_query($con, "SELECT * FROM payments WHERE sno='" . $_SESSION['OID'] . "'");
        $payment_data = mysqli_fetch_assoc($result);

        // Only send email if payment status is 'complete'
        if ($payment_data['payment_status'] === 'complete') {
            // Generate email content for the invoice
            $invoice_content = "
                <div style='font-family: Arial, sans-serif; color: #333;'>
                    <h2 style='color: #1a73e8;'>Invoice from Himland Veda</h2>
                    <p>Hello <strong>" . $payment_data['firstname'] . " " . $payment_data['lastname'] . "</strong>,</p>
                    <p>Thank you for your payment. Below are your payment details:</p>
                    
                    <table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
                        <tr style='background-color: #f2f2f2;'>
                            <th colspan='2' style='text-align: left;'>Invoice Details</th>
                        </tr>
                        <tr>
                            <td><strong>Bill Number</strong></td>
                            <td>" . $payment_data['bill_number'] . "</td>
                        </tr>
                      
                        <tr>
                            <td><strong>Payment ID</strong></td>
                            <td>" . $payment_id . "</td>
                        </tr>
                        <tr>
                            <td><strong>Full Name</strong></td>
                            <td>" . $payment_data['firstname'] . " " . $payment_data['lastname'] . "</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>" . $payment_data['email'] . "</td>
                        </tr>
                        <tr>
                            <td><strong>Phone</strong></td>
                            <td>" . $payment_data['phone'] . "</td>
                        </tr>
                        <tr>
                            <td><strong>Address</strong></td>
                            <td>" . $payment_data['address'] . ", " . $payment_data['district'] . ", " . $payment_data['state'] . " - " . $payment_data['pincode'] . "</td>
                        </tr>
                       
                        <tr>
                            <td><strong>Total Amount</strong></td>
                            <td>Rs. 500</td>
                        </tr>
                    </table>

                    <br>
                    
                </div>
            ";

            // Include PHPMailer files
            require 'PHPMailer/PHPMailer/Exception.php';
            require 'PHPMailer/PHPMailer/PHPMailer.php';
            require 'PHPMailer/PHPMailer/SMTP.php';

            // Send the email using PHPMailer
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Update with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'himlandveda@gmail.com'; // Update with your SMTP email
                $mail->Password = 'azvheoaqcgrzrfha'; // Update with your SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('himlandveda@gmail.com', 'Himland Veda');
                $mail->addAddress($payment_data['email']); // Customer email
                $mail->addAddress('himlandveda@gmail.com'); // Admin email

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Your Invoice from Himland Veda';
                $mail->Body = $invoice_content;

                if ($mail->send()) {
                    ?>
                    <script>
                        alert('Email sent successfully.');
                    </script>
                    <?php
                    // Redirect to index.php
                    header('Location: index.php');
                    exit();
                } else {
                    ?>
                    <script>
                        alert('Email sending failed: <?php echo $mail->ErrorInfo; ?>');
                    </script>
                    <?php
                }

            } catch (Exception $e) {
                ?>
                <script>
                    alert("Email could not be sent. Mailer Error: <?php echo $mail->ErrorInfo; ?>");
                </script>
                <?php
            }
        }

    } else {
        ?>
        <script>
            alert('Error updating payment: <?php echo mysqli_error($con); ?>');
        </script>
        <?php 
    }
}

$con->close();
?>
