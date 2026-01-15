

<?php $__env->startSection('title', 'Donor Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <?php echo e(session('warning')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <h4 class="mb-4">Welcome, <?php echo e(auth()->user()->name); ?>!</h4>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?php echo e($stats['total_pledges']); ?></h3>
                        <span class="text-muted">My Pledges</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?php echo e($stats['verified_count']); ?></h3>
                        <span class="text-muted">Verified</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?php echo e($stats['pending_count']); ?></h3>
                        <span class="text-muted">Pending</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?php echo e($stats['families_helped'] ?? 0); ?></h3>
                        <span class="text-muted">Families Helped</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Drives -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Active Donation Drives</h5>
            <a href="<?php echo e(route('donor.map')); ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-geo-alt me-1"></i>View on Map
            </a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <?php $__empty_1 = true; $__currentLoopData = $activeDrives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $drive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo e($drive->name); ?></h5>
                                <p class="card-text text-muted small"><?php echo e(Str::limit($drive->description, 100)); ?></p>
                                
                                <div class="mb-3">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" style="width: <?php echo e($drive->progress_percentage); ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?php echo e($drive->progress_percentage); ?>% of target reached</small>
                                </div>
                                
                                <?php if($drive->address): ?>
                                    <p class="small mb-2">
                                        <i class="bi bi-geo-alt text-muted me-1"></i><?php echo e(Str::limit($drive->address, 40)); ?>

                                    </p>
                                <?php endif; ?>
                                
                                <p class="small mb-3">
                                    <i class="bi bi-calendar text-muted me-1"></i>Ends <?php echo e($drive->end_date->format('M d, Y')); ?>

                                </p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="<?php echo e(route('donor.pledges.create', ['drive_id' => $drive->id])); ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-heart me-2"></i>Pledge to this Drive
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mb-0">No active drives at the moment. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <?php echo e($activeDrives->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\tupv\relief\resources\views/donor/dashboard.blade.php ENDPATH**/ ?>