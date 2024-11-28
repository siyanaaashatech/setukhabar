<section class="container">
    <div class="row">
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