

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

        <p class="footer-copy">&copy; <?php echo e(date('Y')); ?> ChefMaster. All rights reserved.</p>

        <div class="footer-links">
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('recipes.create')); ?>">Add Recipe</a>
            <?php endif; ?>
            <a href="<?php echo e(url('/')); ?>#sectionDiscover" onclick="handleNavClick(event, 'sectionDiscover')">Discover</a>
            <a href="<?php echo e(url('/')); ?>#sectionFavorites" onclick="handleNavClick(event, 'sectionFavorites')">Favourites</a>
        </div>

    </div>
</footer>


<script src="<?php echo e(asset('js/API_Ops.js')); ?>"></script>
<script src="<?php echo e(asset('js/script.js')); ?>"></script>
<?php /**PATH D:\ChefMaster-fixed\resources\views/layouts/footer.blade.php ENDPATH**/ ?>