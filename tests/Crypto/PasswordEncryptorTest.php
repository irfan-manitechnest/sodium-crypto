<?php
declare(strict_types=1);

namespace Vendor\SodiumCrypto\Tests\Crypto;

use PHPUnit\Framework\TestCase;
use Vendor\SodiumCrypto\Crypto\PasswordEncryptor;

final class PasswordEncryptorTest extends TestCase
{
    public function testPasswordEncryptAndDecrypt(): void
    {
        $passwordEnc = new PasswordEncryptor();
        $password = 'SuperSecureP@ss';
        $plaintext = 'Sensitive data';

        $encrypted = $passwordEnc->encryptWithPassword($plaintext, $password);
        $decrypted = $passwordEnc->decryptWithPassword($encrypted, $password);

        $this->assertSame($plaintext, $decrypted);
    }
}
