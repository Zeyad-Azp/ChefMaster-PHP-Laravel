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
            <h1>Welcome Back</h1>
            <p>Sign in to your ChefMaster account</p>
        </div>

        
        <?php if(session('success')): ?>
            <div class="auth-alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        
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

        <form action="<?php echo e(route('login')); ?>" method="POST" class="auth-form-wrapper">
            <?php echo csrf_field(); ?>

            
            <div class="auth-form-group">
                <label for="loginEmail">Email Address</label>
                <input type="email" name="email" id="loginEmail" value="<?php echo e(old('email')); ?>"
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
                <label for="loginPassword">Password</label>
                <input type="password" name="password" id="loginPassword"
                       placeholder="Enter your password" autocomplete="current-password"
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

            
            <div class="auth-remember-row">
                <input type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                <label for="remember">Remember me</label>
            </div>

            
            <button type="submit" class="auth-submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Sign In
            </button>
        </form>

        
        <p class="auth-footer-text">
            Don't have an account?
            <a href="<?php echo e(route('register')); ?>">Create one free</a>
        </p>

    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ChefMaster-fixed\resources\views/auth/login.blade.php ENDPATH**/ ?>