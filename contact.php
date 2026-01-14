<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("/r", "/n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);
    if (empty($name) OR empty($subject) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "Please complete the form and try again."
        ]);
        exit;
    }
    $recipient = "info@domotix.co.in";
    $email_subject = "New Contact Form Submission: $subject";
    $email_content = "Name : $name\n";
    $email_content .= "Email : $email\n\n";
    $email_content .= "Subject : $subject\n\n";
    $email_content .= "Message : \n$message\n";
    $email_headers = "From: $name <$email>";
    if (mail($recipient, $email_content, $email_subject, $email_headers)) {
        http_response_code(200);
        echo json_encode(
            [
                "success" => true,
                "message" => "Thank you! Your message has been sent. We'll get back to you soon."
            ]
        );
    } else {
        http_response_code(500);
        echo json_encode(
            [
                "success" => false,
                "message" => "Oops! Something went wrong. Please try again or contact us directly."


            ]
        );
    }
} else {
    // Not a POST request
    http_response_code(403);
    echo json_encode([
        "success" => false,
        "message" => "There was a problem with your submission."
    ]);
}

?>