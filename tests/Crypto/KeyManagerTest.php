<?php
declare(strict_types=1);

namespace Vendor\SodiumCrypto\Tests\Crypto;

use PHPUnit\Framework\TestCase;
use Vendor\SodiumCrypto\Crypto\KeyManager;

final class KeyManagerTest extends TestCase
{
    public function testGenerateSymmetricKey(): void
    {
        $manager = new KeyManager();
        $key = $manager->generateSymmetricKey();
        $this->assertSame(SODIUM_CRYPTO_SECRETBOX_KEYBYTES, strlen($key));
    }

    public function testAsymmetricKeyPair(): void
    {
        $manager = new KeyManager();
        $pair = $manager->generateAsymmetricKeyPair();
        $this->assertSame(SODIUM_CRYPTO_BOX_PUBLICKEYBYTES, strlen($pair['publicKey']));
        $this->assertSame(SODIUM_CRYPTO_BOX_SECRETKEYBYTES, strlen($pair['privateKey']));
    }
}
