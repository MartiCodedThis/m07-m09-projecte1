<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\File;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Models\Visibility;

class PlaceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Place::class, 'place');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("places.index",[
            "places" => Place::withCount('favorited')->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("places.create",[
            "visibilities" => Visibility::all(),
        ]);
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
                'visibility_id'=>$request->input('visibility'),
                'author_id' =>  $request->user()->id
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('places.show', $place)
                ->with('success', "{{__('File successfully saved')}}");
        } else {
            \Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("places.create")
                ->with('error', "{{__('ERROR uploading file')}}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place, Request $request)
    {
        $user_id = $request->user()->id;
        $place_id = $place->id;
        $place->loadCount('favorited');

        $fav = Favorite::where('user_id', $user_id)
                 ->where('place_id', $place_id)
                 ->exists();

        return view("places.show")->with(['place' => $place, 'fav' => $fav]);
    }

    /**
     * 
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place)
    {
        return view("places.edit", [
            "visibilities" => Visibility::all(),
        ])->with('place', $place);
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
            'visibility' => 'required',
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
                    'visibility_id'=>$request->input('visibility'),
                    'author_id' =>  $request->user()->id
                ]);
                \Log::debug("DB storage OK");
                // Patró PRG amb missatge d'èxit
                return redirect()->route('places.show', $place)
                    ->with('success', "{{__('File successfully saved')}}");
            } else {
                \Log::debug("Disk storage FAILS");
                // Patró PRG amb missatge d'error
                return redirect()->route("places.create")
                    ->with('error', "{{__('ERROR uploading file')}}");
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
                    'visibility_id'=>$request->input('visibility'),
                    'author_id' => $request->user()->id,
                ]);
                \Log::debug("DB storage OK");
                // Patró PRG amb missatge d'èxit
                return redirect()->route('places.show', $place)
                    ->with('success', "{{__('File successfully saved')}}");
            } else {
                return redirect()->route("places.edit", $place)
                    ->with('error',  "{{__('ERROR uploading file')}}" );
            }
            \Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("places.edit", $place)
                ->with('error', "{{__('ERROR uploading file')}}" );
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
            ->with('success', "{{__('File successfully eliminated)}}");


        // NO FUNCIONA CORRECTAMENT EL DESTROY, LA FUNCIÓ ACTUAL NOMÉS ESBORRA LA PLACE PERO NO EL FITXER
        // $stored = \Storage::disk('public')->get($place->file->filepath);
        // if($stored){
        //     \Storage::disk('public')->delete($place->file->filepath);
        //     $place->file->delete();
        //     return redirect()->route('places.index');
        // }   
        // else{
        //     return redirect()->route('places.show', $place)
        //         ->with('error','Fitxer inexistent');
        // }
    }

    public function favorite(Request $request, Place $place){
        
        $user_id = $request->user()->id;
        $place_id = $place->id;

        $favExists = Favorite::where('user_id', $user_id)
        ->where('place_id', $place_id)
        ->first();

        if ($favExists) {
            $favExists->delete();

            return redirect()->route('places.show', $place)
                ->with('warning', "{{__('Place removed from favorites')}}");
        } 
        else {
            $favorite = Favorite::create([
                'user_id' => $user_id,
                'place_id' => $place_id
            ]);

            return redirect()->route('places.show', $place)
                ->with('success', "{{__('Place added to favorites')}}");
        }
    }
}
