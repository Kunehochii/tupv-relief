

<?php $__env->startSection('title', 'NGO Dashboard'); ?>

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
    
    <!-- Verification Banner -->
    <?php if(auth()->user()->isPending()): ?>
        <div class="verification-banner rounded p-3 mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-hourglass-split fs-4 me-3 text-warning"></i>
                <div>
                    <h6 class="mb-0">Account Pending Verification</h6>
                    <small class="text-muted">Your certificate is being reviewed. Some features are limited until verification.</small>
                </div>
            </div>
        </div>
    <?php elseif(auth()->user()->isRejected()): ?>
        <div class="alert alert-danger mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-x-circle fs-4 me-3"></i>
                <div>
                    <h6 class="mb-0">Verification Rejected</h6>
                    <p class="mb-0"><strong>Reason:</strong> <?php echo e(auth()->user()->rejection_reason); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <h4 class="mb-4">Welcome, <?php echo e(auth()->user()->organization_name); ?>!</h4>
    
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
                        <span class="text-muted">Our Pledges</span>
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
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?php echo e($stats['drives_participated']); ?></h3>
                        <span class="text-muted">Drives Participated</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <a href="<?php echo e(route('ngo.pledges.create')); ?>" class="card text-decoration-none h-100 <?php echo e(auth()->user()->isPending() ? 'opacity-50' : ''); ?>">
                <div class="card-body text-center py-4">
                    <i class="bi bi-plus-circle fs-1 text-primary mb-2"></i>
                    <h6>Pledge New Donation</h6>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo e(route('ngo.donation-link.index')); ?>" class="card text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-link-45deg fs-1 text-success mb-2"></i>
                    <h6>Manage Donation Link</h6>
                    <small class="text-muted"><?php echo e($linkClicks); ?> clicks</small>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo e(route('ngo.map')); ?>" class="card text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-geo-alt fs-1 text-info mb-2"></i>
                    <h6>View Drives Map</h6>
                </div>
            </a>
        </div>
    </div>
    
    <!-- Active Drives -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Active Donation Drives</h5>
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
                                    <small class="text-muted"><?php echo e($drive->progress_percentage); ?>% of target</small>
                                </div>
                                
                                <?php if($drive->address): ?>
                                    <p class="small mb-2">
                                        <i class="bi bi-geo-alt text-muted me-1"></i><?php echo e(Str::limit($drive->address, 40)); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-transparent">
                                <?php if(auth()->user()->isVerified()): ?>
                                    <a href="<?php echo e(route('ngo.pledges.create', ['drive_id' => $drive->id])); ?>" class="btn btn-primary w-100">
                                        <i class="bi bi-heart me-2"></i>Pledge
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100" disabled>
                                        Account Pending
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mb-0">No active drives at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\tupv\relief\resources\views/ngo/dashboard.blade.php ENDPATH**/ ?>