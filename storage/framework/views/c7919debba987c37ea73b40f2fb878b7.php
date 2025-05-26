<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CNHS Registrar Portal">
    <meta name="theme-color" content="#0d47a1">
    <link rel="icon" href="<?php echo e(asset('images/log.png')); ?>" type="image/png">
    <title><?php echo $__env->yieldContent('title'); ?> - CNHS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body { height: 100%; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fc; color: #333; min-height: 100vh; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: #fff; padding: 1rem 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.07); position: fixed; width: 100%; z-index: 1000; height: 70px; top: 0; left: 0; }
        .header-left { display: flex; align-items: center; gap: 1rem; }
        .logo { width: 40px; height: 40px; object-fit: contain; }
        .header h1 { font-size: 1.5rem; color: #012970; margin: 0; }
        .header-right { display: flex; align-items: center; gap: 2rem; }
        .notification-bell { position: relative; cursor: pointer; }
        .notification-badge { position: absolute; top: -8px; right: -8px; background-color: #dc3545; color: white; border-radius: 50%; padding: 0.2rem 0.5rem; font-size: 0.75rem; }
        .user-profile { display: flex; align-items: center; gap: 1rem; }
        .header-profile-pic { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .user-name { color: #012970; font-weight: 500; }
        .container-main { display: flex; margin-top: 70px; min-height: calc(100vh - 70px); }
        .sidebar { width: 250px; background-color: #012970; color: white; padding: 20px; position: fixed; height: 100vh; top: 70px; left: 0; overflow-y: auto; z-index: 900; }
        .profile { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .profile-pic { width: 80px; height: 80px; border-radius: 50%; margin-bottom: 10px; object-fit: cover; }
        .name { font-size: 18px; margin-bottom: 5px; color: white; }
        .role { font-size: 14px; color: #bbb; }
        .menu { list-style: none; padding: 0; }
        .menu li { margin-bottom: 10px; }
        .menu a { color: white; text-decoration: none; display: block; padding: 10px; border-radius: 5px; transition: background-color 0.3s; }
        .menu a:hover, .menu a.active { background-color: rgba(255, 255, 255, 0.1); }
        .menu i { margin-right: 10px; width: 20px; text-align: center; }
        .main-content { flex-grow: 1; margin-left: 250px; padding: 2rem; background: #f8f9fc; min-height: calc(100vh - 70px); }
        @media (max-width: 991px) { .sidebar { display: none; } .main-content { margin-left: 0; } }
        @media (max-width: 768px) { .header { flex-direction: column; height: auto; padding: 1rem; } .header-left h1 { font-size: 1.1rem; } .main-content { padding: 1rem; } }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <img src="<?php echo e(asset('images/log.png')); ?>" alt="CNHS Logo" class="logo">
            <h1>CNHS Registrar Portal</h1>
        </div>
        <div class="header-right">
            <div class="notification-bell">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>
            <div class="dropdown user-profile">
                <a href="#" class="dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration:none;">
                    <img src="<?php echo e(auth()->guard('registrar')->user()->profile_picture ? asset('storage/' . auth()->guard('registrar')->user()->profile_picture) : asset('images/photo.jpg')); ?>" class="header-profile-pic" alt="Profile Picture">
                    <span class="user-name ms-2"><?php echo e(auth()->guard('registrar')->user()->first_name); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li>
                        <form method="POST" action="<?php echo e(route('registrar.logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Sidebar and Main Content -->
    <div class="container-main">
        <div class="sidebar">
            <div class="profile">
                <img src="<?php echo e(auth()->guard('registrar')->user()->profile_picture ? asset('storage/' . auth()->guard('registrar')->user()->profile_picture) : asset('images/logo.png')); ?>" alt="Profile Picture" class="profile-pic">
                <h2><?php echo e(auth()->guard('registrar')->user()->first_name); ?></h2>
                <p>Registrar</p>
            </div>
            <ul class="menu">
                <li>
                    <a href="<?php echo e(route('registrar.dashboard')); ?>" class="<?php echo e(request()->routeIs('registrar.dashboard') ? 'active' : ''); ?>">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('registrar.students.index')); ?>" class="<?php echo e(request()->routeIs('registrar.students*') ? 'active' : ''); ?>">
                        <i class="fas fa-user-graduate"></i> Student Records
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('registrar.subjects')); ?>" class="<?php echo e(request()->routeIs('registrar.subjects') ? 'active' : ''); ?>">
                        <i class="fas fa-book"></i> Subjects
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('registrar.subject-offerings.index')); ?>" class="<?php echo e(request()->routeIs('registrar.subject-offerings*') ? 'active' : ''); ?>">
                        <i class="fas fa-calendar-alt"></i> Subject Offerings
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('registrar.profile')); ?>" class="<?php echo e(request()->routeIs('registrar.profile') ? 'active' : ''); ?>">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </li>
                <li>
                    <form method="POST" action="<?php echo e(route('registrar.logout')); ?>" style="margin: 0;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" style="background: none; border: none; width: 100%; text-align: left; color: white; padding: 10px; cursor: pointer;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        <main class="main-content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH H:\Test - Copy\resources\views/layouts/registrar.blade.php ENDPATH**/ ?>