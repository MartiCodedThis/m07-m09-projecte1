<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $files = File::all();
        if($files){
            return response()->json([
                "success"=> true,
                "data"=> $files  
            ], 200);
        }
        else{
            return response ()->json([
                'success'=>false,
                'message'=>'Internal error'
            ], 500);
        }
       
    }

    /**
     * Store a newly created resource in storage.
     */
    
   public function store(Request $request)
   {
          // Validar fitxer
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
            \Log::debug("Disk storage OK (create)");
            $fullPath = \Storage::disk('public')->path($filePath);
            \Log::debug("File saved at {$fullPath}");
            // Desar dades a BD
            $file = File::create([
                'filepath' => $filePath,
                'filesize' => $fileSize,
            ]);
            \Log::debug("DB storage OK (create)");

           return response()->json([
               'success' => true,
               'data'    => $file
           ], 201);
       } else {
           return response()->json([
               'success'  => false,
               'message' => 'Error uploading file'
           ], 500);
       }
   }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $file = File::find($id);
        if($file){
            return response()->json([
                'success' => true,
                'data' => $file
            ], 200);}
        else{
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar fitxer
        $validatedData = $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024'
        ]);

        if($validatedData){
            $tempFile = File::find($id);
            if($tempFile){
                // Obtenir dades del fitxer
                $upload = $request->file('upload');
                $fullPath = $tempFile->filepath;

                $fileSize = $upload->getSize();
                $file = new File([
                    'filepath' => $tempFile->filepath,
                    'filesize' => $fileSize
                ]);
                $ok = $file->diskSave($upload);

                if ($ok) {
                    \Log::debug("File saved at {$fullPath}");
                    // Desar dades a BD
                    $file->save();
                    \Log::debug("DB storage OK");

                    // Patró PRG amb missatge d'èxit
                    return response()->json([
                        'success'=>true,
                        'data'=>$file
                    ], 200);
                } 
                else {
                    return response()->json([
                        'success'=>false,
                        'message'=>'Error saving file'
                    ], 500);
                }
            }
            else{
                return response()->json([
                    'success'=>false,
                    'message'=>'File not found'
                ], 404);
            }
        }
        else {
            return response()->json([
                'success'=>false,
                'message'=>'Invalid format'
            ], 421);
        }        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $file = File::find($id);
        if($file){
            $stored = \Storage::disk('public')->get($file->filepath);
            if($stored){
                \Storage::disk('public')->delete($file->filepath);
                $file->delete();
                return response()->json([
                    'success'=>true,
                    'data'=>$file
                ], 200);
            }   
            else{
                return response()->json([
                    'success'=>false,
                    'message'=>'File doesnt exist'
                ], 500);
            }
        }
        else{
            return response()->json([
                'success'=>false,
                'message'=>'File not found'
            ], 404);
        }
        
    }

    public function update_workaround(Request $request, $id)
    {
        return $this->update($request, $id);
    }
 
}
