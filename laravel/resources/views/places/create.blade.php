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
                <h1 class="text-2xl font-semibold mb-4"> {{ __('New location') }}</h1>
                <form method="post" action="{{ route('places.store') }}" enctype="multipart/form-data">
                    @csrf
                    <label for="name">{{ __('Name') }}</label>
                    <input type="text" class="border p-2 w-full mb-4" name="name" maxlength="255"/>
                    <label for="description">{{ __('Description') }}</label>
                    <input type="text" class="border p-2 w-full mb-4" name="description" maxlength="255"/>
                    <label for="upload">{{ __('Image') }}</label>
                    <input type="file" class="border p-2 w-full mb-4" name="upload"/>
                    <label for="upload">{{ __('Latitude') }}</label>
                    <input type="number" class="border p-2 w-full mb-4" name="latitude" step="0.000001"/>
                    <label for="upload">{{ __('Longitude') }}</label>
                    <input type="number" class="border p-2 w-full mb-4" name="longitude" step="0.000001"/>
                    <label for="visibility">{{__('Visibility')}}</label>
                        <select type="select" name="visibility" id="visibility"class="border p-2 w-full mb-4">
                            @foreach($visibilities as $visibility)
                            <option value="{{ $visibility->id }}">{{ $visibility->name }}</option>
                            @endforeach
                        </select>
                    <div class="flex justify-between">
                        <button type="reset" class="w-1/4 bg-gray-400 text-white py-2 sm:px-5 lg:px-10 rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300">Reset</button>
                        <button type="submit" class="w-1/4 bg-emerald-500 text-white py-2 sm:px-5 lg:px-10 rounded hover:bg-emerald-600 active:outline-none active:ring active:ring-emerald-300">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>