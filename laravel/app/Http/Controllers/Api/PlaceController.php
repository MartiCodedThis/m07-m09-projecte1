<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\File;
use App\Models\Favorite;
use App\Models\Visibility;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Place::all();
        if ($data) {
            return response()->json([
                'success' => True,
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'success' => False,
                'message' => 'No places were found'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024'
        ]);
        if ($validatedData) {
            // Obtenir dades del fitxer
            $upload = $request->file('upload');
            $fileName = $upload->getClientOriginalName();
            $fileSize = $upload->getSize();
            \Log::debug("Storing file '{$fileName}' ($fileSize)...");

            // Pujar fitxer al disc dur
            $uploadName = time() . '_' . $fileName;
            $filePath = $upload->storeAs(
                'uploads',      // Path
                $uploadName,   // Filename
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
                    $place = Place::create([
                        'name' => $request->input('name'),
                        'description' => $request->input('description'),
                        'file_id' => $file->id,
                        'latitude' => $request->input('latitude'),
                        'longitude' => $request->input('longitude'),
                        'visibility_id' => $request->input('visibility'),
                        'author_id' => 1
                    ]);
                    \Log::debug("DB storage OK");
                    // Patró PRG amb missatge d'èxit
                    return response()->json([
                        'success' => true,
                        'data' => $place
                    ], 201);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error uploading file'
                    ], 421);
                }
            } else {
                \Log::debug("Disk storage FAILS");
                // Patró PRG amb missatge d'error
                return response()->json([
                    "success" => false,
                    "message" => "Disk storage error"
                ], 500);
            }
        } else {
            return response()->json([
                'success' => False,
                'message' => 'Enter a valid image file'
            ], 421);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($place_id)
    {
        $place = Place::find($place_id);
        if ($place) {
            $place->loadCount('favorited');
            return response()->json([
                'success' => true,
                'data' => $place
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Couldnt find the specified place'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $place_id)
    {
        $place = Place::find($place_id);
        if ($place) {
            $oldfilePath = $place->file->filepath;
            $validatedData = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'latitude' => 'numeric',
                'longitude' => 'numeric',
                'visibility' => 'required',
                'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024'
            ]);
            if ($validatedData) {
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
                        $uploadName,   // Filename
                        'public'        // Disk
                    );

                    if (\Storage::disk('public')->exists($filePath)) {
                        \Log::debug("Disk storage OK");
                        $fullPath = \Storage::disk('public')->path($filePath);
                        \Log::debug("File saved at {$fullPath}");
                        \Storage::disk('public')->delete($oldfilePath);
                        // Desar dades a BD
                        $place->file->update([
                            'filepath' => $filePath,
                            'filesize' => $fileSize,
                        ]);
                    } else {
                        \Log::debug("Disk storage FAILS");
                        // Patró PRG amb missatge d'error
                        return response()->json([
                            "success" => false,
                            "message" => "Disk storage error"
                        ], 500);
                    }
                }
                // Assumint que s'ha pujat l'arxiu bé...
                $place->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                    'visibility_id' => $request->input('visibility'),
                    'author_id' => 1
                ]);
                \Log::debug("DB storage OK");
                return response()->json([
                    'success' => true,
                    'data' => $place
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields'
                ], 421);
            }
        } else {
            return response()->json([
                'success' => False,
                'message' => 'Place was not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($place_id)
    {
        $place = Place::find($place_id);
        if ($place) {
            $filepath = $place->file->filepath;
            if ($filepath) {
                \Storage::disk('public')->delete($filepath);
                $place->delete();
                $place->file->delete();
                return response()->json([
                    'success' => true,
                    'data' => $place
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Wrong filepath'
                ], 500);
            }
        } else {
            return response()->json([
                'success' => False,
                'message' => 'Place was not found'
            ], 404);
        }
    }

    public function favorite(Request $request, $place_id)
    {
        $place = Place::find($place_id);
        if ($place) {
            $user_id = $request->user()->id;

            $favExists = Favorite::where('user_id', $user_id)
                ->where('place_id', $place_id)
                ->first();

            if ($favExists) {
                \Log::debug('Removing place from favorites');
                try {
                    $rm = $favExists->delete();
                    \Log::debug($rm ? "Deleted!" : "Not deleted :-(");
                    return response()->json([
                        'success' => true,
                        'data' => $favExists,
                        'message' => 'deleted'
                    ], 200);
                } catch (\Exception $e) {
                    \Log::debug($e->getMessage());
                }
            } else {
                \Log::debug('Adding place from favorites');
                $favorite = Favorite::create([
                    'user_id' => $user_id,
                    'place_id' => $place_id
                ]);
            }
            return response()->json([
                'success' => true,
                'data' => $favorite
            ], 200);
        } else {
            return response()->json([
                'success' => False,
                'message' => 'Place was not found'
            ], 404);
        }
    }
    public function update_workaround(Request $request, $id)
    {
        return $this->update($request, $id);
    }
}
