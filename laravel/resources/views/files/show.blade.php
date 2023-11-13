@include('partials.flash')

<x-app-layout>
   <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ __('Files') }}
       </h2>
   </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">ID</td>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Filepath</td>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Filesize</td>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Created</td>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Updated</td>
                            <th class="bg-gray-200 text-gray-700 py-2 px-4 text-center">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 text-center">{{ $file->id }}</td>
                            <td class="py-2 px-4 text-center">{{ $file->filepath }}</td>
                            <td class="py-2 px-4 text-center">{{ $file->filesize }}</td>
                            <td class="py-2 px-4 text-center">{{ $file->created_at }}</td>
                            <td class="py-2 px-4 text-center">{{ $file->updated_at }}</td>
                            <td class="py-2 px-4 text-center">
                                <div class="flex flex-col w-full space-y-1">
                                    <a href="{{ route('files.edit', $file) }}" class="w-full bg-gray-400 text-white py-2 px-10 text:center  rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300"> Edit </a> 
                                    <form method="POST" action="{{ route('files.destroy', $file) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-500 text-white py-2 px-10 rounded hover:bg-red-600 active:outline-none active:ring active:ring-red-300">Destroy</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <img src='{{ asset("storage/{$file->filepath}") }}' alt="img">
            </div>
        </div>
    </div>
</x-app-layout>