<section class="container-fluid">
    <div class="container">
       <div class="row py-2">
       @foreach ($breakingNews->take(1) as $breakingPost)
           <div class="col-md-8 herosection">
            <a href="{{ route('post.render', ['slug' => $breakingPost->slug ?? '', 'id' => $breakingPost->id ?? '']) }}">
               <h1 class="mt-3">{{$breakingPost->title}}</h1>
               <img class="imgsize rounded" src="{{ $breakingPost->firstImagePath }}">
               <p class="herocontent mt-2">{{ substr(strip_tags($breakingPost->content), 0, 500) }}</p>
               </a>
           </div>
           @endforeach
           <div class="col-md-4">
            @if ($afterNavAd)
        <div class=" m-0 p-0 ">
            <h1 class="mt-3">हाम्रो बिज्ञापन राजनिती </h1>
            <a target="_blank" href="{{ $afterNavAd->url ?? '#' }}">
                <img src="{{ asset('uploads/images/ads/' . ($afterNavAd->image ?? 'default.jpg')) }}" alt="" class="imgsize rounded">
            </a>
        </div>
        @else
        <!-- Handle the case when no ad is found -->
        <p>No ad available.</p>
        @endif
           </div>
       </div>
    </div>
    </section>
    <div class="container">
        @if ($afterBreakingAd)
        <div class="top_ad p-0 m-0 py-2">
            <a target="_blank" href="{{ $afterBreakingAd->url ?? '#' }}">
                <img src="{{ asset('uploads/images/ads/' . ($afterBreakingAd->image ?? 'default.jpg')) }}" alt="">
            </a>
        </div>
        @else
        <!-- Handle the case when no ad is found -->
        <p>No ad available.</p>
        @endif
    </div>
    <section class="container">
    <div class="row">
    <div class="col-md-4">
                    <div class="col-md-12">
                    <img src="{{ asset("img/ed2.png") }}" alt="" class="col-12 rounded forimages">
                    <p class="post_title"> hjghjohkljujk</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-12">
                    <img src="{{ asset("img/ed3.png") }}" alt="" class="col-12 rounded forimages">
                    <p class="post_title"> hjghjohkljujk</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-12">
                    <img src="{{ asset("img/ed2.png") }}" alt="" class="col-12 rounded forimages">
                    <p class="post_title"> hjghjohkljujk</p>
                    </div>
                </div>
    </div>
    </section>