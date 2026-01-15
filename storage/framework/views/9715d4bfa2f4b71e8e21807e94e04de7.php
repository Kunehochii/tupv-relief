

<?php $__env->startSection('title', 'Edit Drive - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.drives.index')); ?>">Drives</a></li>
            <li class="breadcrumb-item active">Edit <?php echo e($drive->name); ?></li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Donation Drive</div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.drives.update', $drive)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Drive Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="name" name="name" value="<?php echo e(old('name', $drive->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="description" name="description" rows="4" required><?php echo e(old('description', $drive->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="target_type" class="form-label">Target Type <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['target_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="target_type" name="target_type" required>
                                    <option value="financial" <?php echo e(old('target_type', $drive->target_type) === 'financial' ? 'selected' : ''); ?>>Financial (PHP)</option>
                                    <option value="quantity" <?php echo e(old('target_type', $drive->target_type) === 'quantity' ? 'selected' : ''); ?>>Quantity (Items)</option>
                                </select>
                                <?php $__errorArgs = ['target_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="target_amount" class="form-label">Target Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?php $__errorArgs = ['target_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="target_amount" name="target_amount" value="<?php echo e(old('target_amount', $drive->target_amount)); ?>" 
                                       min="1" step="0.01" required>
                                <?php $__errorArgs = ['target_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Progress Section -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="collected_amount" class="form-label">Collected Amount (Progress)</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['collected_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="collected_amount" name="collected_amount" 
                                       value="<?php echo e(old('collected_amount', $drive->collected_amount ?? 0)); ?>" 
                                       min="0" step="0.01">
                                <?php $__errorArgs = ['collected_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">Current progress: <?php echo e($drive->progress_percentage ?? 0); ?>%</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Progress Bar</label>
                                <div class="progress" style="height: 38px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: <?php echo e($drive->progress_percentage ?? 0); ?>%" 
                                         aria-valuenow="<?php echo e($drive->progress_percentage ?? 0); ?>" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?php echo e($drive->progress_percentage ?? 0); ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="start_date" name="start_date" value="<?php echo e(old('start_date', $drive->start_date?->format('Y-m-d'))); ?>">
                                <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="end_date" name="end_date" value="<?php echo e(old('end_date', $drive->end_date?->format('Y-m-d'))); ?>" required>
                                <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="items_needed" class="form-label">Items Needed (comma-separated)</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['items_needed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="items_needed" name="items_needed" 
                                   value="<?php echo e(old('items_needed', $drive->items_needed ? implode(', ', $drive->items_needed) : '')); ?>"
                                   placeholder="e.g., Rice, Canned Goods, Water, Blankets">
                            <?php $__errorArgs = ['items_needed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">List of items that donors can contribute</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="status" name="status">
                                <option value="active" <?php echo e(old('status', $drive->status) === 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="completed" <?php echo e(old('status', $drive->status) === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                <option value="closed" <?php echo e(old('status', $drive->status) === 'closed' ? 'selected' : ''); ?>>Closed</option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <!-- Location -->
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <div id="map" style="height: 300px; border-radius: 0.5rem;" class="mb-2"></div>
                            <small class="text-muted">Click on the map to set the drive location</small>
                            <input type="hidden" name="latitude" id="latitude" value="<?php echo e(old('latitude', $drive->latitude)); ?>">
                            <input type="hidden" name="longitude" id="longitude" value="<?php echo e(old('longitude', $drive->longitude)); ?>">
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="address" name="address" value="<?php echo e(old('address', $drive->address)); ?>"
                                   placeholder="e.g., Quezon City, Metro Manila">
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Drive</button>
                            <a href="<?php echo e(route('admin.drives.show', $drive)); ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
    // Initialize map
    const defaultLat = <?php echo e(old('latitude', $drive->latitude ?? 14.5995)); ?>;
    const defaultLng = <?php echo e(old('longitude', $drive->longitude ?? 120.9842)); ?>;
    const map = L.map('map').setView([defaultLat, defaultLng], <?php echo e($drive->latitude ? 13 : 6); ?>);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    let marker = null;
    
    // Set initial marker if exists
    <?php if($drive->latitude && $drive->longitude): ?>
        marker = L.marker([<?php echo e($drive->latitude); ?>, <?php echo e($drive->longitude); ?>]).addTo(map);
    <?php endif; ?>
    
    // Click handler
    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }
        
        // Reverse geocode
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('address').value = data.display_name;
                }
            });
    });

    // Real-time progress bar update
    function updateProgressBar() {
        const targetAmount = parseFloat(document.getElementById('target_amount').value) || 0;
        const collectedAmount = parseFloat(document.getElementById('collected_amount').value) || 0;
        
        let percentage = 0;
        if (targetAmount > 0) {
            percentage = Math.min(100, Math.round((collectedAmount / targetAmount) * 100 * 100) / 100);
        }
        
        const progressBar = document.querySelector('.progress-bar');
        const progressText = progressBar.parentElement.nextElementSibling;
        
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
        progressBar.textContent = percentage + '%';
        
        if (progressText) {
            progressText.innerHTML = `<small class="text-muted">Current progress: ${percentage}%</small>`;
        }
    }

    // Attach event listeners
    document.getElementById('target_amount').addEventListener('input', updateProgressBar);
    document.getElementById('collected_amount').addEventListener('input', updateProgressBar);
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\tupv\relief\resources\views/admin/drives/edit.blade.php ENDPATH**/ ?>