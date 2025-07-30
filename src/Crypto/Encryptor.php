<?php
declare(strict_types=1);

namespace SodiumCrypto\Crypto;

use SodiumCrypto\Exception\EncryptionException;
use SodiumCrypto\Exception\DecryptionException;

final class Encryptor
{
    private const NONCE_BYTES = SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;
    private const KEY_BYTES   = SODIUM_CRYPTO_SECRETBOX_KEYBYTES;

    public function encrypt(string $plaintext, string $key): string
    {
        $this->validateKey($key);
        try {
            $nonce = random_bytes(self::NONCE_BYTES);
            $cipher = sodium_crypto_secretbox($plaintext, $nonce, $key);
            return $this->encode($nonce . $cipher);
        } catch (\Throwable $e) {
            throw new EncryptionException('Encryption failed', 0, $e);
        }
    }

    public function decrypt(string $ciphertext, string $key): string
    {
        $this->validateKey($key);
        $decoded = $this->decode($ciphertext);

        $nonce = mb_substr($decoded, 0, self::NONCE_BYTES, '8bit');
        $encrypted = mb_substr($decoded, self::NONCE_BYTES, null, '8bit');

        $plaintext = sodium_crypto_secretbox_open($encrypted, $nonce, $key);
        if ($plaintext === false) {
            throw new DecryptionException('Invalid key or corrupted ciphertext.');
        }

        return $plaintext;
    }

    private function validateKey(string $key): void
    {
        if (strlen($key) !== self::KEY_BYTES) {
            throw new EncryptionException('Invalid key length. Expected ' . self::KEY_BYTES . ' bytes.');
        }
    }

    private function encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function decode(string $data): string
    {
        $padding = strlen($data) % 4;
        if ($padding > 0) {
            $data .= str_repeat('=', 4 - $padding);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
