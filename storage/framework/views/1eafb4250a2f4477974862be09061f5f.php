<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 15px 0;
        }
        .notification-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .type-success { background: #d1e7dd; color: #0f5132; }
        .type-info { background: #cff4fc; color: #055160; }
        .type-warning { background: #fff3cd; color: #664d03; }
        .type-danger { background: #f8d7da; color: #842029; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;"><?php echo e(config('app.name', 'Relief')); ?></h1>
        <p style="margin: 5px 0 0;">Donation Drive Management System</p>
    </div>
    
    <div class="content">
        <?php
            $typeClass = match($notification->type) {
                'pledge_verified', 'pledge_distributed', 'ngo_verified' => 'success',
                'pledge_submitted', 'new_drive', 'pledge_expiring' => 'info',
                'pledge_expired', 'pledge_rejected', 'ngo_rejected' => 'danger',
                default => 'info'
            };
            $typeLabel = match($notification->type) {
                'pledge_verified' => 'Pledge Verified',
                'pledge_distributed' => 'Pledge Distributed',
                'pledge_expired' => 'Pledge Expired',
                'pledge_expiring' => 'Pledge Expiring Soon',
                'pledge_submitted' => 'New Pledge',
                'pledge_rejected' => 'Pledge Rejected',
                'new_drive' => 'New Drive',
                'ngo_verified' => 'Account Verified',
                'ngo_rejected' => 'Verification Rejected',
                default => 'Notification'
            };
        ?>
        
        <span class="notification-type type-<?php echo e($typeClass); ?>"><?php echo e($typeLabel); ?></span>
        
        <h2 style="margin-top: 0;"><?php echo e($notification->title); ?></h2>
        
        <p><?php echo e($notification->message); ?></p>
        
        <?php if($notification->link): ?>
            <p style="text-align: center;">
                <a href="<?php echo e(url($notification->link)); ?>" class="btn">View Details</a>
            </p>
        <?php endif; ?>
        
        <p style="color: #6c757d; font-size: 14px;">
            This notification was sent on <?php echo e($notification->created_at->format('F j, Y \a\t g:i A')); ?>.
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'Relief')); ?>. All rights reserved.</p>
        <p>
            Department of Social Welfare and Development (DSWD)<br>
            Disaster Relief Coordination System
        </p>
        <p style="margin-top: 15px;">
            <small>You received this email because you have an account on Relief. 
            If you believe this was sent in error, please contact support.</small>
        </p>
    </div>
</body>
</html>
<?php /**PATH D:\tupv\relief\resources\views/emails/notification.blade.php ENDPATH**/ ?>