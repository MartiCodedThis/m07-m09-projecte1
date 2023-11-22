@include('partials.flash')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                <h1 class="text-2xl font-semibold mb-4">Post list</h1>
                <div class="flex justify-between">
                    <a href="{{ route('posts.create') }}" class="w-1/4 bg-gray-400 text-white py-2 px-10 mb-4 text-center rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300">Create post</a>
                </div>
                <div class="border-b border-gray-200">
                    <form action="{{ route('posts.index') }}" method="GET" class="mb-4">
                        @csrf
                        <div class="flex w-full space-x-2">
                            <input type="text" name="search" placeholder="Buscar en el cuerpo del post" class="form-input flex-grow" />
                            <button type="submit" class="w-1/8 bg-gray-400 text-white py-2 px-10 text-center rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300">Buscar</button>
                        </div>
                    </form>
                    @foreach ($posts as $post)
                        <a href="{{ route('posts.show', $post->id) }}">
                            <div class="bg-white mx-auto w-full sm:w-3/4 md:w-1/2 lg:w-1/2 xl:w-1/2 border border-gray-300 rounded-lg p-4 mb-4">
                                <div class="flex items-center mb-2">
                                    <img class="w-10 h-10 rounded-full mr-4" src='{{ asset("storage/{$post->file->filepath}") }}' alt="File Image" />
                                    <div>
                                        <p class="text-gray-800 font-semibold">{{ $post->user->name }}</p>
                                    </div>
                                </div>
                                <p class="text-gray-700 mb-4 max-w-full break-words">{{ $post->body }}</p>
                                <img class="w-1/1 mx-auto mb-4" src='{{ asset("storage/{$post->file->filepath}") }}' alt="File Image" />
                                <div class="flex justify-between text-gray-600">
                                    <p>{{ $post->created_at->diffForHumans() }}</p> 
                                </div>
                            </div> 
                        </a>
                    @endforeach

                   
                    <div class="mt-4">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>