<?php


namespace App\Controllers;


use App\Lib\Config;
use App\Lib\HTTPRequester;
use Core\View;

class Articles
{
	public function show($request)
	{
		$id = $request->param('id');
		$a = HTTPRequester::HTTPGet(Config::env('API_URL') . 'articles/' .$id. '/show')['body'];

		$journal = [];
		$article = [];
		$html = false;
		$pdf = false;

		if($a != null) {
			$journal_data = HTTPRequester::HTTPGet(Config::env('API_URL') . 'home');
			$journal = $journal_data['body']->data->journal;
			$html = array_search($id.'.html',array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/files/html'), array('..', '.')),true);
			$pdf = array_search($id.'.pdf', array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/files/pdf'), array('..', '.')),true);
			$article = $a->data;
		} else {
			header('location: /404');
		}

		View::render('articles/index', [
			'data' => ['title' => 'Article'],
			'article' => $article,
			'html_file' => $html,
			'pdf_file' => $pdf,
			'journal' => $journal,
			'self_link' => Config::env('DOMAIN') . '/article/' . $id
		]);
	}

	public function fulltext($request)
	{
		$id = $request->param('id');
		$a = HTTPRequester::HTTPGet(Config::env('API_URL') . 'articles/' .$id. '/show')['body'];

		if($a != null) {
			$html = array_search($id.'.html',array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/files/html'), array('..', '.')),true);
			if($html != false) {
				$html = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/files/html/' . $id . '.html');
			} else {
				$html = '<h1 class="text-center">No Fulltext document found.</h1>';
			}
			$article = $a->data;
		} else {
			$article = [];
			$html = false;
			header('location: /404');
		}

		View::render('articles/fulltext', [
			'page_title' => 'Article',
			'article' => $article,
			'html' => $html,
		]);
	}

	public function pdf($request, $response)
	{
		$id = $request->param('id');
		$a = HTTPRequester::HTTPGet(Config::env('API_URL') . 'articles/' .$id. '/show')['body'];

		if($a != null) {
			$pdf = array_search($id.'.pdf',array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/files/pdf'), array('..', '.')),true);
			if($pdf != false) {
				$pdf = $_SERVER['DOCUMENT_ROOT'].'/files/pdf/' . $id . '.pdf';
			} else {
				header('location: /404');
				exit();
			}
		} else {
			header('location: /404');
			exit();
		}
		$response->file($pdf);
	}

	public function current()
	{
		$articles_data = HTTPRequester::HTTPGet(Config::env('API_URL') . 'issues/current/articles')['body']->data;

		$articles = [];
		foreach ($articles_data as $key => $value) {
			$articles[] = ['type' => $key, 'data' => $value];
		}

		View::render('articles/list', [
				'page_title' => 'Current Issue',
				'articles' => $articles
		]);
	}

	public function latest()
	{
		$articles_data = HTTPRequester::HTTPGet(Config::env('API_URL') . 'articles')['body']->data;
		$articles = [];
		foreach ($articles_data as $key => $value) {
			$articles[] = ['type' => $key, 'data' => $value];
		}

		View::render('articles/list', [
			'page_title' => 'Latest Articles',
			'articles' => $articles
		]);
	}
}