<?php
define('ENCRYPTION_KEY', hex2bin(getenv('AES_KEY_HEX'))); // Simpan key di env
define('CIPHER_METHOD', 'aes-256-cbc');

// Fungsi enkripsi
function encryptData($data) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(CIPHER_METHOD));
    $encrypted = openssl_encrypt($data, CIPHER_METHOD, ENCRYPTION_KEY, 0, $iv);
    return base64_encode($iv . $encrypted);
}

// Fungsi dekripsi
function decryptData($encryptedData) {
    $decoded = base64_decode($encryptedData);
    $ivLength = openssl_cipher_iv_length(CIPHER_METHOD);
    if (strlen($decoded) < $ivLength) {
        return false;
    }
    $iv = substr($decoded, 0, $ivLength);
    $encrypted = substr($decoded, $ivLength);
    return openssl_decrypt($encrypted, CIPHER_METHOD, ENCRYPTION_KEY, 0, $iv);
}
?>
