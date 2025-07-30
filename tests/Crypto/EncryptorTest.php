<?php
declare(strict_types=1);

namespace SodiumCrypto\Tests\Crypto;

use PHPUnit\Framework\TestCase;
use SodiumCrypto\Crypto\Encryptor;
use SodiumCrypto\Crypto\KeyManager;

final class EncryptorTest extends TestCase
{
    public function testEncryptAndDecrypt(): void
    {
        $keyManager = new KeyManager();
        $key = $keyManager->generateSymmetricKey();
        $encryptor = new Encryptor();

        $plaintext = 'Hello, Sodium!';
        $cipher = $encryptor->encrypt($plaintext, $key);
        $decrypted = $encryptor->decrypt($cipher, $key);

        $this->assertSame($plaintext, $decrypted);
    }
}
