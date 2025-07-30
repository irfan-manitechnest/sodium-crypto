<?php
declare(strict_types=1);

namespace SodiumCrypto\Crypto;

final class Utils
{
    public static function encodeBase64Url(string $binary): string
    {
        return rtrim(strtr(base64_encode($binary), '+/', '-_'), '=');
    }

    public static function decodeBase64Url(string $encoded): string
    {
        $padding = strlen($encoded) % 4;
        if ($padding > 0) {
            $encoded .= str_repeat('=', 4 - $padding);
        }
        return base64_decode(strtr($encoded, '-_', '+/'));
    }

    public static function randomBytes(int $length): string
    {
        return random_bytes($length);
    }

    public static function wipeMemory(string &$value): void
    {
        sodium_memzero($value);
    }
}
