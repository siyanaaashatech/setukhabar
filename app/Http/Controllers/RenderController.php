<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Post;
use App\Models\Display;
use App\Models\Gallery;
use App\Models\Section;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RenderController extends Controller

{ 
    private function processPosts($posts)
    {
        if (!$posts) return;
        foreach ($posts as $post) {
            $images = json_decode($post->image);
            $post->firstImagePath = isset($images[0]) ? asset('uploads/posts/' . $images[0]) : '';
            $post->truncatedTitle = Str::substr($post->title, 0, 200);
        }
        return $posts;
    }

    public function renderCategory($slug, $id, Request $request)
    {
        $decodedSlug = urldecode($slug);
        
        // Fetch ads
        $postadspop = Display::where('title', 'Post Front')->first();
        $postads = $postadspop ? $postadspop->getAds()->latest('id')->first() : null;
        $SportsAdSection = Display::where('title', 'Sports Section Right Side')->first();
        $sportsAd = $SportsAdSection ? $SportsAdSection->getAds()->latest('id')->first() : null;
        $afternavAdSection = Display::where('title', 'After Navbar Section')->first();
        $afterNavAd = $afternavAdSection ? $afternavAdSection->getAds()->latest('id')->first() : null;
        $afterMainNewsTitle = Display::where('title', 'After Main News Title')->first();
        $afterMainNewstitleAd = $afterMainNewsTitle ? $afterMainNewsTitle->getAds()->latest('id')->first() : null;
        $beforeWorldNews = Display::where('title', 'Before World News')->first();
        $beforeWorldNewsAd = $beforeWorldNews ? $beforeWorldNews->getAds()->latest('id')->first() : null;
        $afterMainPost = Display::where('title', 'After Main Posts')->first();
        $afterMainPostAd = $afterMainPost ? $afterMainPost->getAds()->latest('id')->first() : null;
        $afterBreakingNewsSection = Display::where('title', 'After Breaking News Section')->first();
        $afterBreakingAd = $afterBreakingNewsSection ? $afterBreakingNewsSection->getAds()->latest('id')->first() : null;

        $sitesetting = SiteSetting::first();
        $categories = Category::all();
        $category = Category::findOrFail($id);

        // Fetch and process posts
        $titlePosts = Post::with(['getCategories' => function ($query) {
            $query->latest();
        }])->whereHas('getCategories', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->orderBy('created_at', 'desc')->take(2)->get();
        $this->processPosts($titlePosts);

        $titlePostsId = $titlePosts->pluck('id')->toArray();

        $mainPosts = Post::with(['getCategories' => function ($query) {
            $query->latest();
        }])->whereHas('getCategories', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->whereNotIn('id', $titlePostsId)
          ->orderBy('created_at', 'desc')->take(2)->get();
        $this->processPosts($mainPosts);

        $mainPostsId = $mainPosts->pluck('id')->toArray();

        $posts = Post::with(['getCategories' => function ($query) {
            $query->latest();
        }])->whereHas('getCategories', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->whereNotIn('id', $mainPostsId)
          ->orderBy('created_at', 'desc')->take(6)->get();
        $this->processPosts($posts);

        $postsId = $posts->pluck('id')->toArray();

        $postsOne = Post::with('getCategories')
            ->whereHas('getCategories', function ($query) use ($id) {
                $query->where('category_id', $id);
            })->whereNotIn('id', $postsId)
            ->orderBy('created_at', 'desc')
            ->latest()
            ->paginate(12);
        $this->processPosts($postsOne);

        $coverimages = Post::with(['getCategories' => function ($query) {
            $query->latest();
        }])->whereHas('getCategories', function ($query) {
            $query->where('category_id', 3);
        })->orderBy('created_at', 'desc')->take(4)->get();
        $this->processPosts($coverimages);

        $mukhyasection = Section::where('title', 'Mukhya Samachar')->first();
        $mukhyaNews = Post::whereHas('getSections', function ($q) use ($mukhyasection) {
            $q->where('section_id', $mukhyasection->id);
        })->latest()->take(8)->get();
        $this->processPosts($mukhyaNews);

        $breakingsection = Section::where('title', 'Breaking News')->first();
        $breakingNews = Post::whereHas('getSections', function ($q) use ($breakingsection) {
            $q->where('section_id', $breakingsection->id);
        })->latest()->take(7)->get();
        $this->processPosts($breakingNews);

        // Process other sections
        $seventhRowOne = Post::with(['getCategories' => function ($query) {
            $query->latest();
        }])->whereHas('getCategories', function ($query) {
            $query->where('category_id', 1);
        })->orderBy('created_at', 'desc')->latest()->take(8)->get();
        $this->processPosts($seventhRowOne);

        $eighthRow = Post::with(['getCategories' => function ($query) {
            $query->latest();
        }])->whereHas('getCategories', function ($query) {
            $query->where('category_id', 4);
        })->orderBy('created_at', 'desc')->latest()->take(4)->get();
        $this->processPosts($eighthRow);

        $ninthColumnOne = Post::with(['getCategories' => function ($query) {
            $query->latest();
        }])->whereHas('getCategories', function ($query) {
            $query->where('category_id', 4);
        })->orderBy('created_at', 'desc')->latest()->take(6)->get();
        $this->processPosts($ninthColumnOne);

        $ninthColumnOneIds = $ninthColumnOne->pluck('id')->toArray();

        $ninthColumnTwo = Post::with(['getCategories' => function ($query) {
            $query->latest();
        }])->whereHas('getCategories', function ($query) {
            $query->whereIn('category_id', [4]);
        })->whereNotIn('id', $ninthColumnOneIds)
          ->orderBy('created_at', 'desc')->latest()
          ->take(2)->get();
        $this->processPosts($ninthColumnTwo);

        $ninthColumnTwoIds = $ninthColumnTwo->pluck('id')->toArray();
        $excludedIds = array_merge($ninthColumnOneIds, $ninthColumnTwoIds);

        $ninthColumnThree = Post::with(['getCategories' => function ($query) {
            $query->latest();
        }])->whereHas('getCategories', function ($query) {
            $query->where('category_id', [4]);
        })->whereNotIn('id', $excludedIds)
          ->orderBy('created_at', 'desc')
          ->latest()
          ->take(2)
          ->get();
        $this->processPosts($ninthColumnThree);

        $trendingPosts = Post::orderBy('views', 'desc')->take(4)->get();
        $this->processPosts($trendingPosts);

        $sharedPosts = Post::orderBy('shares', 'desc')->take(4)->get();
        $this->processPosts($sharedPosts);

        $relatedPosts = Post::orderByRaw('LENGTH(tags) - LENGTH(REPLACE(tags, ",", "")) + 1 DESC')
            ->limit(4)
            ->get();
        $this->processPosts($relatedPosts);

        $tagposts = Post::all();
        $uniqueTags = [];

        foreach ($tagposts as $post) {
            $tags = explode(',', $post->tags);
            $uniqueTags = array_merge($uniqueTags, $tags);
        }

        $uniqueTags = array_unique($uniqueTags);

        return view('portal.render_categories', compact(
            'sitesetting', 'categories', 'titlePosts', 'mainPosts', 'posts',
            'postsOne', 'coverimages', 'mukhyasection', 'mukhyaNews',
            'breakingsection', 'breakingNews', 'seventhRowOne', 'eighthRow',
            'ninthColumnOne', 'ninthColumnTwo', 'ninthColumnThree',
            'trendingPosts', 'sharedPosts', 'relatedPosts', 'category',
            'uniqueTags', 'afterNavAd', 'afterBreakingAd', 'sportsAd',
            'afterMainNewstitleAd', 'beforeWorldNewsAd', 'postads',
            'postadspop'
        ));
    }

    public function renderPost($slug, $id, Request $request)
    {
        $slug = $request->input('slug');
        $sitesetting = SiteSetting::first();
        $categories = Category::all();
        $posts = Post::all();
        $post = Post::with('getCategories')->findOrfail($id);
        $images = json_decode($post->image);
        $firstImagePath = isset($images[0]) ? asset('uploads/posts/' . $images[0]) : '';
        $post->firstImagePath = $firstImagePath;
        $post->increment('views');
        $mukhyasection = Section::where('title', 'Mukhya Samachar')->first();
        $mukhyaNews = Post::whereHas('getSections', function ($q) use ($mukhyasection) {
            $q->where('section_id', $mukhyasection->id);
        })->latest()->get()->take(8)->except($post->id);
        $afternavAdSection = Display::where('title', 'After Navbar Section')->first();
        $afterNavAd = $afternavAdSection ? $afternavAdSection->getAds()->latest('id')->first() : null;
        $bottomSection = Display::where('title', 'Bottom Section')->first();
        $bottomAd = $bottomSection ? $bottomSection->getAds()->latest('id')->first() : null;
        $afterStrangeWorldSection = Display::where('title', 'After Strange World Section')->first();
        $afterStrangeWorldAd = $afterStrangeWorldSection ? $afterStrangeWorldSection->getAds()->latest('id')->first() : null;
        $afterMainNewsTitle = Display::where('title', 'After Main News Title')->first();
        $afterMainNewstitleAd = $afterMainNewsTitle ? $afterMainNewsTitle->getAds()->latest('id')->first() : null;
        $afterPostTitle = Display::where('title', 'Under Single Post')->first();
        $afterPosttitleAd = $afterPostTitle ? $afterPostTitle->getAds()->latest('id')->first() : null;
        $postadspop = Display::where('title', 'Post Front')->first();
        $postads = $postadspop ? $postadspop->getAds()->latest('id')->first() : null;
        $strippedContent = preg_replace('/<p>(\s*<iframe[^>]*><\/iframe>\s*)<\/p>/', '$1', $post->content);
        $currentPostTags = $post->tags;
        $tagPosts = Post::where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        $similarPosts = Post::whereHas('getCategories', function ($query) use ($post) {
            $query->whereIn('category_id', $post->getCategories->pluck('id'));
        })
        ->where('id', '!=', $post->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
        $trendingPosts = Post::orderBy('views', 'desc')->take(4)->get();
        $this->processPosts($trendingPosts);
        $sharedPosts = Post::orderBy('shares', 'desc')->take(4)->get();
        $this->processPosts($sharedPosts);
        $relatedPosts = Post::whereHas('getCategories', function ($query) use ($post) {
            $query->whereIn('category_id', $post->getCategories->pluck('id'));
        })->where('id', '!=', $post->id)
          ->latest('updated_at')
          ->take(4)
          ->get();
        $this->processPosts($relatedPosts);
        $slug = $request->slug;
        $id = $request->id;
        $ogTitle = $post->title;
        $ogDescription = strip_tags($post->content);
        $ogImage = $firstImagePath;
        $ogUrl = route('post.render', ['slug' => $slug, 'id' => $id]);
        $shareComponent = \Share::page(
            $request->fullUrl(),
            $post->title
        )->facebook()
         ->twitter()
         ->linkedin()
         ->telegram()
         ->whatsapp()
         ->reddit();
        return view('portal.render_posts', [
            'post' => $post,
            'mukhyaNews' => $mukhyaNews,
            'sitesetting' => $sitesetting,
            'tagPosts' => $tagPosts,
            'categories' => $categories,
            'strippedContent' => $strippedContent,
            'similarPosts' => $similarPosts,
            'trendingPosts' => $trendingPosts,
            'sharedPosts' => $sharedPosts,
            'relatedPosts' => $relatedPosts,
            'afterNavAd' => $afterNavAd,
            'afterStrangeWorldAd' => $afterStrangeWorldAd,
            'afterMainNewstitleAd' => $afterMainNewstitleAd,
            'afterPosttitleAd' => $afterPosttitleAd,
            'bottomAd' => $bottomAd,
            'posts' => $posts,
            'ogTitle' => $ogTitle,
            'ogDescription' => $ogDescription,
            'ogImage' => $ogImage,
            'ogUrl' => $ogUrl,
            'shareComponent' => $shareComponent,
            'postads' => $postads,
        ]);
    }

    public function renderTags(Request $request)
    {
        $afternavAdSection = Display::where('title', 'After Navbar Section')->first();
        $afterNavAd = $afternavAdSection ? $afternavAdSection->getAds()->latest('id')->first() : null;
        $sitesetting = SiteSetting::first();
        $categories = Category::all();
        $tag = $request->input('tag');
        $posts = Post::where('tags', 'like', "%$tag%")->latest()->paginate(15);
        $uniqueTags = Post::pluck('tags')->flatMap(function ($tags) {
            return explode(',', $tags);
        })->unique();
        $trendingPosts = Post::orderBy('views', 'desc')->take(4)->get();
        $sharedPosts = Post::orderBy('shares', 'desc')->take(4)->get();
        $relatedPosts = Post::orderByRaw('LENGTH(tags) - LENGTH(REPLACE(tags, ",", "")) + 1 DESC')
            ->limit(4)
            ->get();

        return view('portal.render_tags', compact('sitesetting', 'categories', 'posts', 'uniqueTags', 'tag', 'trendingPosts', 'sharedPosts', 'relatedPosts', 'afterNavAd'));
    }

    public function loadMore(Request $request)
    {

        $slug = $request->input('slug');
        $id = $request->input('id');
        $page = $request->input('page');
        $limit = 3;
        $post = Post::find($id);
        $tags = $post->tags; // Assuming the tags are stored as a comma-separated string
        $offset = ($page - 1) * $limit;   //query will skip the first  records and start fetching the next records.
        $relatedPosts = Post::where('id', '!=', $id)
            ->where(function ($query) use ($tags) {
                $query->where('tags', 'LIKE', '%' . $tags . '%')
                    ->orWhere('tags', 'LIKE', '%' . $tags . ',%')
                    ->orWhere('tags', 'LIKE', '%,' . $tags . ',%')
                    ->orWhere('tags', 'LIKE', '%,' . $tags);
            })
            ->offset($offset)
            ->limit($limit)
            ->get();

        // Return the partial view with the loaded posts
        return view('portal.render_posts_partial', ['relatedPosts' => $relatedPosts]);
    }


    public function renderSearch(Request $request)
    {

        $adspop = Ad::latest()->first();
        $afternavAdSection = Display::where('title', 'After Navbar Section')->first();
        $afterNavAd = $afternavAdSection ? $afternavAdSection->getAds()->latest('id')->first() : null;

        $afterMainNewsTitle = Display::where('title', 'After Main News Title')->first();
        $afterMainNewstitleAd = $afterMainNewsTitle ? $afterMainNewsTitle->getAds()->latest('id')->first() : null;

        $bottomSection = Display::where('title', 'Bottom Section')->first();
        $bottomAd = $bottomSection ? $bottomSection->getAds()->latest('id')->first() : null;

        $this->validate($request, [
            'input' => 'required'
        ]);
        $trendingPosts = Post::orderBy('views', 'desc')->take(4)->get();
        $this->processPosts($trendingPosts);
        $sharedPosts = Post::orderBy('shares', 'desc')->take(4)->get();
        $this->processPosts($sharedPosts);

        $relatedPosts = Post::orderByRaw('LENGTH(tags) - LENGTH(REPLACE(tags, ",", "")) + 1 DESC')
            ->limit(4)
            ->get();
        $this->processPosts($relatedPosts);
        $id = $request->input('post_id');

        // Retrieve similar posts based on categories
        $similarPosts = Post::whereHas('getCategories', function ($query) use ($id) {
            $query->whereIn('id', function ($subQuery) use ($id) {
                $subQuery->select('category_id')
                    ->from('posts_categories')
                    ->where('post_id', $id);
            });
        })
            ->where('id', '!=', $id)
            ->limit(7)
            ->get(); 

        $currentPostTags = $request->input('post_tags');

        // Fetch posts with matching tags
        $tagPosts = Post::where('id', '!=', $id)
            ->where(function ($query) use ($currentPostTags) {
                $query->where('tags', 'LIKE', '%' . $currentPostTags . '%')
                    ->orWhere('tags', 'LIKE', '%,' . $currentPostTags . ',%')
                    ->orWhere('tags', 'LIKE', '%,' . $currentPostTags)
                    ->orWhere('tags', 'LIKE', $currentPostTags . ',%');
            })
            ->limit(5)
            ->get();


        $mukhyasection = Section::where('title', 'Mukhya Samachar')->first();
        $mukhyaNews = Post::whereHas('getSections', function ($q) use ($mukhyasection) {
            $q->where('section_id', $mukhyasection->id);
        })->latest()->get()->take(5);

        $posts = Post::where('title', 'like', '%' . "$request->input" . '%')
            ->orWhere('description', 'like', '%' . "$request->input" . '%')
            ->orWhere('content', 'like', '%' . "$request->input" . '%')
            ->paginate(5);
        $this->processPosts($posts);
        $display = Display::where('title', 'Sidebar Ads')->first();
        $sidebarAds = $display ? $display->getAds()->latest('id')->first() : null;
        $sitesetting = SiteSetting::first();
        $categories = Category::all();
        $uniqueTags = [];

        foreach ($posts as $post) {
            $tags = explode(',', $post->tags);
            $uniqueTags = array_merge($uniqueTags, $tags);
        }

        $uniqueTags = array_unique($uniqueTags);
        return view('portal.render-search', [
            'sitesetting' => $sitesetting,
            'categories' => $categories,
            'posts' => $posts,
            'mukhyasection' => $mukhyasection,
            'mukhyaNews' => $mukhyaNews,
            'similarPosts' => $similarPosts,
            'tagPosts' => $tagPosts,
            'trendingPosts' => $trendingPosts,
            'sharedPosts' => $sharedPosts,
            'relatedPosts' => $relatedPosts,
            'afterNavAd' => $afterNavAd,
            'bottomAd' => $bottomAd,
            'afterMainNewstitleAd' => $afterMainNewstitleAd,
            'adspop' => $adspop,

        ]);
    }



    // public function renderNews()
    // {
    //     $sitesetting = SiteSetting::first();
    //     $collectedPosts = Post::with('getCategories')->latest()->get()->take(10);
    //     $navAdSection = Display::where('title', 'Below Title')->first();
    //     $navAd = $navAdSection ? $navAdSection->getAds()->latest('id')->first() : null;
    //     return view('portal.render_news', [
    //         'sitesetting' => $sitesetting,
    //         'collectedPosts' => $collectedPosts,
    //         'navAd' => $navAd,

    //     ]);
    // }

    // public function renderSports()
    // {
    //     # code...
    //     $sitesetting = SiteSetting::first();
    //     $sportsSection = Section::where('title', 'Sports News')->first();
    //     $sportsNews = Post::whereHas('getSections', function ($q) use ($sportsSection) {
    //         $q->where('section_id', $sportsSection->id);
    //     })->latest()->get()->take(10);
    //     $navAdSection = Display::where('title', 'Below Title')->first();
    //     $navAd = $navAdSection ? $navAdSection->getAds()->latest('id')->first() : null;

    //     return view('portal.render_sports', [
    //         'sportsSection' => $sportsSection,
    //         'sportsNews' => $sportsNews,
    //         'sitesetting' => $sitesetting,
    //         'navAd' => $navAd,

    //     ]);
    // }

    // public function renderBichar()
    // {
    //     $sitesetting = SiteSetting::first();
    //     $bichaarsection = Section::where('title', 'विचार/ब्लग')->first();
    //     $BichaarNews = Post::whereHas('getSections', function ($q) use ($bichaarsection) {
    //         $q->where('section_id', $bichaarsection->id);
    //     })->latest()->get()->take(4);
    //     $navAdSection = Display::where('title', 'Below Title')->first();
    //     $navAd = $navAdSection ? $navAdSection->getAds()->latest('id')->first() : null;

    //     return view('portal.render_bichar', [
    //         'bichaarsection' => $bichaarsection,
    //         'BichaarNews' => $BichaarNews,
    //         'sitesetting' => $sitesetting,
    //         'navAd' => $navAd,
    //     ]);
    // }

    // public function renderWorld()
    // { 
    //     $sitesetting = SiteSetting::first();
    //     $worldSection = Section::where('title', 'World News')->first();
    //     $worldNews = Post::whereHas('getSections', function ($q) use ($worldSection) {
    //         $q->where('section_id', $worldSection->id);
    //     })->latest()->get()->take(10);
    //     $navAdSection = Display::where('title', 'Below Title')->first();
    //     $navAd = $navAdSection ? $navAdSection->getAds()->latest('id')->first() : null;

    //     return view('portal.render_world', [
    //         'worldSection' => $worldSection,
    //         'worldNews' => $worldNews,
    //         'sitesetting' => $sitesetting,
    //         'navAd' => $navAd,
    //     ]);
    // }

    // public function renderRomanchakNews()
    // {
    //     $sitesetting = SiteSetting::first();
    //     $romanchaksection = Section::where('title', 'Romanchak News')->first();
    //     $romanchaknews = Post::whereHas('getSections', function ($q) use ($romanchaksection) {
    //         $q->where('section_id', $romanchaksection->id);
    //     })->latest()->get()->take(10);
    //     $navAdSection = Display::where('title', 'Below Title')->first();
    //     $navAd = $navAdSection ? $navAdSection->getAds()->latest('id')->first() : null;

    //     return view('portal.render_romanchak_news', [
    //         'romanchaksection' => $romanchaksection,
    //         'romanchaknews' => $romanchaknews,
    //         'sitesetting' => $sitesetting,
    //         'navAd' => $navAd,
    //     ]);
    // }

    // public function renderArtha()
    // {
    //     # code...
    //     $sitesetting = SiteSetting::first();
    //     $arthaSection = Section::where('title', 'Artha Byapar News')->first();
    //     $arthaNews = Post::whereHas('getSections', function ($q) use ($arthaSection) {
    //         $q->where('section_id', $arthaSection->id);
    //     })->latest()->get()->take(5);
    //     $navAdSection = Display::where('title', 'Below Title')->first();
    //     $navAd = $navAdSection ? $navAdSection->getAds()->latest('id')->first() : null;

    //     return view('portal.render_artha_news', [
    //         'arthaSection' => $arthaSection,
    //         'arthaNews' => $arthaNews,
    //         'sitesetting' => $sitesetting,
    //         'navAd' => $navAd,
    //     ]);
    // }

    // public function renderInformationTechnology()
    // {
    //     # code...
    //     $sitesetting = SiteSetting::first();
    //     $itsection = Section::where('title', 'Information Technology News')->first();
    //     $itnews = Post::whereHas('getSections', function ($q) use ($itsection) {
    //         $q->where('section_id', $itsection->id);
    //     })->latest()->get()->take(4);
    //     $navAdSection = Display::where('title', 'Below Title')->first();
    //     $navAd = $navAdSection ? $navAdSection->getAds()->latest('id')->first() : null;

    //     return view('portal.render_information_technology', [
    //         'itsection' => $itsection,
    //         'itnews' => $itnews,
    //         'sitesetting' => $sitesetting,
    //         'navAd' => $navAd,
    //     ]);
    // }

    public function photofeature($id)
    {
        $sitesetting = SiteSetting::first();
        $photofeature = Post::find($id);
        $categories = Category::all(); 

        $afternavAdSection = Display::where('title', 'After Navbar Section')->first();
        $afterNavAd = Ad::whereHas('getDisplays', function ($q) use ($afternavAdSection) {
            $q->where('display_id', $afternavAdSection->id);
        })->latest()->first();
        $posts = Post::all();
        $post = Post::with('getCategories')->findOrfail($id);
        $mukhyasection = Section::where('title', 'Mukhya Samachar')->first();
        $mukhyasection = Section::where('title', 'Mukhya Samachar')->first();
        $mukhyaNews = $mukhyasection ? $mukhyasection->getAds()->latest('id')->first() : null;
        $afterMainNewsTitle = Display::where('title', 'After Main News Title')->first();
        $afterMainNewstitleAd = $afterMainNewsTitle ? $afterMainNewsTitle->getAds()->latest('id')->first() : null;
        $similarPosts = Post::whereHas('getCategories', function ($query) use ($id) {
            $query->whereIn('id', function ($subQuery) use ($id) {
                $subQuery->select('category_id')
                    ->from('posts_categories')
                    ->where('post_id', $id);
            });
        })
            ->where('id', '!=', $id)
            ->limit(7)
            ->get();
        $currentPostTags = $post->tags;

        $tagPosts = Post::where('id', '!=', $id)
            ->where(function ($query) use ($currentPostTags) {
                $query->where('tags', 'LIKE', '%' . $currentPostTags . '%')
                    ->orWhere('tags', 'LIKE', '%,' . $currentPostTags . ',%')
                    ->orWhere('tags', 'LIKE', '%,' . $currentPostTags)
                    ->orWhere('tags', 'LIKE', $currentPostTags . ',%');
            })
            ->limit(7)
            ->get();

        $bottomSection = Display::where('title', 'Bottom Section')->first();
        $bottomAd = $bottomSection ? $bottomSection->getAds()->latest('id')->first() : null;
        $trendingPosts = Post::orderBy('views', 'desc')->take(4)->get();
        $sharedPosts = Post::orderBy('shares', 'desc')->take(4)->get();
        $relatedPosts = Post::orderByRaw('LENGTH(tags) - LENGTH(REPLACE(tags, ",", "")) + 1 DESC')
            ->limit(4)
            ->get();

        return view('portal.render_photo_feature', [

            'sitesetting' => $sitesetting,
            'photofeature' => $photofeature,
            'categories' => $categories,
            'afterNavAd' => $afterNavAd,
            'posts' => $posts,
            'post' => $post,
            'mukhyaNews' => $mukhyaNews,
            'afterMainNewstitleAd' => $afterMainNewstitleAd,
            'similarPosts' => $similarPosts,
            'tagPosts' => $tagPosts,
            'bottomAd' => $bottomAd,
            'trendingPosts' => $trendingPosts,
            'sharedPosts' => $sharedPosts,
            'relatedPosts' => $relatedPosts,

        ]);
    }

    public function khelkudIndex()
{
    // Logic for 'Khelkud' category
    $category = Category::where('slug', 'khelkud')->firstOrFail();
    $posts = Post::whereHas('getCategories', function ($query) use ($category) {
        $query->where('category_id', $category->id);
    })->latest()->paginate(12);

    return view('portal.render_khelkud', compact('category', 'posts'));
}

public function swasthaIndex()
{
    // Logic for 'Swastha' category
    $category = Category::where('slug', 'swastha')->firstOrFail();
    $posts = Post::whereHas('getCategories', function ($query) use ($category) {
        $query->where('category_id', $category->id);
    })->latest()->paginate(12);

    return view('portal.render_swasthya', compact('category', 'posts'));
}

public function rajnitiIndex()
{
    // Logic for 'Rajniti' category
    $category = Category::where('slug', 'rajniti')->firstOrFail();
    $posts = Post::whereHas('getCategories', function ($query) use ($category) {
        $query->where('category_id', $category->id);
    })->latest()->paginate(12);

    return view('portal.render_rajniti', compact('category', 'posts'));
}

public function arthaIndex()
{
    // Logic for 'Artha' category
    $category = Category::where('slug', 'artha')->firstOrFail();
    $posts = Post::whereHas('getCategories', function ($query) use ($category) {
        $query->where('category_id', $category->id);
    })->latest()->paginate(12);

    return view('portal.render_artha', compact('category', 'posts'));
}

    
}
