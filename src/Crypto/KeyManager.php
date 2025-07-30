<?php
declare(strict_types=1);

namespace Vendor\SodiumCrypto\Crypto;

use Vendor\SodiumCrypto\Exception\KeyException;

final class KeyManager
{
    private const SYMMETRIC_KEY_BYTES = SODIUM_CRYPTO_SECRETBOX_KEYBYTES;
    private const ASYMMETRIC_PUBLIC_KEY_BYTES = SODIUM_CRYPTO_BOX_PUBLICKEYBYTES;
    private const ASYMMETRIC_PRIVATE_KEY_BYTES = SODIUM_CRYPTO_BOX_SECRETKEYBYTES;
    private const SIGN_PUBLIC_KEY_BYTES = SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES;
    private const SIGN_PRIVATE_KEY_BYTES = SODIUM_CRYPTO_SIGN_SECRETKEYBYTES;
    private const PWHASH_SALT_BYTES = SODIUM_CRYPTO_PWHASH_SALTBYTES;

    public function generateSymmetricKey(): string
    {
        return random_bytes(self::SYMMETRIC_KEY_BYTES);
    }

    public function generateAsymmetricKeyPair(): array
    {
        $keypair = sodium_crypto_box_keypair();
        return [
            'publicKey'  => sodium_crypto_box_publickey($keypair),
            'privateKey' => sodium_crypto_box_secretkey($keypair),
        ];
    }

    public function generateSigningKeyPair(): array
    {
        $signKeypair = sodium_crypto_sign_keypair();
        return [
            'publicKey'  => sodium_crypto_sign_publickey($signKeypair),
            'privateKey' => sodium_crypto_sign_secretkey($signKeypair),
        ];
    }

    public function deriveKey(string $password, ?string $salt = null): array
    {
        $salt = $salt ?? random_bytes(self::PWHASH_SALT_BYTES);
        $key = sodium_crypto_pwhash(
            self::SYMMETRIC_KEY_BYTES,
            $password,
            $salt,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE,
            SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13
        );
        return ['key' => $key, 'salt' => $salt];
    }

    public function rotateKey(string $oldKey): string
    {
        if (strlen($oldKey) !== self::SYMMETRIC_KEY_BYTES) {
            throw new KeyException('Invalid old key length for rotation.');
        }
        return random_bytes(self::SYMMETRIC_KEY_BYTES);
    }
}
