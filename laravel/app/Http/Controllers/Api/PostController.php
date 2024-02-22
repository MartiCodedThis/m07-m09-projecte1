<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Post;
use App\Models\Like;
use App\Models\User;
use App\Models\Visibility;
class PostController extends Controller
{
    public function index()
    {
        $data = Post::all();
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
                        'author_id'=>$request->user()->id,     
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

    public function show($post_id)
    {
        $post = Post::find( $post_id );
        if( $post ){
            $post->loadCount('liked');

            return response()->json([
                'success'=>true,
                'data' => $post
            ],200);
        }
        else{
            return response()->json([
                'success'=>false,
                'message'=> 'Couldnt find the specified post'
            ],404);
        }
    }

    public function update(Request $request, string $id)
    {   
        $post = Post::find( $id );
        
        if( $post ){
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
                        'upload',      // Path
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
                    'visibility_id'=>1,
                    'author_id'=>$request->user()->id,     
                ]);
                
                \Log::debug("DB storage OK");
                
                // Patró PRG amb missatge d'èxit
                return response()->json([
                    'success'=>true,
                    'data'=>$post
                ], 201);
                }
                else{
                    return response()->json([
                        'success'=>false,
                        'message'=>'Missing required fields'
                    ], 421);
                }
            }
        else{
            return response()->json([
                'success'=>false,
                'message'=>'File not found'
            ], 404);
        }                   
    }

    public function destroy($post_id)
    {
        $post = Post::find($post_id);
        if($post){
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
        else{
            return response()->json([
                'success'=>false,
                'message'=> 'Not found'
            ],404);
    }        
    }

    public function like(Request $request, $post_id)
    {
        $user_id = $request->user()->id;

        $liked = Like::where('user_id', $user_id)
            ->where('post_id', $post_id)
            ->first();
        if($request->method() == 'POST'){
            if($liked){
                return response()->json([
                    'success'=>false,
                    'message'=>'User already likes the post'
                ], 500);
            }
            \Log::debug("Create like");
            $like = Like::create([
                'user_id' => $user_id,
                'post_id' => $post_id
            ]);   
            return response()->json([
                'success'=>true,
                'data'=>$like
            ],200);
        }
        elseif($request->method() == 'DELETE'){
            \Log::debug("Delete like");
            if(!$liked){
                return response()->json([
                    'success'=>false,
                    'message'=>'User didnt like the post in the first place'
                ], 500);
            }
            try{
                $rm = $liked->delete();
                \Log::debug($rm ? "Deleted!" : "Not deleted :-(");

                return response()->json([
                    'success'=>true,
                    'data'=>$liked,
                    'message'=>'deleted'
                ],200);
            } catch (\Exception $e) {
                \Log::debug($e->getMessage()); // Display any deletion error
            }
        }
        else{
            return response()->json([
                'success'=>false,
                'message'=> 'Bad request'
            ], 401);
        }
        
    }

    public function update_workaround(Request $request, $id)
    {
        return $this->update($request, $id);
    }
}
