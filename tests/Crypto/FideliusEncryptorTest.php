<?php

namespace SodiumCrypto\Tests\Crypto;

use PHPUnit\Framework\TestCase;
use SodiumCrypto\Crypto\FideliusEncryptor;
use RuntimeException;

class FideliusEncryptorTest extends TestCase
{
	private string $senderPrivate;
	private string $senderPublic;
	private string $recipientPrivate;
	private string $recipientPublic;

	protected function setUp(): void
	{
		// Generate sender keys
		$senderKeys = FideliusEncryptor::generateKeyPair();
		$this->senderPrivate = $senderKeys['privateKey'];
		$this->senderPublic = $senderKeys['publicKey'];

		// Generate recipient keys
		$recipientKeys = FideliusEncryptor::generateKeyPair();
		$this->recipientPrivate = $recipientKeys['privateKey'];
		$this->recipientPublic = $recipientKeys['publicKey'];
	}

	public function testKeyPairGeneration(): void
	{
		$keys = FideliusEncryptor::generateKeyPair();

		$this->assertArrayHasKey('publicKey', $keys);
		$this->assertArrayHasKey('privateKey', $keys);
		$this->assertNotEmpty($keys['publicKey']);
		$this->assertNotEmpty($keys['privateKey']);

		$this->assertNotFalse(base64_decode($keys['publicKey'], true));
		$this->assertNotFalse(base64_decode($keys['privateKey'], true));
	}

	public function testKeyPairWithNonceGeneration(): void
	{
		$result = FideliusEncryptor::generateKeyPairWithNonce();

		$this->assertArrayHasKey('publicKey', $result);
		$this->assertArrayHasKey('privateKey', $result);
		$this->assertArrayHasKey('nonce', $result);

		$this->assertNotEmpty($result['publicKey']);
		$this->assertNotEmpty($result['privateKey']);
		$this->assertNotEmpty($result['nonce']);

		$decodedNonce = base64_decode($result['nonce'], true);
		$this->assertNotFalse($decodedNonce);
		$this->assertSame(SODIUM_CRYPTO_BOX_NONCEBYTES, strlen($decodedNonce));
	}

	public function testEncryptAndDecrypt(): void
	{
		$plaintext = "Sensitive message: Fidelius replacement test!";

		$encrypted = FideliusEncryptor::encrypt(
			$plaintext,
			$this->senderPrivate,
			$this->senderPublic,       // ✅ Added sender public key
			$this->recipientPublic
		);

		$this->assertArrayHasKey('ciphertext', $encrypted);
		$this->assertArrayHasKey('nonce', $encrypted);
		$this->assertArrayHasKey('keyToShare', $encrypted);

		$this->assertEquals($this->senderPublic, $encrypted['keyToShare']);

		$decrypted = FideliusEncryptor::decrypt(
			$encrypted['ciphertext'],
			$encrypted['nonce'],
			$this->recipientPrivate,
			$this->senderPublic
		);

		$this->assertEquals($plaintext, $decrypted);
	}

	public function testTamperedCiphertextFails(): void
	{
		$plaintext = "This will be tampered.";
		$encrypted = FideliusEncryptor::encrypt(
			$plaintext,
			$this->senderPrivate,
			$this->senderPublic,       // ✅ Fixed param count
			$this->recipientPublic
		);

		// Tamper with ciphertext
		$tampered = substr_replace($encrypted['ciphertext'], 'A', 5, 1);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Decryption failed');

		FideliusEncryptor::decrypt(
			$tampered,
			$encrypted['nonce'],
			$this->recipientPrivate,
			$this->senderPublic
		);
	}

	public function testInvalidBase64KeysFail(): void
	{
		$plaintext = "Invalid key test.";

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Invalid base64 key');

		FideliusEncryptor::encrypt(
			$plaintext,
			'INVALID_KEY',             // Sender private (invalid)
			$this->senderPublic,       // Sender public (valid)
			$this->recipientPublic     // Recipient public (valid)
		);
	}
}
