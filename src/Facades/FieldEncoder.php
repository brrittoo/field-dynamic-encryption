<?php
	
	namespace Brrittoo\FieldDynamicEncryption\Facades;
	
	use Illuminate\Support\Facades\Facade;
	
	class FieldEncoder extends Facade
	{
		protected static function getFacadeAccessor()
		{
			return 'field-encoder';
		}
	}