<header>
    <div class="header-container">
        <h1><a href="{{ url('/catalog') }}">Clubhouse</a></h1>

        <!-- Container for Platform Links and Search Icon -->
        <div class="platform-search-container">
            <!-- Navigation Section -->
            <nav class="platform-links">
                <ul class="nav-list">
                    <li><a href="{{ route('platform.filter', 'PC') }}">PC</a></li>
                    <li><a href="{{ route('platform.filter', 'Xbox') }}">Xbox</a></li>
                    <li><a href="{{ route('platform.filter', 'Playstation') }}">Playstation</a></li>
                    <li><a href="{{ route('platform.filter', 'Switch') }}">Switch</a></li>
                    <!-- Search Icon Next to Platforms -->
                    <li class="search-icon">
                        <img 
                            src="{{ asset('images/search-icon.png') }}" 
                            alt="Search" 
                            style="cursor: pointer; width: 30px; height: 30px;"
                            onclick="toggleSearch()"
                        >
                    </li>
                </ul>
            </nav>

            <!-- Search Form (Initially Hidden) -->
            <div id="search-bar" class="search-toggle">
                <form method="get" action="{{ url('/catalog/search') }}" class="search-form">
                    <input 
                        class="search-input" 
                        type="text" 
                        name="search" 
                        placeholder="Search..." 
                        value="{{ request()->get('search', '') }}"
                    >
                    <button type="submit" class="search-btn">
                        <img 
                            src="{{ asset('images/search-icon.png') }}" 
                            alt="Search"
                            style="width: 20px; height: 20px;"
                        >
                    </button>
                    <!-- Close Button Next to Search Button -->
                    <button type="button" class="close-btn" onclick="closeSearch()">
                        &times; <!-- HTML entity for "X" symbol -->
                    </button>
                </form>
            </div>
        </div>

        <!-- Authenticated User Links -->
        @auth
            <x-notifications-modal />
            <!-- Shopping Cart Icon (Styled as a Button) -->
            <a href="{{ route('shopping-cart.show', ['id' => Auth::id()]) }}" class="shopping-cart-icon">
                <img 
                    src="{{ asset('images/shopping-cart-icon.png') }}" 
                    alt="Shopping Cart" 
                    style="width: 50px; height: 50px;">
            </a>

            <!-- Admin Button for Admin Users Only -->
            @if (Auth::user()->is_admin)
                <a class="button admin-button" href="{{ url('/admin/users') }}">Admin</a>
            @endif

            <!-- Logout and Profile Links -->
            <a class="button" href="{{ url('/logout') }}"> Logout </a> 
            <a href="{{ url('/profile/' . Auth::id()) }}">{{ Auth::user()->name }}</a>
        @endauth

        <!-- Guest Links -->
        @guest
            <a class="button" href="{{ url('/login') }}">Login</a>
        @endguest
    </div>
</header>
