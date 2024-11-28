
<style>.educationhero{
    background-image: url({{asset("img/ed3.png")}});
  }</style>


<section class="container-fluid sectionbackground educationhero  d-flex align-items-center justify-content-center">
    <h2 class="sectionname">शिक्षा </h2>
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
                <img src="{{ asset("img/ed3.png") }}" alt="" class="col-12 rounded">
                <p class="post_title"> {{ $mukhyaPost->title }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-12">
                <img src="{{ asset("img/ed3.png") }}" alt="" class="col-12 rounded">
                <p class="post_title"> {{ $mukhyaPost->title }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-12">
                <img src="{{ asset("img/ed3.png") }}" alt="" class="col-12 rounded">
                <p class="post_title"> {{ $mukhyaPost->title }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-12">
                <img src="{{ asset("img/ed3.png") }}" alt="" class="col-12 rounded">
                <p class="post_title"> {{ $mukhyaPost->title }}</p>
                </div>
            </div>
        </div>
    </div>
</section>