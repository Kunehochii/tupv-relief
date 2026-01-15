

<?php $__env->startSection('title', 'Welcome'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Make a Difference.<br>Donate with Relief.</h1>
                <p class="lead mb-4">Connect with verified donation drives, pledge your support, and track your impact. Together, we can help families in need.</p>
                <div class="d-flex gap-3">
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-heart-fill me-2"></i>Get Started
                    </a>
                    <a href="#how-it-works" class="btn btn-outline-light btn-lg">Learn More</a>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <i class="bi bi-people-fill" style="font-size: 15rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Role Information Section -->
<section class="py-5" id="roles">
    <div class="container">
        <h2 class="text-center mb-5">Who We Serve</h2>
        <div class="row g-4">
            <!-- Donors -->
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-gift text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h4>Individual Donors</h4>
                        <p class="text-muted">Make pledges to active donation drives, track your contributions, and see the real impact of your generosity.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Easy pledge submission</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Track donation status</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>View impact feedback</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- NGOs -->
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-building text-success" style="font-size: 2rem;"></i>
                        </div>
                        <h4>NGO Partners</h4>
                        <p class="text-muted">Partner with us to participate in drives, manage your external donation links, and share campaigns.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Verified organization status</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>External link tracking</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Campaign sharing tools</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- DSWD -->
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-shield-check text-info" style="font-size: 2rem;"></i>
                        </div>
                        <h4>DSWD Administrators</h4>
                        <p class="text-muted">Create and manage donation drives, verify pledges, and provide impact feedback to donors.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Drive management</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Pledge verification</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Impact reporting</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5 bg-light" id="how-it-works">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <span class="fw-bold fs-4">1</span>
                </div>
                <h5>Register</h5>
                <p class="text-muted">Create an account as a Donor or NGO Partner</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <span class="fw-bold fs-4">2</span>
                </div>
                <h5>Browse Drives</h5>
                <p class="text-muted">Find active donation drives in your area</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <span class="fw-bold fs-4">3</span>
                </div>
                <h5>Make a Pledge</h5>
                <p class="text-muted">Pledge items and get a reference number</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <span class="fw-bold fs-4">4</span>
                </div>
                <h5>Track Impact</h5>
                <p class="text-muted">See how your donation helped families</p>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5" id="features">
    <div class="container">
        <h2 class="text-center mb-5">Platform Features</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-geo-alt-fill text-primary fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h5>Interactive Maps</h5>
                        <p class="text-muted">View all donation drives on an interactive map. Find drives near you easily.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-bell-fill text-primary fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h5>Real-time Notifications</h5>
                        <p class="text-muted">Get notified about pledge status, new drives, and impact updates via app and email.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-shield-check text-primary fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h5>Verified Organizations</h5>
                        <p class="text-muted">All NGO partners are verified with Certificate of Authenticity.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-graph-up text-primary fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h5>Impact Tracking</h5>
                        <p class="text-muted">See exactly how many families were helped by your donations.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-google text-primary fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h5>Easy Sign-In</h5>
                        <p class="text-muted">Quick registration with Google OAuth or email/password.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-share-fill text-primary fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h5>Campaign Sharing</h5>
                        <p class="text-muted">Share drives with public preview pages that prompt sign-up.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-3">Ready to Make a Difference?</h2>
        <p class="lead mb-4">Join thousands of donors and NGOs making an impact every day.</p>
        <a href="<?php echo e(route('register')); ?>" class="btn btn-light btn-lg">
            <i class="bi bi-heart-fill me-2"></i>Start Donating Today
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="py-4 bg-dark text-white">
    <div class="container text-center">
        <p class="mb-0">&copy; <?php echo e(date('Y')); ?> Relief. Making a difference together.</p>
    </div>
</footer>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\tupv\relief\resources\views/welcome.blade.php ENDPATH**/ ?>