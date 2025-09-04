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
				
				// Replace all @field placeholders
				$content = preg_replace_callback('/@([a-zA-Z0-9_]+)/', function ($matches) {
					$field = $matches[1];
					return FieldNameEncoder::encode($field);
				}, $content);
				
				// Replace name attributes that use @field
				$content = preg_replace_callback(
					'/name\s*=\s*(["\']?)@([a-zA-Z0-9_]+)\1/',
					function ($matches) {
						$quote = $matches[1] ?: '';
						$field = $matches[2];
						return 'name=' . $quote . FieldNameEncoder::encode($field) . $quote;
					},
					$content
				);
				
				$response->setContent($content);
			}
			
			return $response;
		}
	}