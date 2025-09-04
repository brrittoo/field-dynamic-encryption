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