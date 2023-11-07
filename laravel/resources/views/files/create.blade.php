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
                <h1 class="text-2xl font-semibold mb-4">Upload file</h1>
                <form method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                    @csrf
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