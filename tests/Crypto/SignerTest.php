<?php
declare(strict_types=1);

namespace Vendor\SodiumCrypto\Tests\Crypto;

use PHPUnit\Framework\TestCase;
use Vendor\SodiumCrypto\Crypto\Signer;
use Vendor\SodiumCrypto\Crypto\KeyManager;

final class SignerTest extends TestCase
{
    public function testSignAndVerify(): void
    {
        $manager = new KeyManager();
        $signer = new Signer();
        $keys = $manager->generateSigningKeyPair();

        $message = 'Verify me!';
        $signature = $signer->sign($message, $keys['privateKey']);
        $result = $signer->verify($message, $signature, $keys['publicKey']);

        $this->assertTrue($result);
    }
}
