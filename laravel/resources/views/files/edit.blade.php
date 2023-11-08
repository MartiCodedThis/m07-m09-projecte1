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
                            <td class="py-2 px-4 text-center">                      </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <form method="post" action="{{ route('files.update', $file) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="file" class="border p-2 w-full mb-4" name="upload"/>
                    <div class="flex justify-between">
                        <button type="reset" class="w-1/4 bg-gray-400 text-white py-2 px-10 rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300">Reset</button>
                        <button type="submit" class="w-1/4 bg-emerald-500 text-white py-2 px-10 rounded hover:bg-emerald-600 active:outline-none active:ring active:ring-emerald-300">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>