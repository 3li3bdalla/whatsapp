<?php
	
	namespace AliAbdalla\Whatsapp\Facade;
	
	use Illuminate\Support\Facades\Facade;
	
	/**
//	 * @method static \Illuminate\Contracts\Cache\Repository  store(string|null $name = null)
	 * @method static string test()
//	 * @method static bool missing(string $key)
//	 * @method static mixed get(string $key, mixed $default = null)
//	 * @method static mixed pull(string $key, mixed $default = null)
//	 * @method static bool put(string $key, $value, \DateTimeInterface|\DateInterval|int $ttl)

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