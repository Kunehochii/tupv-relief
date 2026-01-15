

<?php $__env->startSection('title', 'Pending Pledges'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h4 class="mb-4">Pending Pledge Verifications</h4>
    
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
                            <th>Reference</th>
                            <th>Donor</th>
                            <th>Drive</th>
                            <th>Items</th>
                            <th>Submitted</th>
                            <th>Time Left</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pledges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pledge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('admin.pledges.show', $pledge)); ?>" class="fw-medium">
                                        <?php echo e($pledge->reference_number); ?>

                                    </a>
                                </td>
                                <td>
                                    <?php echo e($pledge->user->display_name); ?>

                                    <br>
                                    <small class="text-muted"><?php echo e(ucfirst($pledge->user->role)); ?></small>
                                </td>
                                <td><?php echo e($pledge->drive->name); ?></td>
                                <td>
                                    <?php if($pledge->items): ?>
                                        <?php echo e(implode(', ', array_slice($pledge->items, 0, 2))); ?>

                                        <?php if(count($pledge->items) > 2): ?>
                                            <span class="text-muted">+<?php echo e(count($pledge->items) - 2); ?> more</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($pledge->created_at->format('M d, H:i')); ?></td>
                                <td>
                                    <?php
                                        $hoursLeft = 24 - $pledge->created_at->diffInHours(now());
                                    ?>
                                    <?php if($hoursLeft > 6): ?>
                                        <span class="text-success"><?php echo e($hoursLeft); ?>h left</span>
                                    <?php elseif($hoursLeft > 0): ?>
                                        <span class="text-warning"><?php echo e($hoursLeft); ?>h left</span>
                                    <?php else: ?>
                                        <span class="text-danger">Expiring soon</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('admin.pledges.show', $pledge)); ?>" class="btn btn-outline-primary">
                                            View
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.pledges.verify', $pledge)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-check-circle me-1"></i>Verify
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle fs-3 mb-2"></i>
                                    <p class="mb-0">All pledges verified!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <?php echo e($pledges->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\tupv\relief\resources\views/admin/pledges/pending.blade.php ENDPATH**/ ?>