<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Upload;

class HomeController extends Controller
{

    public function index() {
        
        $files = Upload::all();

        foreach ( $files as $file )
        {
            echo '<img src=" ' .asset($file->path). ' " alt="">';
        }
    }

    public function upload(Request $request) {
        
        // $path = $request->file('image')->store('photo');
        // $path = Storage::putFile('public', $request->file('image'));

        try {

            if ( $request->hasFile('image') ) {
                 
            $files = $request->file('image');

            foreach ( $files as $file ) {
                $name = rand(1, 9999);
                $extention = $file->getClientOriginalExtension();
                $newName = $name . '.' . $extention;
                $size = $file->getClientSize();
                Storage::putFileAs('public', $file, $newName);

                $data = [
                   'path' => 'storage/' . $newName,
                   'size' => $size
                ];

                Upload::create($data);
            }
               return 'Success';
            }

            return 'Empty';

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function list(){

        // $files = Storage::Files('');
        // $files = Storage::allFiles('public');
        // $directories = Storage::allDirectories('');
        // $directory = Storage::makeDirectory('image/gif');
        $directory = Storage::deleteDirectory('phone');
        dd($directory);
    }

    public function show() {
        $path = Storage::url('123.jpg');
        return '<img src=" ' .asset('/storage/123.jpg'). ' " alt="">';
    }

    public function copy() {
        try {
            Storage::copy('public/123.jpg','image/copy-emage.jpg');
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function move() {
        try {
            Storage::move('image/copy-emage.jpg','public/move-image.jpg');
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function download() {
        try {
            return Storage::disk('local')->download('phone/1608791053.jpg');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete() {
        try {
            Storage::disk('local')->delete('phone/1608791053.jpg');
            return 'Deleted';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}