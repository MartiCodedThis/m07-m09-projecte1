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
                <h1 class="text-2xl font-semibold mb-4">Place list</h1>
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
                        @foreach ($places as $place)
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
                                    <a href="{{ route('places.show', $place) }}" class="w-full bg-gray-400 text-white py-2 px-10 text-center rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300">View more</a>    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex justify-between">
                    <a href="{{ route('places.create') }}" class="w-1/4 bg-gray-400 text-white py-2 px-10 my-2 text-center rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300">Create</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>