<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing admin dashboard foundation...\n";

// Check if admin user exists
$user = App\Models\User::where('role', 'admin')->first();
if (!$user) {
    $user = App\Models\User::factory()->create([
        'role' => 'admin', 
        'email_verified_at' => now(),
        'name' => 'Admin User',
        'email' => 'admin@lunora.com'
    ]);
    echo "Created admin user: " . $user->email . "\n";
} else {
    echo "Admin user exists: " . $user->email . "\n";
}

// Test admin middleware
echo "Admin middleware class exists: " . (class_exists('App\Http\Middleware\AdminMiddleware') ? 'Yes' : 'No') . "\n";

// Test dashboard controller
echo "Dashboard controller exists: " . (class_exists('App\Http\Controllers\Admin\DashboardController') ? 'Yes' : 'No') . "\n";

// Test admin activity log model
echo "AdminActivityLog model exists: " . (class_exists('App\Models\AdminActivityLog') ? 'Yes' : 'No') . "\n";

echo "Admin dashboard foundation test complete.\n";