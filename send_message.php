<?php
header('Content-Type: application/json');

// Menampilkan semua error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $subject = htmlspecialchars(trim($_POST["subject"] ?? ''));
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    // Validasi input kosong
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }

    $to = "caturwirasyahputra@gmail.com"; // Ganti dengan email tujuan
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "Name: $name\nEmail: $email\nSubject: $subject\n\n$message";

    // Cek apakah fungsi mail() diaktifkan
    if (function_exists('mail')) {
        if (mail($to, $subject, $body, $headers)) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully!']);
        } else {
            // Debugging tambahan saat mail() gagal
            error_log("Mail failed. Check SMTP configuration.");
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to send message.']);
        }
    } else {
        error_log("Mail function not available.");
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Mail function not available on the server.']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
