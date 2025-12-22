<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Qassirin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #fff;
            padding: 40px 35px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-box {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-box img {
            width: 85px;
        }

        .login-title {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 6px rgba(13,110,253,0.4);
        }

        .btn-login {
            background-color: #0d6efd;
            color: white;
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
            transition: 0.25s;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
            color: #fff;
        }

        .flash-message {
            background: #fdecea;
            border-left: 4px solid #d9534f;
            padding: 10px 12px;
            border-radius: 6px;
            color: #b52b27;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .error-message ul {
            padding-left: 20px;
            color: #d9534f;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="login-container">

        <div class="logo-box">
            <img src="<?php echo e(asset('images/qassirin_logo.png')); ?>" alt="Qassirin Logo">
        </div>

        <h2 class="login-title">Login Qassirin</h2>

        
        <?php if(session('error')): ?>
            <div class="flash-message">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        
        <?php if($errors->any()): ?>
            <div class="error-message">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($err); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('login')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?php echo e(old('email')); ?>"
                    required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required>
            </div>

            <button type="submit" class="btn btn-login w-100">
                Login
            </button>
        </form>
    </div>
</body>
</html>
<?php /**PATH D:\projek_pos\qassir_na\resources\views\auth\login.blade.php ENDPATH**/ ?>