<?php
/* =========================================================
   SINGLE FILE CONTACT SYSTEM (contact.php)
   - Includes HTML form + PHP mail sender
   - Validation + clean inputs + success/error UI
   ========================================================= */

// ======== EDIT THIS ========
$toEmail  = "uthman.ishaq.abdullah@email.com";     // <-- put YOUR email here (where messages should arrive)
$siteName = "MARIINA Nigeria CN LTD";

// ======== Helpers ========
function clean($v) {
  return htmlspecialchars(strip_tags(trim((string)$v)), ENT_QUOTES, 'UTF-8');
}

$errors = [];
$successMsg = "";

// ======== If form submitted ========
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name    = clean($_POST["fullName"] ?? "");
  $email   = clean($_POST["email"] ?? "");
  $message = clean($_POST["message"] ?? "");

  // Validate
  if (strlen($name) < 2) $errors[] = "Full Name is too short.";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email address.";
  if (strlen($message) < 10) $errors[] = "Message must be at least 10 characters.";

  // Send email if no errors
  if (empty($errors)) {
    $subject = "New Contact Message - $siteName";

    $body = "New message from your website:\n\n"
          . "Full Name: $name\n"
          . "Email: $email\n\n"
          . "Message:\n$message\n\n"
          . "----\nSent from $siteName contact form.";

    // IMPORTANT: Some hosts require a real domain email. If no-reply@yourdomain.com fails,
    // change it to your real business email on your domain.
    $fromEmail = "no-reply@yourdomain.com";

    $headers  = "From: $siteName <$fromEmail>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $sent = @mail($toEmail, $subject, $body, $headers);

    if ($sent) {
      $successMsg = "✅ Message sent successfully. Thank you, $name!";
      // Clear values after success
      $name = $email = $message = "";
    } else {
      $errors[] = "❌ Server couldn't send the message. (Mail function failed on hosting.)";
    }
  }
}

// Keep form values if errors happened
$nameVal    = isset($name) ? $name : "";
$emailVal   = isset($email) ? $email : "";
$messageVal = isset($message) ? $message : "";
?>
