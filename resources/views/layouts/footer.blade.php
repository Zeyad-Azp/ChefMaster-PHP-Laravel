{{-- ChefMaster Footer --}}

<footer class="site-footer" role="contentinfo">
    <div class="footer-inner">

        <div class="footer-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 style="display:inline;vertical-align:middle;margin-right:6px;color:var(--accent)">
                <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                <line x1="6" x2="18" y1="17" y2="17"/>
            </svg>
            ChefMaster
        </div>

        <p class="footer-copy">&copy; {{ date('Y') }} ChefMaster. All rights reserved.</p>

        <div class="footer-links">
<<<<<<< HEAD
            @auth
                <a href="{{ route('recipes.create') }}">Add Recipe</a>
            @endauth
            <a href="{{ url('/') }}#sectionDiscover" onclick="handleNavClick(event, 'sectionDiscover')">Discover</a>
            <a href="{{ url('/') }}#sectionFavorites" onclick="handleNavClick(event, 'sectionFavorites')">Favourites</a>
=======
            <a href="#" onclick="openAddRecipeModal();return false;">Add Recipe</a>
            <a href="#" onclick="scrollToSection('sectionDiscover');return false;">Discover</a>
            <a href="#" onclick="scrollToSection('sectionFavorites');return false;">Favourites</a>
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
        </div>

    </div>
</footer>

{{-- JavaScript --}}
<script src="{{ asset('js/API_Ops.js') }}"></script>
<<<<<<< HEAD
<script src="{{ asset('js/script.js') }}"></script>
=======
<script src="{{ asset('js/script.js') }}"></script>
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
