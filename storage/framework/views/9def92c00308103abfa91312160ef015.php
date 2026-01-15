

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar py-3">
            <nav class="nav flex-column">
                <a class="nav-link active" href="<?php echo e(route('admin.dashboard')); ?>">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
                <a class="nav-link" href="<?php echo e(route('admin.drives.index')); ?>">
                    <i class="bi bi-collection me-2"></i>Manage Drives
                </a>
                <a class="nav-link" href="<?php echo e(route('admin.drives.create')); ?>">
                    <i class="bi bi-plus-circle me-2"></i>Create Drive
                </a>
                <a class="nav-link" href="<?php echo e(route('admin.pledges.pending')); ?>">
                    <i class="bi bi-check2-square me-2"></i>Verify Pledges
                    <?php if($metrics['pending_verifications'] > 0): ?>
                        <span class="badge bg-danger ms-auto"><?php echo e($metrics['pending_verifications']); ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-link" href="<?php echo e(route('admin.ngos.pending')); ?>">
                    <i class="bi bi-building-check me-2"></i>Verify NGOs
                    <?php if($metrics['pending_ngos'] > 0): ?>
                        <span class="badge bg-warning ms-auto"><?php echo e($metrics['pending_ngos']); ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-link" href="<?php echo e(route('admin.drives.map')); ?>">
                    <i class="bi bi-geo-alt me-2"></i>Drives Map
                </a>
                <a class="nav-link" href="<?php echo e(route('admin.reports.index')); ?>">
                    <i class="bi bi-bar-chart me-2"></i>Reports
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-10 py-3">
            <h4 class="mb-4">Dashboard Overview</h4>
            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-collection"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0"><?php echo e($metrics['total_drives']); ?></h3>
                                <span class="text-muted">Total Drives</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-play-circle"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0"><?php echo e($metrics['active_drives']); ?></h3>
                                <span class="text-muted">Active Drives</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-gift"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0"><?php echo e($metrics['total_donations']); ?></h3>
                                <span class="text-muted">Total Pledges</span>
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
                                <h3 class="mb-0"><?php echo e($metrics['pending_verifications']); ?></h3>
                                <span class="text-muted">Pending Verification</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <!-- Active Drives -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Active Drives</h5>
                            <a href="<?php echo e(route('admin.drives.index')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Drive Name</th>
                                            <th>End Date</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $activeDrives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $drive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo e(route('admin.drives.show', $drive)); ?>"><?php echo e($drive->name); ?></a>
                                                </td>
                                                <td><?php echo e($drive->end_date->format('M d, Y')); ?></td>
                                                <td>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar" style="width: <?php echo e($drive->progress_percentage); ?>%"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">No active drives</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Verifications -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pending Verifications</h5>
                            <a href="<?php echo e(route('admin.pledges.pending')); ?>" class="btn btn-sm btn-outline-warning">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php $__empty_1 = true; $__currentLoopData = $pendingPledges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pledge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e(route('admin.pledges.show', $pledge)); ?>" class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong><?php echo e($pledge->reference_number); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo e($pledge->user->name); ?> → <?php echo e($pledge->drive->name); ?></small>
                                            </div>
                                            <small class="text-muted"><?php echo e($pledge->created_at->diffForHumans()); ?></small>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="list-group-item text-center text-muted py-4">
                                        <i class="bi bi-check-circle fs-3 mb-2"></i>
                                        <p class="mb-0">All caught up!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\tupv\relief\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>