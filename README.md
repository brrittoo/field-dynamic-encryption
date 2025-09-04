# Field Dynamic Encryption for Laravel

[![Latest Version](https://img.shields.io/packagist/v/brrittoo/field-dynamic-encryption.svg?style=flat-square)](https://packagist.org/packages/brrittoo/field-dynamic-encryption)
[![Total Downloads](https://img.shields.io/packagist/dt/brrittoo/field-dynamic-encryption.svg?style=flat-square)](https://packagist.org/packages/brrittoo/field-dynamic-encryption)
[![License](https://img.shields.io/packagist/l/brrittoo/field-dynamic-encryption.svg?style=flat-square)](https://packagist.org/packages/brrittoo/field-dynamic-encryption)

[![PHP Version Require](https://img.shields.io/packagist/php-v/brrittoo/field-dynamic-encryption.svg?style=flat-square)](https://packagist.org/packages/brrittoo/field-dynamic-encryption)
[![GitHub Issues](https://img.shields.io/github/issues/brrittoo/field-dynamic-encryption.svg?style=flat-square)](https://github.com/brrittoo/field-dynamic-encryption/issues)
[![GitHub Stars](https://img.shields.io/github/stars/brrittoo/field-dynamic-encryption.svg?style=flat-square)](https://github.com/brrittoo/field-dynamic-encryption/stargazers)


A Laravel package for dynamically encrypting and decrypting form field names to enhance security.

## Installation

1. Install via Composer:
```bash
composer require brrittoo/field-dynamic-encryption
```

2. Publish the configuration file:
```bash
php artisan vendor:publish --tag=field-encryption-config

```
Update Your Configuration: 

```php
<?php
	
	return [
		/*
		|--------------------------------------------------------------------------
		| Encryption Key
		|--------------------------------------------------------------------------
		|
		| This key is used by the field encryption service. It should be set
		| to a random, 16, 24, or 32 character string, otherwise these
		| encrypted fields will not be safe. Please do this before deploying!
		|
		*/
		'encryption_key' => env('FIELD_ENCRYPTION_KEY', bin2hex(random_bytes(8))),
		
		/*
		|--------------------------------------------------------------------------
		| Encryption IV
		|--------------------------------------------------------------------------
		|
		| Initialization vector for encryption. Should be 16 bytes long.
		|
		*/
		'encryption_iv' => env('FIELD_ENCRYPTION_IV', bin2hex(random_bytes(8))),
		
		/*
		|--------------------------------------------------------------------------
		| Excluded Fields
		|--------------------------------------------------------------------------
		|
		| Field names that should not be encrypted/decrypted
		|
		*/
		'exclude_fields' => [
			'_token',
			'_method',
			'_previous',
		],
		
		/*
		|--------------------------------------------------------------------------
		| Middleware Registration
		|--------------------------------------------------------------------------
		|
		| Control how the middleware is registered:
		| - 'auto': Use route_groups for automatic registration (default)
		| - 'global': Register middleware globally
		| - 'none': Don't register automatically
		| - array: Register to specific groups only, e.g., ['web', 'api']
		|
		*/
		'middleware_registration' => 'auto',
		
		/*
		|--------------------------------------------------------------------------
		| Route Groups
		|--------------------------------------------------------------------------
		|
		| Route groups where middleware should be automatically applied
		| when using 'auto' registration mode
		|
		*/
		'route_groups' => ['web'],

	];
```
3. Set encryption keys in your .env file:
```bash
FIELD_ENCRYPTION_KEY=your_32_character_encryption_key_here
FIELD_ENCRYPTION_IV=your_16_character_iv_here
```
Usage
In Blade Templates
Use the @field syntax in your form field names:

```bash
<input name="@name" type="text" class="form-control" placeholder="User Name">
<input name="@email" type="email" class="form-control" placeholder="Email Address">
```

In JavaScript
Use the encoded field names when accessing form fields:
```bash
const encodedName = "{{ FieldEncoder::encode('name') }}";
document.querySelector(`input[name="${encodedName}"]`).value = 'John Doe';

```
In Jquery
Use the encoded field names when accessing form fields:
```bash

$(`input[name="@name"]`).value = 'John Doe';

```

Manual Encoding/Decoding
```bash

use Brrittoo\FieldDynamicEncryption\Facades\FieldEncoder;

// Encode a field name
$encoded = FieldEncoder::encode('field_name');

// Decode an encoded field name
$decoded = FieldEncoder::decode($encoded);

// Encode/Decode arrays
$encodedArray = FieldEncoder::encodeArray(['name' => 'John', 'email' => 'john@example.com']);
$decodedArray = FieldEncoder::decodeArray($encodedArray);

```

### Configuration
After publishing the configuration file, you can modify config/field-encryption.php:
--- encryption_key: Your encryption key (32 characters recommended)
--- encryption_iv: Your initialization vector (16 characters)
--- exclude_fields: Field names that should not be encrypted
--- middleware_registration: How middleware is registered

### Middleware
The package includes two middleware classes:
EncodeFieldNamesInView: Encodes field names in responses
DecodeFieldName: Decodes field names in requests
These are automatically registered based on your configuration.

### Security
Always use strong, randomly generated encryption keys
Keep your encryption keys secure and never commit them to version control
Regularly rotate your encryption keys for enhanced security

### License
This package is open-source software licensed under the MIT license.

