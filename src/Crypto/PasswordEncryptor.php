<?php
declare(strict_types=1);

namespace Vendor\SodiumCrypto\Crypto;

use Vendor\SodiumCrypto\Exception\EncryptionException;
use Vendor\SodiumCrypto\Exception\DecryptionException;

final class PasswordEncryptor
{
    private const SALT_BYTES = SODIUM_CRYPTO_PWHASH_SALTBYTES;
    private const KEY_BYTES  = SODIUM_CRYPTO_SECRETBOX_KEYBYTES;

    private Encryptor $encryptor;

    public function __construct(?Encryptor $encryptor = null)
    {
        $this->encryptor = $encryptor ?? new Encryptor();
    }

    public function encryptWithPassword(string $plaintext, string $password): string
    {
        $salt = random_bytes(self::SALT_BYTES);
        $key  = $this->deriveKey($password, $salt);
        $ciphertext = $this->encryptor->encrypt($plaintext, $key);
        return Utils::encodeBase64Url($salt . Utils::decodeBase64Url($ciphertext));
    }

    public function decryptWithPassword(string $ciphertext, string $password): string
    {
        $decoded = Utils::decodeBase64Url($ciphertext);
        $salt = mb_substr($decoded, 0, self::SALT_BYTES, '8bit');
        $encrypted = mb_substr($decoded, self::SALT_BYTES, null, '8bit');
        $key = $this->deriveKey($password, $salt);
        $wrappedCipher = Utils::encodeBase64Url($encrypted);
        return $this->encryptor->decrypt($wrappedCipher, $key);
    }

    private function deriveKey(string $password, string $salt): string
    {
        return sodium_crypto_pwhash(
            self::KEY_BYTES,
            $password,
            $salt,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE,
            SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13
        );
    }
}
