<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data safely
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $business = htmlspecialchars(trim($_POST['business_type']));

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($business)) {
        echo "Please fill all fields.";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Email details
    $to = "sriethiraj@getnos.io"; // 🔥 CHANGE THIS
    $subject = "Dollar Business Meta - Discover How Exporters Find Active Importers";

    $message = "
    New Form Submission:

    Name: $name
    Email: $email
    Phone: $phone
    Business Type: $business
    ";

    $headers = "From: hello@getnos.io\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send email
    if (mail($to, $subject, $message, $headers)) {

        // Redirect after success
        header("Location: https://in.thedollarbusiness.com/book-a-demo/R2V0Tm9z");
        exit;

    } else {
        echo "Something went wrong. Please try again.";
    }

} else {
    echo "Invalid request.";
}

?>