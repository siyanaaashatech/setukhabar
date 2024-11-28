{{-- For  खेलकुद --}}
<style>
    .gamesectionstart{
        background:#F1F1F1;
    }

    .gameshero{
  background-image: url({{asset("img/games2.png")}});
} 
</style>
<section class="container-fluid sectionbackground gameshero  d-flex align-items-center justify-content-center">
    <h2 class="sectionname ">खेलकुद</h2>
</section>
<section class="cover mt-5">
    <div class="container gamesectionstart rounded ">
        <div class="row">
            <div class="col-md-8">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach ($coverimages as $key => $coverimage)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}"
                            class="" aria-label="Slide {{ $key + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach ($coverimages as $key => $coverimage)
                        <div class="carousel-item">
                            <a
                                href="{{ route('post.render', ['slug' => $coverimage->slug ?? '', 'id' => $coverimage->id ?? '']) }}">
                                <img class="d-block w-100 carousel-top-image" src="{{ $coverimage->firstImagePath }}">
                                <div class="carousel-caption d-none d-md-block">
                                    <p>{{ $coverimage->title }}</p>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="main_news">
                    <ul>
                        @foreach ($mukhyaNews->take(5) as $mukhyaPost)
                        <a href="{{ route('post.render', ['slug' => $mukhyaPost->slug ?? '', 'id' => $mukhyaPost->id ?? '']) }}" class="d-flex py-2" style="border-bottom:2px solid #ECE824">
                  <img src="{{ asset("img/games2.png") }}" class="square_image"
                            alt="...">
                     <p class="post_title"> {{ $mukhyaPost->title }}</p>
                        </a>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container">
    @if ($afterMainAd)
    <div class="top_ad">
        <a target="_blank" href="{{ $afterMainAd->url ?? '#' }}">
            <img src="{{ asset('uploads/images/ads/' . ($afterMainAd->image ?? 'default.jpg')) }}" alt="">
        </a>
    </div>
    @else
    <!-- Handle the case when no ad is found -->
    <p>No ad available.</p>
    @endif
</div>
<section clas="educationstart">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="col-md-12">
                <img src="{{ asset("img/games2.png") }}" alt="" class="col-12 rounded">
                <p class="post_title"> {{ $mukhyaPost->title }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-12">
                <img src="{{ asset("img/games2.png") }}" alt="" class="col-12 rounded">
                <p class="post_title"> {{ $mukhyaPost->title }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-12">
                <img src="{{ asset("img/games2.png") }}" alt="" class="col-12 rounded">
                <p class="post_title"> {{ $mukhyaPost->title }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-12">
                <img src="{{ asset("img/games2.png") }}" alt="" class="col-12 rounded">
                <p class="post_title"> {{ $mukhyaPost->title }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var carouselIndicators = document.querySelectorAll("#carouselExampleIndicators .carousel-indicators button");
        var carouselItems = document.querySelectorAll("#carouselExampleIndicators .carousel-inner .carousel-item");
        carouselIndicators[0].classList.add("active");
        carouselItems[0].classList.add("active");
    });
</script>