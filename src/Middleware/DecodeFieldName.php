<?php
	
	namespace Brrittoo\FieldDynamicEncryption\Middleware;
	
	use Brrittoo\FieldDynamicEncryption\FieldNameEncoder;
	use Closure;
	use Illuminate\Http\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class DecodeFieldName
	{
		/**
		 * Handle an incoming request.
		 */
		public function handle(Request $request, Closure $next): Response
		{
			$decoded = [];
			foreach ($request->all() as $key => $value) {
				$decodedKey = FieldNameEncoder::decode($key);
				$decoded[$decodedKey ?: $key] = $value;
			}
			$request->replace($decoded);
			
			return $next($request);
		}
	}