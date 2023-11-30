@include('partials.flash')

<x-geomir-layout>
   <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ __('Places') }}
       </h2>
   </x-slot>


    <div>
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="min-h-[50%]" id="map" >

                </div>
                @foreach ($places as $place)
                <div class="flex flex-row border-t-2 border-t-gm_bg2 m-3">
                    <div class="flex flex-end flex-col w-1/5">
                        <div>
                            <div >
                                <img class="w-full rounded-full" src='{{asset("storage/{$place->file->filepath}") }}' alt="location-pin">
                            </div>
                        </div>
                        <div class="flex flex-row justify-center">                         
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 text-gm_emphasis">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                </svg>
                            
                                <p class="text-gm_emphasis">
                                    {{ $place->favorited_count }}
                                </p>
                        </div>
                    </div>
                    <div class="flex flex-col text-white w-4/5 ml-3">
                        <div class="flex flex-row text-white justify-between">
                            <h2 class="text-2xl font-semibold mb-4"> {{ $place->name }} </h2>
                            <p>{{ $place->latitude }}º {{ $place->longitude }}º</p>
                        </div>
                        
                        <p> {{ $place->description }} </p>
                    </div>
                </div>
                @endforeach
                <!-- <table class="w-full table-auto">
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
                            <td class="py-2 px-4 text-center">{{ $place->favorited_count }}</td>
                            <td class="py-2 px-4 text-center">
                                <div class="flex flex-col w-full space-y-1">
                                    <a href="{{ route('places.show', $place) }}" class="w-full bg-gray-400 text-white py-2 px-10 text-center rounded hover:bg-gray-500 active:outline-none active:ring active:ring-gray-300">View more</a>    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table> -->
                
            </div>
        </div>
    </div>
    <nav class="flex justify-end self-end w-vw -z-1">
        <div class="flex bg-gm_emphasis text-gm_bg1 font-bold py-2 px-10 items-center rounded-full hover:bg-gm_bg1 hover:text-gm_text hover:outline hover:outline-gm_emphasis active:outline-gm_text ">
            <a href="{{ route('places.create') }}">
                <x-heroicon-s-plus class="h-8 w-8"/>
                <p>Create</p>
            </a>
        </div>
    </nav>
</x-geomir-layout>

