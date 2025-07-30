<?php
declare(strict_types=1);

namespace SodiumCrypto\Crypto;

use SodiumCrypto\Exception\SigningException;
use SodiumCrypto\Exception\VerificationException;

final class Signer
{
    private const SIGN_PUBLIC_KEY_BYTES = SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES;
    private const SIGN_PRIVATE_KEY_BYTES = SODIUM_CRYPTO_SIGN_SECRETKEYBYTES;

    public function sign(string $message, string $privateKey): string
    {
        $this->validatePrivateKey($privateKey);
        try {
            return sodium_crypto_sign_detached($message, $privateKey);
        } catch (\Throwable $e) {
            throw new SigningException('Signing failed.', 0, $e);
        }
    }

    public function verify(string $message, string $signature, string $publicKey): bool
    {
        $this->validatePublicKey($publicKey);
        try {
            return sodium_crypto_sign_verify_detached($signature, $message, $publicKey);
        } catch (\Throwable $e) {
            throw new VerificationException('Verification failed.', 0, $e);
        }
    }

    private function validatePrivateKey(string $key): void
    {
        if (strlen($key) !== self::SIGN_PRIVATE_KEY_BYTES) {
            throw new SigningException('Invalid private key length for signing.');
        }
    }

    private function validatePublicKey(string $key): void
    {
        if (strlen($key) !== self::SIGN_PUBLIC_KEY_BYTES) {
            throw new VerificationException('Invalid public key length for verification.');
        }
    }
}
