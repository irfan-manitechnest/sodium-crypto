<?php
declare(strict_types=1);

namespace SodiumCrypto\Tests\Crypto;

use PHPUnit\Framework\TestCase;
use SodiumCrypto\Crypto\Utils;

final class UtilsTest extends TestCase
{
    public function testBase64UrlEncodingAndDecoding(): void
    {
        $data = 'binary-data';
        $encoded = Utils::encodeBase64Url($data);
        $decoded = Utils::decodeBase64Url($encoded);

        $this->assertSame($data, $decoded);
    }
}
