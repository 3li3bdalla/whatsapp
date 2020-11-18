<?php
	
	namespace AliAbdalla\Whatsapp;
	
	use AliAbdalla\Whatsapp\Core\WhatsappCore;
	
	
	class ServiceProvider extends \Illuminate\Support\ServiceProvider
	{
		/**
		 * Register services.
		 *
		 * @return void
		 */
		public function register()
		{
			
			$this->app->singleton(
				"whatsapp", function() {
				return new WhatsappCore();
			}
			);
			
			$this->app->alias(Whatsapp::class, 'Whatsapp');
			
			
		}
		
		/**
		 * Bootstrap services.
		 *
		 * @return void
		 */
		public function boot()
		{
		
		}
	}
