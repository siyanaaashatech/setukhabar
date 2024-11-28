<nav class="navbar navbar-expand-lg navbar-light  sticky-top">
    <div class="container">
        <!-- Hamburger button for smaller screens -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-icon">
                <i class="fas fa-bars"></i>
            </span>
        </button>
        <!-- Navbar items -->
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <!-- Home Page Link -->
                <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('index') }}">होमपेज</a>
                </li>
                <!-- Main Categories and Anya Dropdown -->
                @foreach($categories as $category)
                    @if(!in_array($category->title, ['विचार', 'मनाेरंजन', 'अनौठो संसार', 'फोटो फिचर']))
                        <li class="nav-item @if(request()->is('category/' . $category->slug . '/' . $category->id)) active @endif">
                            <a class="nav-link" href="{{ route('category.render',['slug' => urlencode($category->slug), 'id' => $category->id]) }}"
                               onclick="markNavItemActive(this)">{{ $category->title }}</a>
                        </li>
                    @endif
                @endforeach
                <!-- Anya Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="anyaDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        अन्य
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="anyaDropdown">
                        @foreach($categories as $category)
                            @if(in_array($category->title, ['विचार', 'मनाेरंजन', 'अनौठो संसार', 'फोटो फिचर']))
                                <li>
                                    <a class="dropdown-item @if(request()->is('category/' . $category->slug . '/' . $category->id)) active @endif"
                                       href="{{ route('category.render',['slug' => urlencode($category->slug), 'id' => $category->id]) }}"
                                       onclick="markNavItemActive(this)">{{ $category->title }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            </ul>
            <!-- Search bar -->
            <div class="search-container">
            <form action="{{ route('post.search') }}" method="GET">
                @csrf
                <input type="text" name="input" placeholder="Search..." class="rounded" />
                <button type="submit" class="btn btn-dark">Search</button>
            </form>
        </div>
        </div>
    </div>
</nav>
<script>
    function markNavItemActive(element) {
        var navItems = document.getElementsByClassName('nav-item');
        for (var i = 0; i < navItems.length; i++) {
            navItems[i].classList.remove('active');
        }
        element.parentNode.classList.add('active');
    }
</script>