@include('partials.flash')

<x-geomir-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl px-6 sm:px-0 font-bold mb-4">{{__('Post list')}}</h1>
            <div class="bg-gm_bg1 border-t-2 sm:border-2 border-gm_bg2 overflow-hidden shadow-sm sm:rounded-3xl p-10">
                <div>
                    <form action="{{ route('posts.index') }}" method="GET" class="mb-4">
                        @csrf
                        <div class="flex w-full space-x-2">
                            <input type="text" name="search" placeholder="{{__('Search for posts...')}}" class="form-input flex-grow bg-gm_bg1 border-2 border-gm_bg2 rounded-full" />
                            <button type="submit" class="bg-gm_emphasis text-gm_bg1 font-bold py-2 px-10 text-center rounded-full hover:bg-gm_bg1 hover:text-gm_text hover:outline hover:outline-gm_emphasis active:outline-gm_text">{{__('Search')}}</button>
                        </div>
                    </form>
                    @foreach ($posts as $post)
                    <a href="{{ route('posts.show', $post->id) }}">
                        <div class="mx-auto w-full p-4 mb-4 border-b-2 border-gm_bg2">
                            <div class="flex justify-between">
                                <p class="text-gm_textsub font-light">{{__('Author:')}} {{ $post->user->name }}</p>
                            </div>
                            <div class="flex justify-between mb-2">
                                <div class="flex space-x-1 items-center">
                                    <x-heroicon-s-heart class="text-gm_emphasis w-5 h-5"/>
                                    <p class="text-gm_text">{{ $post->liked_count }}</p>
                                </div>
                                <p class="text-gm_textsub font-light">{{ $post->created_at->diffForHumans() }}</p> 
                            </div>
                            <div class="w-5/6 border-2 border-gm_bg2 rounded-xl h-98 mx-auto mb-4">
                                <img class="bg-white object-cover mx-auto" src='{{ asset("storage/{$post->file->filepath}") }}' alt="File Image" />
                            </div>
                            <p class="w-5/6 mb-4 max-w-full text-xl break-words">{{ $post->body }}</p>
                        </div> 
                    </a>
                    @endforeach
                    <div class="flex justify-end pt-4">
                        @can('create',$posts)
                        <a href="{{ route('posts.create') }}" class="flex bg-gm_emphasis text-gm_bg1 font-bold py-2 px-10 items-center rounded-full hover:bg-gm_bg1 hover:text-gm_text hover:outline hover:outline-gm_emphasis active:outline-gm_text ">
                            <x-heroicon-s-plus class="h-8 w-8"/>
                            <p>{{__('Create post')}}</p>
                        </a>
                        @endcan
                    </div>
                    <div>
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-geomir-layout>