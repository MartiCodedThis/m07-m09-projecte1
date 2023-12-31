<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Post;
use App\Models\Like;
use App\Models\Visibility;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index()
    {
        return view("posts.index", [
            "posts" => Post::withCount('liked')->paginate(5),
        ]);
    }

    public function create()
    {
        return view("posts.create", [
            "visibilities" => Visibility::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024'
         ]);
    
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
                    'visibility_id'=>$request->input('visibility'),
                    'author_id'=>$request->user()->id,     
                ]);
                \Log::debug("DB storage OK");
                // Patró PRG amb missatge d'èxit
                    return redirect()->route('posts.show', $post)
                    ->with('success', __('File successfully saved'));
            }else{
                return redirect()->route("posts.create")
                ->with('error', __('ERROR uploading file'));
            }
        } else {
            \Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.create")
                ->with('error', __('ERROR uploading file'));
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

        return view("posts.show")->with([
            'post' => $post,
            'liked' => $liked
        ]);
    }

    public function edit(Post $post)
    {
        return view("posts.edit", [
            "visibilities" => Visibility::all(),
        ])
        ->with('post', $post);
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
                return redirect()->route("posts.edit",$post)
                    ->with('error', __('ERROR uploading file'));
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
        return redirect()->route('posts.show', $post)
            ->with('success', __('File successfully saved'));            
    }

    public function destroy(Post $post)
    {
        $filePath = $post->file->filepath;
        \Storage::disk('public')->delete($filePath);
        $post->delete();
        $post->file->delete();
        return redirect()->route('posts.index')
            ->with('success', __('File successfully eliminated'));
    }

    public function like(Request $request, Post $post)
    {
        $this->authorize('create', $post);

        $user_id = $request->user()->id;
        $post_id = $post->id;

        $liked = Like::where('user_id', $user_id)
            ->where('post_id', $post_id)
            ->first();

        if ($liked) {
            \Log::debug("Delete like");
            try{
                $rm = $liked->delete();
                \Log::debug($rm ? "Deleted!" : "Not deleted :-(");
            } catch (\Exception $e) {
                \Log::debug($e->getMessage()); // Display any deletion error
            }
        }else{
            \Log::debug("Create like");
            $like = Like::create([
                'user_id' => $user_id,
                'post_id' => $post_id
            ]);            
        }
        return redirect()->route('posts.show', $post); 
    }
}
