

<?php $__env->startSection('title', 'Manage Drives'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Manage Donation Drives</h4>
        <a href="<?php echo e(route('admin.drives.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Create Drive
        </a>
    </div>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Drive Name</th>
                            <th>Status</th>
                            <th>End Date</th>
                            <th>Pledges</th>
                            <th>Progress</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $drives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $drive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('admin.drives.show', $drive)); ?>" class="fw-medium">
                                        <?php echo e($drive->name); ?>

                                    </a>
                                </td>
                                <td>
                                    <?php if($drive->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif($drive->status === 'completed'): ?>
                                        <span class="badge bg-primary">Completed</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Closed</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($drive->end_date->format('M d, Y')); ?></td>
                                <td><?php echo e($drive->pledges_count); ?></td>
                                <td style="width: 150px;">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" style="width: <?php echo e($drive->progress_percentage); ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?php echo e($drive->progress_percentage); ?>%</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('admin.drives.show', $drive)); ?>" class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.drives.edit', $drive)); ?>" class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if($drive->status === 'active'): ?>
                                            <form method="POST" action="<?php echo e(route('admin.drives.close', $drive)); ?>" class="d-inline" 
                                                onsubmit="return confirm('Are you sure you want to close this drive?')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-outline-danger" title="Close">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No drives found. <a href="<?php echo e(route('admin.drives.create')); ?>">Create one</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <?php echo e($drives->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\tupv\relief\resources\views/admin/drives/index.blade.php ENDPATH**/ ?>