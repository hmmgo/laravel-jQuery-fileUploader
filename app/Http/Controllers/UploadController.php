<?php

namespace App\Http\Controllers;


use DB;

// add request here like use Request or whatever you created

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class UploadController extends Controller {

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{

		return view('edit.upload');

	}

	public function store(Request $request)
	{
		if ($request->hasFile('files')) {
			$file = $request->file('files');
		foreach($file as $files){
			$filename = $files->getClientOriginalName();
			$extension = $files->getClientOriginalExtension();
			$picture = sha1($filename . time()) . '.' . $extension;
			$folder = project::select('folder')->where('id', session('progetto'))->get();
			
			//specify your folder
			
			$destinationPath = public_path() . '/files_clients/' .$folder[0]->folder. '/';
			$files->move($destinationPath, $picture);
			$destinationPath1='http://'.$_SERVER['HTTP_HOST'].'/files_clients/' .$folder[0]->folder. '/';
					$filest = array();
					$filest['name'] = $picture;
					$filest['size'] = $this->get_file_size($destinationPath.$picture);
					$filest['url'] = $destinationPath1.$picture;
			$filest['thumbnailUrl'] = $destinationPath1.$picture;
			$filesa['files'][]=$filest;}
		return  $filesa;
		}
	}

// add more customized code available at https://github.com/blueimp/jQuery-File-Upload in https://github.com/blueimp/jQuery-File-Upload/blob/master/server/php/UploadHandler.php

	/*
     * jQuery File Upload Plugin PHP Class
     * https://github.com/blueimp/jQuery-File-Upload
     *
     * Copyright 2010, Sebastian Tschan
     * https://blueimp.net
     *
     * Licensed under the MIT license:
     * http://www.opensource.org/licenses/MIT
     */

	protected function get_file_size($file_path, $clear_stat_cache = false) {
		if ($clear_stat_cache) {
			if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
				clearstatcache(true, $file_path);
			} else {
				clearstatcache();
			}
		}
		return $this->fix_integer_overflow(filesize($file_path));
	}

	protected function fix_integer_overflow($size) {
		if ($size < 0) {
			$size += 2.0 * (PHP_INT_MAX + 1);
		}
		return $size;
	}

}
