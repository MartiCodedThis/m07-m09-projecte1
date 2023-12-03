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
        backface-visibility: hidden;
    }
    .about-mst .flip-back{
        transform:rotateY(180deg);
    }
    .about-mre .flip-back{
        transform:rotateX(180deg);
    }

</style>

<x-app-layout>
    <div class="containr flex flex-row">
        <div class="about-mst w-48 m-4">
            <div class="flex flex-col">
                <div class="flip">
                    <div class="flip-inner">
                        <div class="flip-front">
                            <img src="{{ route('serious-mst')}}">
                            <h3 class="font-semibold">Martí Soler Tello</h3>
                            <p id="serious-text" class="text-gray-500">Full stack developer</p>
                        </div>
                        <div class="flip-back">
                            <img src="{{ route('funny-mst')}}">
                            <h3 class="font-semibold">Martí Soler Tello</h3>
                            <p id="funny-text" class="text-gray-500">Full stax MTG player</p>
                        </div>
                    </div>
                </div>          
            </div>
            <audio id="audio-mst" >
                <source src="{{ route('oof')}}"/>
            </audio>
        </div>

        <div class="about-mre w-48 m-4">
            <div class="flex flex-col">
                <div class="flip">
                    <div class="flip-inner">
                        <div class="flip-front">
                            <img class="object-contain" id="aboutme-mre" src="{{ route('serious-mre')}}" alt="">
                            <h3 class="font-semibold">Marc Rius Egozcue</h3>
                            <p id="serious-text" class="text-gray-500">Full stack developer</p>
                        </div>
                        <div class="flip-back">
                            <img class="object-contain" id="aboutme-mst" src="{{ route('funny-mre')}}">
                            <h3 class="font-semibold">Marc Rius Egozcue</h3>
                            <p id="funny-text" class="text-gray-500">Pissing off demonic entities</p>
                        </div>
                    </div>
                </div>          
            </div>
            <audio id="audio-mre">
                <source src="{{ route('thud')}}"/>
            </audio>
        </div>

</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let audiomst = document.getElementById('audio-mst')
        let audiomre = document.getElementById('audio-mre')

        let mst = document.getElementById('about-mst')
        let mre = document.getElementById('about-mre')

        mst.addEventListener("mouseover", function(){
            audiomst.load()
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
    })

</script>