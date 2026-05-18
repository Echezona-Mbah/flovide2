<?php

$dir = __DIR__ . '/storage/app/keys';
if (!is_dir($dir)) mkdir($dir, 0777, true);

$config = [
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'private_key_bits' => 2048,
];

$res = openssl_pkey_new($config);

if (!$res) {
    echo "Failed to generate key pair\n";
    while ($e = openssl_error_string()) {
        echo $e . PHP_EOL;
    }
    exit(1);
}

openssl_pkey_export($res, $privateKey);
$details = openssl_pkey_get_details($res);
$publicKey = $details['key'] ?? null;

file_put_contents($dir . '/interac_private_key.pem', $privateKey);
file_put_contents($dir . '/interac_public_key.pem', $publicKey);

echo "Keys generated successfully\n";
