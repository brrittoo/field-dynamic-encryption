<?php
	
	namespace Brrittoo\FieldDynamicEncryption\Middleware;
	
	use Brrittoo\FieldDynamicEncryption\FieldNameEncoder;
	use Closure;
	use Illuminate\Http\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class EncodeFieldNamesInView
	{
		/**
		 * Handle an incoming request.
		 */
		public function handle(Request $request, Closure $next): Response
		{
			$response = $next($request);
			
			if (method_exists($response, 'getContent')) {
				$content = $response->getContent();
				
				// Only replace @field used in name or value attributes
				$content = preg_replace_callback(
					'/(name|value)\s*=\s*(["\']?)@([a-zA-Z0-9_]+)\2/',
					function ($matches) {
						$attribute = $matches[1];   // name or value
						$quote = $matches[2] ?: '';
						$field = $matches[3];       // logical field name
						return $attribute . '=' . $quote . FieldNameEncoder::encode($field) . $quote;
					},
					$content
				);
				
				$response->setContent($content);
			}
			
			return $response;
			
		}
	}