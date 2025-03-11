<?php
// Alıcı e-posta adresinizi buraya yazın
$receiving_email_address = 'tunahanakderem@gmail.com';

// CORS (Farklı domainlerden gelen isteklere izin verir)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

// Yalnızca POST isteğine izin ver
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Yalnızca POST isteği kabul edilir."]);
    exit;
}

// POST verilerini al ve güvenli hale getir
$name    = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Zorunlu alanları kontrol et
if (!$name || !$email || !$subject || !$message) {
    http_response_code(400);
    echo json_encode(["error" => "Lütfen tüm alanları doldurun."]);
    exit;
}

// E-posta başlıkları
$headers  = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// E-posta içeriği
$email_body = "İsim: $name\n";
$email_body .= "E-posta: $email\n";
$email_body .= "Konu: $subject\n\n";
$email_body .= "Mesaj:\n$message\n";

// E-posta gönderme işlemi
if (mail($receiving_email_address, $subject, $email_body, $headers)) {
    echo json_encode(["success" => "E-posta başarıyla gönderildi!"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "E-posta gönderilirken bir hata oluştu."]);
}
?>
