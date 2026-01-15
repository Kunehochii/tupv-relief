<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand text-primary" href="<?php echo e(url('/')); ?>">
            <i class="bi bi-heart-pulse-fill me-2"></i>Relief
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.drives.index')); ?>">Drives</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.pledges.pending')); ?>">Verify Pledges</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.ngos.pending')); ?>">Verify NGOs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.reports.index')); ?>">Reports</a>
                        </li>
                    <?php elseif(auth()->user()->isDonor()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('donor.dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('donor.pledges.create')); ?>">Make a Pledge</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('donor.pledges.index')); ?>">My Pledges</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('donor.map')); ?>">Drive Map</a>
                        </li>
                    <?php elseif(auth()->user()->isNgo()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('ngo.dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('ngo.pledges.create')); ?>">Make a Pledge</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('ngo.pledges.index')); ?>">Our Pledges</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('ngo.donation-link.index')); ?>">Donation Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('ngo.map')); ?>">Drive Map</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <?php if(auth()->guard()->check()): ?>
                    <?php if(!auth()->user()->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link notification-badge" href="<?php echo e(route(auth()->user()->role . '.notifications.index')); ?>">
                                <i class="bi bi-bell"></i>
                                <?php if(auth()->user()->unreadNotifications()->count() > 0): ?>
                                    <span class="badge bg-danger rounded-pill"><?php echo e(auth()->user()->unreadNotifications()->count()); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <?php if(auth()->user()->avatar): ?>
                                <img src="<?php echo e(auth()->user()->avatar); ?>" alt="Avatar" class="rounded-circle me-1" width="24" height="24">
                            <?php else: ?>
                                <i class="bi bi-person-circle me-1"></i>
                            <?php endif; ?>
                            <?php echo e(auth()->user()->display_name); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small"><?php echo e(ucfirst(auth()->user()->role)); ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('login')); ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="<?php echo e(route('register')); ?>">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH D:\tupv\relief\resources\views/partials/navbar.blade.php ENDPATH**/ ?>