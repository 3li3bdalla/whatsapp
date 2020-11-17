<?php
	
	namespace AliAbdalla\Whatsapp\ServiceProvider;
	
	use AliAbdalla\Whatsapp\Core\WhatsappCore;
	use Illuminate\Support\ServiceProvider;
	
	class WhatsappServiceProvider extends ServiceProvider
	{
		/**
		 * Register services.
		 *
		 * @return void
		 */
		public function register()
		{
			//
			$this->app->bind(
				'whatsapp', function() {
				return new WhatsappCore();
			}
			);
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
