<?php

// ==============================
// ✅ HELPER FUNCTIONS
// ==============================

// 🔒 Disposable / blocked domains
function isDisposableEmail($email) {

    $disposable_domains = [
        "mailinator.com", "guerrillamail.com", "10minutemail.com", "tempmail.com",
        "tempmail.net", "tempmail.org", "tempmailaddress.com", "yopmail.com",
        "throwawaymail.com", "emailtemp.com", "fakeinbox.com", "getnada.com",
        "sharklasers.com", "grr.la", "spamgourmet.com", "maildrop.cc",
        "bouncemail.com", "discardmail.com", "fakemailgenerator.com",
        "temp-mail.org", "mytemp.email", "disposableemailaddresses.com",
        "emailondeck.com", "burnermail.io", "trashmail.com", "moakt.com",
        "spambog.com", "mailcatch.com", "eyepaste.com", "spam4.me",

        // 🚫 Free / unwanted (optional - remove if not needed)
        "hotmail.com", "outlook.com", "aol.com", "yandex.com", "proton.me",

        // ISP domains
        "roadrunner.com", "rr.com", "twc.com"
    ];

    $domain = strtolower(substr(strrchr($email, "@"), 1));

    return in_array($domain, $disposable_domains);
}

// 🚫 Role-based emails (admin@, info@ etc.)
function isRoleEmail($email) {
    $rolePrefixes = ["admin", "info", "support", "sales", "contact"];
    $username = strtolower(explode("@", $email)[0]);

    return in_array($username, $rolePrefixes);
}

// ✅ Main validation
function validateEmail($email) {

    // 1. Format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Disposable / blocked
    if (isDisposableEmail($email)) {
        return "Temporary or unsupported email addresses are not allowed.";
    }

    // 3. Role email
    if (isRoleEmail($email)) {
        return "Please use a personal business email.";
    }

    // 4. MX record check (real domain)
    $domain = substr(strrchr($email, "@"), 1);
    if (!checkdnsrr($domain, "MX")) {
        return "Email domain does not exist.";
    }

    return true;
}

// ==============================
// ✅ MAIN FORM HANDLER
// ==============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data safely
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $business = htmlspecialchars(trim($_POST['business_type'] ?? ''));

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($business)) {
        echo "Please fill all fields.";
        exit;
    }

    // Email validation
    $emailCheck = validateEmail($email);
    if ($emailCheck !== true) {
        echo $emailCheck;
        exit;
    }

    // Email details
    $to = "sriethiraj@getnos.io"; // ✅ Change if needed
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
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send email
    if (mail($to, $subject, $message, $headers)) {

        // ✅ Redirect after success
        header("Location: https://in.thedollarbusiness.com/book-a-demo/R2V0Tm9z");
        exit;

    } else {
        echo "Something went wrong. Please try again.";
    }

} else {
    echo "Invalid request.";
}

?>