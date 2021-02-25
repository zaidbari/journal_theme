<?php

namespace Core;

use App\Lib\Config;
use App\Lib\HTTPRequester;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

/**
 * View
 *
 * PHP version 7.0
 */
class View
{

	/**
	 * Render a view template using Twig
	 *
	 * @param string $template The template file
	 * @param array  $args     Associative array of data to display in the view (optional)
	 *
	 * @return void
	 */
	public static function render( string $template, $args = [] )
	{
		static $twig = null;

		if($twig == null) {

			$loader = new FilesystemLoader(['resources/views/pages', 'resources/views/partials']);
			$twig = new Environment($loader, [
				'cache' => Config::CACHE,
				'auto_reload' => Config::RELOAD
			]);

			$menu = HTTPRequester::HTTPGet(Config::env('API_URL') . 'pages');
			$twig->addGlobal('menu',(array)  $menu['body']->data);

			// Global Variables available everywhere in app
			$twig->addGlobal('APP_NAME', Config::env('APP_NAME'));
			$twig->addGlobal('CURRENT_ROUTE', Router::currentRoute());

			$twig->addFilter( new TwigFilter('cast_to_array', function ($stdClassObject) {
				return (array) $stdClassObject;
			}));

		}

		try {
			echo $twig->render($template . '.twig', $args);
		} catch (LoaderError | RuntimeError | SyntaxError $e) {
			echo '<pre>' . $e . '</pre>';
		}
	}



}
