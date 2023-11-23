@include('partials.flash')

<x-app-layout>
   <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ __('Places') }}
       </h2>
   </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">ID</th>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Name</th>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Description</th>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">File ID</th>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Latitude</th>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Longitude</th>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Author ID</th>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Created</th>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Updated</th>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 text-center">{{ $place->id }}</td>
                            <td class="py-2 px-4 text-center">{{ $place->name }}</td>
                            <td class="py-2 px-4 text-center">{{ $place->description }}</td>
                            <td class="py-2 px-4 text-center">{{ $place->file_id }}</td>
                            <td class="py-2 px-4 text-center">{{ $place->latitude }}</td>
                            <td class="py-2 px-4 text-center">{{ $place->longitude }}</td>
                            <td class="py-2 px-4 text-center">{{ $place->author_id }}</td>
                            <td class="py-2 px-4 text-center">{{ $place->created_at }}</td>
                            <td class="py-2 px-4 text-center">{{ $place->updated_at }}</td>
                            <td class="py-2 px-4 text-center">
                                <div class="flex flex-col w-full space-y-1">
                                        <a href="{{ route('places.edit', $place) }}" class="w-full bg-gray-400 text-white py-2 px-10 text:center rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300"> Edit </a> 
                                    <form method="POST" action="{{ route('places.destroy', $place) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-500 text-white py-2 px-10 rounded hover:bg-red-600 active:outline-none active:ring active:ring-red-300">Destroy</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <img src='{{ asset("storage/{$place->file->filepath}") }}' alt="img">
                <form  method="POST" action="{{ route('places.favorite', $place) }}">
                    @csrf
                    @if($fav)
                        @method('DELETE')
                        <button type="submit" class="w-full bg-gray-400 text-white py-2 px-10 text:center rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300">Unfav</button>
                    @else
                        <button type="submit" class="w-full bg-green-400 text-white py-2 px-10 text:center rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300">Fav</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>