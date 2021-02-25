<?php namespace App\Controllers;

use App\Lib\Config;
use App\Lib\HTTPRequester;
use \Core\View;
use \Core\Controller;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends Controller
{

	/**
	 * Show the index page
	 *
	 *
	 * @return void
	 */
	public function index()
	{
		$journal_data = HTTPRequester::HTTPGet(Config::env('API_URL') . 'home')['body']->data;
		$articles_data = HTTPRequester::HTTPGet(Config::env('API_URL') . 'articles')['body']->data;


		if(!empty($journal_data->featured_articles)) $featured_article = $journal_data->featured_articles[0];
		else $featured_article = false;
		$articles = [];

		foreach ($articles_data as $key => $value) {
			$articles[] = ['type' => $key, 'data' => $value];
		}

		View::render('home/index',
			[
				'page_title' => 'Home',
				'journal' => $journal_data->journal,
				'articles' => $articles,
				'featured_article' => $featured_article
			]);

	}
}
