<?php
	
	namespace AliAbdalla\Whatsapp\Core;
	
	use Symfony\Component\HttpClient\HttpClient;
	
	class WhatsappCore
	{
		
		private $token;
		private $baseUrl;
		
		public function __construct()
		{
			$this->token = config('services.whatsapp.token');
			$this->token = config('services.whatsapp.base_url');
		}
		
		
		public function test()
		{
			return "test facades";
		}
		
		
		private function getClient()
		{
			return  HttpClient::create();;
		}
		
	}