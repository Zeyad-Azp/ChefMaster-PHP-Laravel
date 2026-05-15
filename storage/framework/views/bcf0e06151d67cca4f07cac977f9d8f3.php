<?php $__env->startSection('content'); ?>
<main class="app-main auth-page" role="main">
    <div class="auth-card">

        
        <div class="auth-header">
            <div class="auth-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                    <line x1="6" x2="18" y1="17" y2="17"/>
                </svg>
            </div>
            <h1>Join ChefMaster</h1>
            <p>Create your free account and start cooking</p>
        </div>

        
        <?php if($errors->any()): ?>
            <div class="auth-alert-error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('register')); ?>" method="POST" enctype="multipart/form-data" class="auth-form-wrapper">
            <?php echo csrf_field(); ?>

            
            <div class="avatar-upload-group">
                <label class="avatar-label-text">Profile Photo</label>
                <div class="avatar-upload-wrap">
                    <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp">
                    <div class="avatar-preview" id="avatarPreview">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div class="avatar-overlay">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                    </div>
                </div>
                <span class="avatar-hint">JPG, PNG or WebP — max 2 MB</span>
                <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="auth-field-error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="auth-form-group">
                <label for="regFullname">Full Name <span class="required">*</span></label>
                <input type="text" name="fullname" id="regFullname" value="<?php echo e(old('fullname')); ?>"
                       placeholder="e.g. Sarah Johnson" autocomplete="name"
                       class="auth-input <?php echo e($errors->has('fullname') ? 'input-error' : ''); ?>">
                <?php $__errorArgs = ['fullname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="auth-field-error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="auth-form-group">
                <label for="regEmail">Email Address <span class="required">*</span></label>
                <input type="email" name="email" id="regEmail" value="<?php echo e(old('email')); ?>"
                       placeholder="you@example.com" autocomplete="email"
                       class="auth-input <?php echo e($errors->has('email') ? 'input-error' : ''); ?>">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="auth-field-error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="auth-form-group">
                <label for="regPassword">Password <span class="required">*</span></label>
                <input type="password" name="password" id="regPassword"
                       placeholder="Min. 8 characters" autocomplete="new-password"
                       class="auth-input <?php echo e($errors->has('password') ? 'input-error' : ''); ?>">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="auth-field-error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="auth-form-group" style="margin-bottom:28px;">
                <label for="regPasswordConfirm">Confirm Password <span class="required">*</span></label>
                <input type="password" name="password_confirmation" id="regPasswordConfirm"
                       placeholder="Repeat your password" autocomplete="new-password"
                       class="auth-input">
            </div>

            
            <button type="submit" class="auth-submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Create Account
            </button>
        </form>

        
        <p class="auth-footer-text">
            Already have an account?
            <a href="<?php echo e(route('login')); ?>">Sign in</a>
        </p>

    </div>
</main>

<script>
    // Avatar preview on file select
    document.getElementById('avatarInput')?.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) { alert('Image must be under 2 MB.'); this.value = ''; return; }
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatarPreview').innerHTML =
                `<img src="${e.target.result}" alt="Avatar preview">`;
        };
        reader.readAsDataURL(file);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ChefMaster-fixed\resources\views/auth/register.blade.php ENDPATH**/ ?>