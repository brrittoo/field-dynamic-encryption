<?php
	
	namespace Brrittoo\FieldDynamicEncryption;
	
	class FieldNameEncoder
	{
		protected static $cipher = 'AES-128-CBC';
		
		/**
		 * Get excluded field names
		 */
		public static function getExclude(): array
		{
			return config('field-encryption.exclude_fields', []);
		}
		
		/**
		 * Get encryption key
		 */
		public static function getKey(): string
		{
			return config('field-encryption.encryption_key');
		}
		
		/**
		 * Get initialization vector
		 */
		public static function getIv(): string
		{
			return config('field-encryption.encryption_iv');
		}
		
		/**
		 * Encode a field name
		 */
		public static function encode(string $name): string
		{
			if (in_array($name, self::getExclude())) {
				return $name;
			}
			
			$encrypted = openssl_encrypt(
				$name,
				self::$cipher,
				self::getKey(),
				OPENSSL_RAW_DATA,
				self::getIv()
			);
			
			return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
		}
		
		/**
		 * Decode an encoded field name
		 */
		public static function decode(string $encoded): ?string
		{
			if (in_array($encoded, self::getExclude())) {
				return $encoded;
			}
			
			$decoded = base64_decode(strtr($encoded, '-_', '+/'));
			$decrypted = openssl_decrypt(
				$decoded,
				self::$cipher,
				self::getKey(),
				OPENSSL_RAW_DATA,
				self::getIv()
			);
			
			return $decrypted ?: null;
		}
		
		/**
		 * Encode multiple field names in an array
		 */
		public static function encodeArray(array $fields): array
		{
			$result = [];
			foreach ($fields as $key => $value) {
				$result[self::encode($key)] = $value;
			}
			return $result;
		}
		
		/**
		 * Decode multiple field names in an array
		 */
		public static function decodeArray(array $fields): array
		{
			$result = [];
			foreach ($fields as $key => $value) {
				$decodedKey = self::decode($key);
				$result[$decodedKey ?: $key] = $value;
			}
			return $result;
		}
	}