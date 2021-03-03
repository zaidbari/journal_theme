<?php namespace App\Controllers;

use App\Lib\Config;
use App\Lib\Upload;

class ReceiveFiles
{
	public function pdf( $request ) : string
	{
		if($request->method() == 'POST') {
			if(Upload::checkType('file', ['pdf'])) {
				$name = Config::env('APP_ABBRV') . '-v' . $request->param('volume') . '-i' . $request->param('issue') . '-' . $request->param('id');
				$file = new Upload('file', 'pdf', $name);
				if ( $file->move() ) {
					$path = $file->save_name;
					$success = ['success' => true, 'path' => 'files/pdf/' . $path];
					return json_encode($success);
				}
			} else return json_encode(['error' => 403, 'msg' => 'Invalid File type']);
		} return json_encode(['error' => 404, 'msg' => 'Not Found']);
	}

	public function html( $request ) : string
	{
		if($request->method() == 'POST') {
			if(Upload::checkType('file', ['html'])) {
				$name = Config::env('APP_ABBRV') . '-v' . $request->param('volume') . '-i' . $request->param('issue') . '-' . $request->param('id');
				$file = new Upload('file', 'html', $name);
				if ( $file->move() ) {
					$path = $file->save_name;
					$success = [ 'success' => true, 'path' => 'files/html/' . $path ];
					return json_encode($success);
				}
			} return json_encode(['error' => 403, 'msg' => 'Invalid File type']);
		} return json_encode(['error' => 404, 'msg' => 'Not Found']);
	}
}