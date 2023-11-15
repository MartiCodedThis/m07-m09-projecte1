<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\File;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("places.index", [
            "places" => Place::paginate(5),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("places.create");
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
            $place = Place::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'file_id' => $file->id,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'author_id' =>  $request->user()->id
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('places.show', $file)
                ->with('success', 'File successfully saved');
        } else {
            \Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("places.create")
                ->with('error', 'ERROR uploading file');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        return view("places.show")->with(['place' => $place]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place)
    {
        return view("places.edit")->with('place', $place);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
        $oldfilePath = $place->file->filepath;

        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024'
        ]);

        if ($request->hasFile('upload')){
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
                \Storage::disk('public')->delete($oldfilePath);
                $file_id = File::where('filepath',$filePath)->where('filesize',$fileSize)->first();
                // Desar dades a BD
                $file->update([
                    'filepath' => $filePath,
                    'filesize' => $fileSize,
                ]);
                $place->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'file_id' => $file->id,
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                    'author_id' =>  $request->user()->id
                ]);
                \Log::debug("DB storage OK");
                // Patró PRG amb missatge d'èxit
                return redirect()->route('places.show', $place)
                    ->with('success', 'File successfully saved');
            } else {
                \Log::debug("Disk storage FAILS");
                // Patró PRG amb missatge d'error
                return redirect()->route("places.create")
                    ->with('error', 'ERROR uploading file');
            }
        }else{

            $file_id = File::where('filepath', $place->file->filepath)->where('filesize', $place->file->filesize)->first();
            
            if ($file_id){
                $place->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'file_id' => $file_id->id,
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                    'author_id' => $request->user()->id,
                ]);
                \Log::debug("DB storage OK");
                // Patró PRG amb missatge d'èxit
                return redirect()->route('places.show', $place)
                    ->with('success', 'File successfully saved');
            } else {
                return redirect()->route("places.edit", $place)
                    ->with('error', 'ERROR uploading file');
            }
            \Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("places.edit", $place)
                ->with('error', 'ERROR uploading file');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        $filepath = $place->file->filepath;
        \Storage::disk('public')->delete($filepath);
        $place->delete();
        $place->file->delete();
        return redirect()->route('places.index')
            ->with('success', 'File successfully deleted');
    }
}
