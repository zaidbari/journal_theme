<?php
namespace App\Controllers;

use App\Lib\Config;
use App\Lib\HTTPRequester;
use Core\View;

class Issues
{
	public function index( $request )
	{
		$articles_data = HTTPRequester::HTTPGet(Config::env('API_URL') . 'archive/'. $request->param('id').'/articles')['body']->data;
		$articles = [];
		foreach ($articles_data as $key => $value) {
			$articles[] = ['type' => $key, 'data' => $value];
		}
		$title = 'Year ' . $request->param('year') .', Volume ' . $request->param('volume') . ' - Issue '. $request->param('issue');

		View::render('articles/list', [
			'page_title' => $title,
			'articles' => $articles
		]);
	}
}