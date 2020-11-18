<?php
	
	namespace AliAbdalla\Whatsapp;
	
	use Illuminate\Support\Facades\Facade;
	use Symfony\Component\HttpClient\HttpClient;
	use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
	
	/**
	 * @method static string test()
	 * @method static void sendMessage($message, string|array $phoneNumber)
	 * @method static TransportExceptionInterface getError()
	 * @method static HttpClient getResponse()
	 */
	class Whatsapp extends Facade
	{
		/**
		 * Get the registered name of the component.
		 *
		 * @return string
		 */
		protected static function getFacadeAccessor()
		{
			return 'whatsapp';
		}
	}