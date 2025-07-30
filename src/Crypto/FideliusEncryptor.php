<?php

namespace SodiumCrypto\Crypto;

use RuntimeException;

class FideliusEncryptor
{
	/**
	 * Generate an asymmetric key pair (public/private).
	 *
	 * @return array{publicKey: string, privateKey: string}
	 */
	public static function generateKeyPair(): array
	{
		$keypair = sodium_crypto_box_keypair();
		$publicKey = sodium_crypto_box_publickey($keypair);
		$privateKey = sodium_crypto_box_secretkey($keypair);

		return [
			'publicKey' => base64_encode($publicKey),
			'privateKey' => base64_encode($privateKey),
		];
	}

	/**
	 * Generate a keypair and nonce (to match Fidelius API behavior).
	 *
	 * @return array{publicKey: string, privateKey: string, nonce: string}
	 */
	public static function generateKeyPairWithNonce(): array
	{
		$keys = self::generateKeyPair();
		$nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);

		return [
			'publicKey' => $keys['publicKey'],
			'privateKey' => $keys['privateKey'],
			'nonce' => base64_encode($nonce),
		];
	}

	/**
	 * Encrypt plaintext using sender's private key and recipient's public key.
	 *
	 * @param string $plaintext
	 * @param string $senderPrivateKey (base64)
	 * @param string $senderPublicKey (base64)
	 * @param string $recipientPublicKey (base64)
	 * @return array{ciphertext: string, nonce: string, keyToShare: string}
	 */
	public static function encrypt(
		string $plaintext,
		string $senderPrivateKey,
		string $senderPublicKey,
		string $recipientPublicKey
	): array {
		$nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);

		$senderPrivRaw = base64_decode($senderPrivateKey, true);
		$senderPubRaw = base64_decode($senderPublicKey, true);
		$recipientPubRaw = base64_decode($recipientPublicKey, true);

		if ($senderPrivRaw === false || $senderPubRaw === false || $recipientPubRaw === false) {
			throw new RuntimeException('Invalid base64 key provided for encryption.');
		}

		$encryptionKeypair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
			$senderPrivRaw,
			$recipientPubRaw
		);

		$ciphertext = sodium_crypto_box($plaintext, $nonce, $encryptionKeypair);

		return [
			'ciphertext' => base64_encode($ciphertext),
			'nonce' => base64_encode($nonce),
			'keyToShare' => $senderPublicKey,
		];
	}

	/**
	 * Decrypt ciphertext using recipient's private key and sender's public key.
	 *
	 * @param string $ciphertext (base64)
	 * @param string $nonce (base64)
	 * @param string $recipientPrivateKey (base64)
	 * @param string $senderPublicKey (base64)
	 * @return string plaintext
	 */
	public static function decrypt(string $ciphertext, string $nonce, string $recipientPrivateKey, string $senderPublicKey): string
	{
		$cipherRaw = base64_decode($ciphertext, true);
		$nonceRaw = base64_decode($nonce, true);
		$recPrivRaw = base64_decode($recipientPrivateKey, true);
		$senderPubRaw = base64_decode($senderPublicKey, true);

		if ($cipherRaw === false || $nonceRaw === false || $recPrivRaw === false || $senderPubRaw === false) {
			throw new RuntimeException('Invalid base64 data for decryption.');
		}

		$decryptionKeypair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
			$recPrivRaw,
			$senderPubRaw
		);

		$plaintext = sodium_crypto_box_open($cipherRaw, $nonceRaw, $decryptionKeypair);

		if ($plaintext === false) {
			throw new RuntimeException('Decryption failed: Invalid message or keys.');
		}

		return $plaintext;
	}
}
