<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Relief')); ?> - <?php echo $__env->yieldContent('title', 'Donation Drive Platform'); ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- Leaflet CSS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root {
            --relief-primary: #2563eb;
            --relief-secondary: #64748b;
            --relief-success: #16a34a;
            --relief-danger: #dc2626;
            --relief-warning: #d97706;
            --relief-info: #0891b2;
            --relief-purple: #7c3aed;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .btn-primary {
            background-color: var(--relief-primary);
            border-color: var(--relief-primary);
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 0.75rem;
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .stat-card {
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .notification-badge {
            position: relative;
        }
        
        .notification-badge .badge {
            position: absolute;
            top: -5px;
            right: -5px;
        }
        
        .status-pending { color: var(--relief-warning); }
        .status-verified { color: var(--relief-success); }
        .status-expired { color: var(--relief-danger); }
        .status-distributed { color: var(--relief-purple); }
        
        .bg-status-pending { background-color: #fef3c7; }
        .bg-status-verified { background-color: #dcfce7; }
        .bg-status-expired { background-color: #fee2e2; }
        .bg-status-distributed { background-color: #f3e8ff; }
        
        #map {
            height: 400px;
            border-radius: 0.75rem;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #fff;
            border-right: 1px solid #e2e8f0;
        }
        
        .sidebar .nav-link {
            color: #475569;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin: 0.125rem 0;
        }
        
        .sidebar .nav-link:hover {
            background-color: #f1f5f9;
            color: var(--relief-primary);
        }
        
        .sidebar .nav-link.active {
            background-color: #eff6ff;
            color: var(--relief-primary);
            font-weight: 500;
        }
        
        .sidebar .nav-link i {
            width: 24px;
        }
        
        .verification-banner {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid var(--relief-warning);
        }
        
        <?php echo $__env->yieldContent('styles'); ?>
    </style>
</head>
<body>
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\tupv\relief\resources\views/layouts/app.blade.php ENDPATH**/ ?>