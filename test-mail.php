<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\Mail;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Mail::raw('Test email from GameShop using Resend!', function ($message) {
        $message->to('madesanjaya255@gmail.com')
                ->subject('Resend Test');
    });
    echo "Email sent successfully!";
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage();
}
