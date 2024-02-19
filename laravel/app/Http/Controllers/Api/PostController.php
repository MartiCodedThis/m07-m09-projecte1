<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Post;
use App\Models\Like;
use App\Models\Visibility;
class PostController extends Controller
{
    public function index()
    {
        $data = Post::withCount('liked')->orderBy('created_at','desc')->paginate(10);
        if($data){
            return response()->json([
                'success'=> True,
                'data'=> $data
            ],200);
        }
        else{
            return response()->json([
                'success'=> False,
                'message'=>'No posts were found'             
            ],404);
        }
        
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024',
            'body' => 'required',
            'latitude' => 'required',
            'longitude'=> 'required'
         ]);
        if($validatedData){
            // Obtenir dades del fitxer
            $upload = $request->file('upload');
            $fileName = $upload->getClientOriginalName();
            $fileSize = $upload->getSize();
            \Log::debug("Storing file '{$fileName}' ($fileSize)...");
    
    
            // Pujar fitxer al disc dur
            $uploadName = time() . '_' . $fileName;
            $filePath = $upload->storeAs(
                    'uploads',      // Path
                    $uploadName ,   // Filename
                    'public'        // Disk
            );
        
            if (\Storage::disk('public')->exists($filePath)) {
                \Log::debug("Disk storage OK");
                $fullPath = \Storage::disk('public')->path($filePath);
                \Log::debug("File saved at {$fullPath}");
                // Desar dades a BD
                $file = File::create([
                    'filepath' => $filePath,
                    'filesize' => $fileSize,
                ]);
                $newFile = File::where('filepath', $filePath)
                    ->where('filesize', $fileSize)
                    ->first();

                if ($newFile) {
                    $post = Post::create([
                        'body'=>$request->input('body'),
                        'file_id'=>$newFile->id,
                        'latitude'=>$request->input('latitude'),
                        'longitude'=>$request->input('longitude'),
                        'visibility_id'=>1,
                        'author_id'=>1,     
                    ]);
                    \Log::debug("DB storage OK");
                    // Patró PRG amb missatge d'èxit
                        return response()->json([
                            'success'=>true,
                            'data'=> $post
                        ],201);
                }else{
                    return response()->json([
                        'success'=>false,
                        'message'=> 'Error uploading file'
                    ],421);
                }
            } else {
                \Log::debug("Disk storage FAILS");
                // Patró PRG amb missatge d'error
                return response()->json([
                    "success"=>false,
                    "message"=> "Disk storage error"
                ],500);
            }
        }
        else{
            return response()->json([
                'success'=>False,
                'message'=>'Enter a valid image file'
           ],421);
        }
        
    }

    public function show(Post $post, Request $request)
    {
        $user_id = $request->user()->id;
        $post_id = $post->id;
        $post->loadCount('liked');
    
        $liked = Like::where('user_id', $user_id)
                 ->where('post_id', $post_id)
                 ->exists();

        return response()->json([
            'success'=>true,
            'data' => $post
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $oldfilePath = $post->file->filepath;
        
        // Validar fitxer
        $validatedData = $request->validate([
            'body' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'visibility' => 'required',
            'upload' => 'nullable|mimes:gif,jpeg,jpg,png|max:1024'
        ]);
        if($validatedData){
            if ($request->hasFile('upload')) {
                // Obtenir dades del fitxer
                $upload = $request->file('upload');
                $fileName = $upload->getClientOriginalName();
                $fileSize = $upload->getSize();
                \Log::debug("Storing file '{$fileName}' ($fileSize)...");
                
                // Pujar fitxer al disc dur
                $uploadName = time() . '_' . $fileName;
                $filePath = $upload->storeAs(
                    'uploads',      // Path
                    $uploadName ,   // Filename
                    'public'        // Disk
                );
                
                if (\Storage::disk('public')->exists($filePath)) {
                    \Log::debug("Disk storage OK");
                    $fullPath = \Storage::disk('public')->path($filePath);
                    \Log::debug("File saved at {$fullPath}");
                    // Desar dades a BD
                    $post->file->update([
                        'filepath' => $filePath,
                        'filesize' => $fileSize,
                    ]);
                    // Esborrar fitxer antic
                    \Storage::disk('public')->delete($oldfilePath);
                } else {               
                    \Log::debug("Disk storage FAILS");
                    // Patró PRG amb missatge d'error
                    return response()->json([
                        "success"=>false,
                        "message"=> "Disk storage error"
                    ],500);
                }
            }
            
            // Escenari A. No ha pujat cap fitxer
            // Escenari B. Ha pujat un nou fitxer correctament
            $post->update([
                'body'=>$request->input('body'),
                'latitude'=>$request->input('latitude'),
                'longitude'=>$request->input('longitude'),
                'visibility_id'=>$request->input('visibility'),
                'author_id'=>$request->user()->id,     
            ]);
            
            \Log::debug("DB storage OK");
            
            // Patró PRG amb missatge d'èxit
            return response()->json([
                'success'=>true,
                'data'=>$post
            ]);
        }
        else{
            return response()->json([
                'success'=>false,
                'message'=>'Missing required fields'
            ], 421);
        }
                   
    }

    public function destroy(Post $post)
    {
        $filePath = $post->file->filepath;
        if($filePath){
            \Storage::disk('public')->delete($filePath);
            $post->delete();
            $post->file->delete();
            return response()->json([
                'success'=>true,
                'data'=>$post
            ]);
        }
        else{
            return response()->json([
                'success'=>false,
                'message'=> 'Wrong filepath'
            ], 500);
        }        
    }
}
