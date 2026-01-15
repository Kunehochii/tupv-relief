

<?php $__env->startSection('title', 'Pledge ' . $pledge->reference_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pledge Details</h5>
                    <?php if($pledge->isPending()): ?>
                        <span class="badge bg-warning">Pending Verification</span>
                    <?php elseif($pledge->isVerified()): ?>
                        <span class="badge bg-success">Verified</span>
                    <?php elseif($pledge->isDistributed()): ?>
                        <span class="badge bg-purple" style="background-color: var(--relief-purple);">Distributed</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Expired</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <!-- Reference Number Card -->
                    <div class="bg-light rounded p-4 text-center mb-4">
                        <p class="text-muted mb-1">Reference Number</p>
                        <h2 class="mb-0"><?php echo e($pledge->reference_number); ?></h2>
                        <small class="text-muted">Show this at the donation point</small>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Drive</label>
                            <p class="fw-medium"><?php echo e($pledge->drive->name); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Submitted On</label>
                            <p><?php echo e($pledge->created_at->format('M d, Y h:i A')); ?></p>
                        </div>
                    </div>
                    
                    <?php if($pledge->drive->address): ?>
                        <div class="mb-3">
                            <label class="text-muted small">Drop-off Location</label>
                            <p><i class="bi bi-geo-alt me-2"></i><?php echo e($pledge->drive->address); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Items Pledged</label>
                        <ul class="list-unstyled mb-0">
                            <?php $__currentLoopData = $pledge->items ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><i class="bi bi-check2 text-success me-2"></i><?php echo e($item); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <p class="mt-2"><strong>Quantity:</strong> <?php echo e($pledge->quantity); ?></p>
                    </div>
                    
                    <?php if($pledge->details): ?>
                        <div class="mb-3">
                            <label class="text-muted small">Details</label>
                            <p><?php echo e($pledge->details); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($pledge->verified_at): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Verified on <?php echo e($pledge->verified_at->format('M d, Y h:i A')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if($pledge->isDistributed()): ?>
                        <div class="alert alert-info" style="background-color: #f3e8ff; border-color: #c4b5fd;">
                            <i class="bi bi-box-seam me-2"></i>
                            Your donation has been distributed on <?php echo e($pledge->distributed_at->format('M d, Y')); ?>!
                        </div>
                    <?php endif; ?>
                    
                    <!-- Impact Feedback -->
                    <?php if($pledge->families_helped || $pledge->relief_packages || $pledge->admin_feedback): ?>
                        <div class="card bg-light border-0 mt-4">
                            <div class="card-body">
                                <h6><i class="bi bi-heart-fill text-danger me-2"></i>Your Impact</h6>
                                
                                <?php if($pledge->families_helped): ?>
                                    <p class="mb-1"><strong><?php echo e($pledge->families_helped); ?></strong> families helped</p>
                                <?php endif; ?>
                                <?php if($pledge->relief_packages): ?>
                                    <p class="mb-1"><strong><?php echo e($pledge->relief_packages); ?></strong> relief packages distributed</p>
                                <?php endif; ?>
                                <?php if($pledge->items_distributed): ?>
                                    <p class="mb-1"><strong><?php echo e($pledge->items_distributed); ?></strong> items distributed</p>
                                <?php endif; ?>
                                <?php if($pledge->admin_feedback): ?>
                                    <hr>
                                    <p class="mb-0 fst-italic">"<?php echo e($pledge->admin_feedback); ?>"</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route(auth()->user()->role . '.pledges.index')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to My Pledges
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\tupv\relief\resources\views/donor/pledges/show.blade.php ENDPATH**/ ?>