<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Teacher Dashboard'); ?> - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #012970;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
        }

        .profile {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .profile h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .profile p {
            font-size: 14px;
            color: #bbb;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            margin-bottom: 10px;
        }

        .menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .menu a:hover, .menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Profile Picture">
            <h2><?php echo e(Auth::guard('teacher')->user()->name); ?></h2>
            <p>Teacher</p>
        </div>
        <ul class="menu">
            <li>
                <a href="<?php echo e(route('teacher.dashboard')); ?>" class="<?php echo e(request()->routeIs('teacher.dashboard') ? 'active' : ''); ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('teacher.classlist')); ?>" class="<?php echo e(request()->routeIs('teacher.classlist') ? 'active' : ''); ?>">
                    <i class="fas fa-users"></i> Class List
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('teacher.subjects')); ?>" class="<?php echo e(request()->routeIs('teacher.subjects') ? 'active' : ''); ?>">
                    <i class="fas fa-book"></i> Subjects
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('teacher.grades')); ?>" class="<?php echo e(request()->routeIs('teacher.grades') ? 'active' : ''); ?>">
                    <i class="fas fa-chart-bar"></i> Grades
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('teacher.profile')); ?>" class="<?php echo e(request()->routeIs('teacher.profile') ? 'active' : ''); ?>">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
            <li>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin: 0;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" style="background: none; border: none; width: 100%; text-align: left; color: white; padding: 10px; cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html> <?php /**PATH H:\Test - Copy\resources\views/layouts/teacher.blade.php ENDPATH**/ ?>