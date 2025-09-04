<?php
	
	namespace Brrittoo\FieldDynamicEncryption;
	
	use Illuminate\Support\ServiceProvider as BaseServiceProvider;
	use Brrittoo\FieldDynamicEncryption\Middleware\DecodeFieldName;
	use Brrittoo\FieldDynamicEncryption\Middleware\EncodeFieldNamesInView;
	
	class ServiceProvider extends BaseServiceProvider
	{
		/**
		 * Register services.
		 */
		public function register(): void
		{
			$this->app->singleton('field-encoder', function ($app) {
				return new FieldNameEncoder();
			});
			
			$this->mergeConfigFrom(
				__DIR__.'/config/field-encryption.php', 'field-encryption'
			);
		}
		
		/**
		 * Bootstrap services.
		 */
		public function boot(): void
		{
			$this->publishes([
				__DIR__.'/config/field-encryption.php' => config_path('field-encryption.php'),
			], 'field-encryption-config');
			
			$this->app->booted(function () {
				$this->registerMiddleware();
			});
			
		}
		
		/**
		 * Register middleware based on configuration
		 */
		protected function registerMiddleware(): void
		{
			$registration = config('field-encryption.middleware_registration', 'auto');
			
			if ($registration === 'global') {
				$this->app['router']->pushMiddlewareToGroup('web', DecodeFieldName::class);
				$this->app['router']->pushMiddlewareToGroup('web', EncodeFieldNamesInView::class);
			} elseif ($registration === 'auto') {
				$groups = config('field-encryption.route_groups', ['web']);
				
				foreach ($groups as $group) {
					$this->app['router']->pushMiddlewareToGroup($group, DecodeFieldName::class);
					$this->app['router']->pushMiddlewareToGroup($group, EncodeFieldNamesInView::class);
				}
			} elseif (is_array($registration)) {
				foreach ($registration as $group) {
					$this->app['router']->pushMiddlewareToGroup($group, DecodeFieldName::class);
					$this->app['router']->pushMiddlewareToGroup($group, EncodeFieldNamesInView::class);
				}
			}
		}
	}