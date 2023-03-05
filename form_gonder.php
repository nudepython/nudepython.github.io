<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kullanıcının adı, e-posta adresi ve mesajı alınır
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // E-posta gönderimi için gerekli bilgiler
    $to = 'befeconsultinggroup@gmail.com';
    $subject = 'Yeni bir mesajınız var';
    $headers = "From: $email\r\n" .
               "Reply-To: $email\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // E-posta içeriği
    $body = "Adı: $name\n" .
            "E-posta: $email\n" .
            "Mesajı:\n$message";

    // E-posta gönderimi
    if (mail($to, $subject, $body, $headers)) {
        echo 'Mesajınız başarıyla gönderildi.';
    } else {
        echo 'Mesajınız gönderilemedi.';
    }
}
?>
