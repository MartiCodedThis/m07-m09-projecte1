<style>
    .flip-front{
        filter:grayscale(100%);
    }
    .flip-back{
        filter:contrast(150%);
    }


    .flip{
        perspective: 1000px;
    }
    .flip-inner{
        width:100%;
        height:100%;
        position: relative;
        transition: transform 1.2s;
        transform-style: preserve-3d;
    }
    .about-mst:hover .flip-inner{
        transform: rotateY(180deg);
    }
    .about-mre:hover .flip-inner{
        transform: rotateX(180deg);
    }
    .flip-front, .flip-back{
        width:100%;
        height:100%;
        position: absolute;
        transform: rotateX(0);
        backface-visibility: hidden;
    }
    .about-mst .flip-back{
        transform:rotateY(180deg);
    }
    .about-mre .flip-back{
        transform:rotateX(180deg);
    }

</style>

<x-geomir-layout>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold mb-8">Meet our team</h1>
        <p class="text-xl mb-4  ">We are a small team of "experienced professionals" that are "fully committed" to bringing you the most "unique web experiences".</p>
        <div class="flex flex-row justify-center">
            <div id="about-mst" class="about-mst w-full sm:w-1/2 md:w-1/3 xl:w-1/4 m-4">
                <div class="flex flex-col">
                    <div class="flip">
                        <div class="flip-inner">
                            <div class="flip-front">
                                <button type="button"
                                        data-te-toggle="modal"
                                        data-te-target="#modalMarti"
                                        data-te-ripple-init
                                        data-te-ripple-color="light">
                                    <img class="w-full rounded-3xl mb-4" src="{{ route('serious-mst')}}">
                                </button>
                                <h3 class="font-semibold">Martí Soler Tello</h3>
                                <p id="serious-text" class="text-l font-light text-gm_textsub">Full stack developer</p>
                            </div>
                            <div class="flip-back">
                                <button type="button"
                                        data-te-toggle="modal"
                                        data-te-target="#modalMarti"
                                        data-te-ripple-init
                                        data-te-ripple-color="light">
                                    <img class="w-full rounded-3xl mb-4" src="{{ route('funny-mst')}}">
                                </button>
                                <h3 class="font-semibold">Martí Soler Tello</h3>
                                <p id="funny-text" class="text-l font-light text-gm_textsub">Full stax MTG player</p>
                            </div>
                        </div>
                    </div>          
                </div>
                <audio src="{{ route('spiderman')}}" id="audio-mst"></audio>
            </div>

            <div id="about-mre" class="about-mre w-full sm:w-1/2 md:w-1/3 xl:w-1/4 m-4">
                <div class="flex flex-col">
                    <div class="flip">
                        <div class="flip-inner">
                            <div class="flip-front">
                                <button type="button"
                                        data-te-toggle="modal"
                                        data-te-target="#modalMarc"
                                        data-te-ripple-init
                                        data-te-ripple-color="light">
                                    <img class="object-contain w-full rounded-3xl mb-4" id="aboutme-mre" src="{{ route('serious-mre')}}" alt="">
                                </button>
                                <h3 class="font-semibold">Marc Rius Egozcue</h3>
                                <p id="serious-text" class="text-gray-500">Full stack developer</p>
                            </div>
                            <div class="flip-back">
                                <button type="button"
                                        data-te-toggle="modal"
                                        data-te-target="#modalMarc"
                                        data-te-ripple-init
                                        data-te-ripple-color="light">
                                    <img class="object-contain w-full rounded-3xl mb-4" id="aboutme-mst" src="{{ route('funny-mre')}}">
                                </button>
                                <h3 class="font-semibold">Marc Rius Egozcue</h3>
                                <p id="funny-text" class="text-gray-500">Pissing off demonic entities</p>
                            </div>
                        </div>
                    </div>          
                </div>
                <audio src="{{ route('sandstorm')}}" id="audio-mre"></audio>
            </div>  
                
        <!-- MODAL 1 -->
        <div
            data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="modalMarti"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div
                data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                    <!--Close button-->
                    <button
                    type="button"
                    class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss
                    aria-label="Close">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>

                <!-- Modal 1 body  -->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                <iframe width="420" height="315"
                    src="https://www.youtube.com/embed/5TLrUNp7O2g?autoplay=1&mute=1">
                </iframe>
                </div>
                </div>
            </div>
            </div>

            <!-- Modal 2 -->
            <div
            data-te-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="modalMarc"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div
                data-te-modal-dialog-ref
                class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                    <!--Close button-->
                    <button
                    type="button"
                    class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss
                    aria-label="Close">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>

                <!-- Modal body  -->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                <iframe width="420" height="315"
                    src="https://www.youtube.com/embed/4tH5tg-2K-o?autoplay=1&mute=1">
                </iframe>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-geomir-layout>

<script defer >

        let audiomst = document.getElementById('audio-mst')
        let audiomre = document.getElementById('audio-mre')
        if(audiomst && audiomre){
            let mst = document.getElementById('about-mst')
            let mre = document.getElementById('about-mre')
            if(mst && mre){
                mst.addEventListener("mouseover", function(){
                    audiomst.play()
                })
                mst.addEventListener("mouseout", function(){
                    audiomst.pause()
                })

                mre.addEventListener("mouseover", function(){
                    audiomre.play()
                })
                mre.addEventListener("mouseout", function(){
                    audiomre.pause()
                })
            }
        }       
        
</script>

