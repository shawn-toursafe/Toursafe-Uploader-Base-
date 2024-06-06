<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminArchiveController;
use App\Http\Controllers\Admin\ArchiveConfigController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\FinancePlanController;
use App\Http\Controllers\Admin\ReferralSystemController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\FinanceSettingController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\InstallController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\Admin\Frontend\AppearanceController;
use App\Http\Controllers\Admin\Frontend\FrontendController;
use App\Http\Controllers\Admin\Frontend\BlogController;
use App\Http\Controllers\Admin\Frontend\PageController;
use App\Http\Controllers\Admin\Frontend\FAQController;
use App\Http\Controllers\Admin\Frontend\ReviewController;
use App\Http\Controllers\Admin\Settings\GlobalController;
use App\Http\Controllers\Admin\Settings\BackupController;
use App\Http\Controllers\Admin\Settings\OAuthController;
use App\Http\Controllers\Admin\Settings\ActivationController;
use App\Http\Controllers\Admin\Settings\SMTPController;
use App\Http\Controllers\Admin\Settings\RegistrationController;
use App\Http\Controllers\Admin\Settings\UpgradeController;
use App\Http\Controllers\Admin\Webhooks\PaypalWebhookController;
use App\Http\Controllers\Admin\Webhooks\StripeWebhookController;
use App\Http\Controllers\Admin\Webhooks\PaystackWebhookController;
use App\Http\Controllers\Admin\Webhooks\RazorpayWebhookController;
use App\Http\Controllers\Admin\Webhooks\CoinbaseWebhookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\UploadController;
use App\Http\Controllers\User\ArchiveResultController;
use App\Http\Controllers\User\BalanceController;
use App\Http\Controllers\User\PlanController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ReferralController;
use App\Http\Controllers\User\UserSupportController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\User\SearchController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now reate something great!
|
*/

// AUTH ROUTES
Route::middleware(['middleware' => 'PreventBackHistory'])->group(function () {
    require __DIR__.'/auth.php';
});

// FRONTEND ROUTES
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/blog/{slug}', 'blogShow')->name('blogs.show');
    Route::post('/contact', 'contact')->name('contact');
    Route::get('/terms-and-conditions', 'termsAndConditions')->name('terms');
    Route::get('/privacy-policy', 'privacyPolicy')->name('privacy');
});

// INSTAL ROUTES
Route::group(['prefix' => 'install', 'middleware' => 'install'], function() {
    Route::controller(InstallController::class)->group(function () {
        Route::get('/', 'index')->name('install');
        Route::get('/requirements', 'requirements')->name('install.requirements');
        Route::get('/permissions', 'permissions')->name('install.permissions');
        Route::get('/database', 'database')->name('install.database');    
        Route::post('/database', 'storeDatabaseCredentials')->name('install.database.store');
        Route::get('/activation', 'activation')->name('install.activation');    
        Route::post('/activation', 'activateApplication')->name('install.activation.activate');
    });
});

// LOCALE ROUTES
Route::get('/locale/{lang}', [LocaleController::class, 'language'])->name('locale');

// UPDATE ROUTE
Route::get('/update/now', [UpdateController::class, 'updateDatabase']);


// ADMIN ROUTES
Route::group(['prefix' => 'admin', 'middleware' => ['verified', 'role:admin', 'PreventBackHistory']], function() {

    // ADMIN DASHBOARD ROUTES
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // ADMIN ARCHIVE MANAGEMENT ROUTES
    Route::controller(AdminArchiveController::class)->group(function() {
        Route::get('/archive/dashboard', 'index')->name('admin.archive.dashboard');
        Route::get('/archive/list', 'listArchives')->name('admin.archive.list');  
        Route::get('/archive/{id}/show', 'show')->name('admin.archive.show');    
        Route::post('/archive/download', 'download');         
        Route::post('/archive/delete', 'delete');   
    }); 

    Route::post('/archive/retrieve', [UploadController::class, 'retrieveArchiveAdmin']);
    
    // ADMIN ARCHIVE CONFIGURATION ROUTES
    Route::controller(ArchiveConfigController::class)->group(function() {
        Route::get('/archive/configs', 'index')->name('admin.archive.configs');
        Route::post('/archive/configs', 'store')->name('admin.archive.configs.store');
    }); 

    // ADMIN USER MANAGEMENT ROUTES
    Route::controller(AdminUserController::class)->group(function() {
        Route::get('/users/dashboard', 'index')->name('admin.user.dashboard');
        Route::get('/users/activity', 'activity')->name('admin.user.activity');
        Route::get('/users/list', 'listUsers')->name('admin.user.list');        
        Route::post('/users', 'store')->name('admin.user.store');
        Route::get('/users/create', 'create')->name('admin.user.create');        
        Route::get('/users/{user}/show', 'show')->name('admin.user.show');
        Route::get('/users/{user}/edit', 'edit')->name('admin.user.edit');
        Route::get('/users/{user}/storage', 'storage')->name('admin.user.storage');
        Route::post('/users/{user}/increase', 'increase')->name('admin.user.increase');
        Route::put('/users/{user}/update', 'update')->name('admin.user.update');
        Route::put('/users/{user}', 'change')->name('admin.user.change');       
        Route::post('/users/delete', 'delete');
    }); 
            
    // ADMIN FINANCE - DASHBOARD & TRANSACTIONS & SUBSCRIPTION LIST ROUTES
    Route::controller(FinanceController::class)->group(function() {
        Route::get('/finance/dashboard', 'index')->name('admin.finance.dashboard');
        Route::get('/finance/transactions', 'listTransactions')->name('admin.finance.transactions');
        Route::put('/finance/transaction/{id}/update', 'update')->name('admin.finance.transaction.update');
        Route::get('/finance/transaction/{id}/show', 'show')->name('admin.finance.transaction.show');
        Route::get('/finance/transaction/{id}/edit', 'edit')->name('admin.finance.transaction.edit');
        Route::post('/finance/transaction/delete', 'delete');
        Route::get('/finance/subscriptions', 'listSubscriptions')->name('admin.finance.subscriptions');
    });

    // ADMIN FINANCE - CANCEL USER SUBSCRIPTION
    Route::post('/finance/subscriptions/cancel', [PaymentController::class, 'stopSubscription']);

    // ADMIN FINANCE - PLAN ROUTES
    Route::controller(FinancePlanController::class)->group(function() {
        Route::get('/finance/plans', 'index')->name('admin.finance.plans');
        Route::post('/finance/plans', 'store')->name('admin.finance.plan.store');
        Route::get('/finance/plan/create', 'create')->name('admin.finance.plan.create');
        Route::get('/finance/plan/{id}/show', 'show')->name('admin.finance.plan.show');        
        Route::get('/finance/plan/{id}/edit', 'edit')->name('admin.finance.plan.edit');
        Route::put('/finance/plan/{id}', 'update')->name('admin.finance.plan.update');
        Route::post('/finance/plan/delete', 'delete');
    });

    // ADMIN FINANCE - REFERRAL ROUTES
    Route::controller(ReferralSystemController::class)->group(function() {
        Route::get('/referral/settings', 'index')->name('admin.referral.settings');
        Route::post('/referral/settings', 'store')->name('admin.referral.settings.store');
        Route::get('/referral/{order_id}/show', 'paymentShow')->name('admin.referral.show');
        Route::get('/referral/payouts', 'payouts')->name('admin.referral.payouts');
        Route::get('/referral/payouts/{id}/show', 'payoutsShow')->name('admin.referral.payouts.show');
        Route::put('/referral/payouts/{id}/store', 'payoutsUpdate')->name('admin.referral.payouts.update');
        Route::get('/referral/payouts/{id}/cancel', 'payoutsCancel')->name('admin.referral.payouts.cancel');
        Route::delete('/referral/payouts/{id}/decline', 'payoutsDecline')->name('admin.referral.payouts.decline');
        Route::get('/referral/registration', 'registrationReferrals')->name('admin.referral.registration');
        Route::get('/referral/top', 'topReferrers')->name('admin.referral.top');
    });

    // ADMIN FINANCE - INVOICE SETTINGS
    Route::controller(InvoiceController::class)->group(function() {
        Route::get('/settings/invoice', 'index')->name('admin.settings.invoice');
        Route::post('/settings/invoice', 'store')->name('admin.settings.invoice.store');
    });

    // ADMIN FINANCE SETTINGS ROUTES
    Route::controller(FinanceSettingController::class)->group(function() {
        Route::get('/finance/settings', 'index')->name('admin.finance.settings');
        Route::post('/finance/settings', 'store')->name('admin.finance.settings.store');
    });

    // ADMIN SUPPORT ROUTES
    Route::controller(SupportController::class)->group(function() {
        Route::get('/support', 'index')->name('admin.support');
        Route::put('/support/{ticked_id}', 'update')->name('admin.support.update');
        Route::get('/support/{ticket_id}/show', 'show')->name('admin.support.show');
        Route::post('/support/delete', 'delete');
    });

    // ADMIN NOTIFICATION ROUTES
    Route::controller(NotificationController::class)->group(function() {
        Route::get('/notifications', 'index')->name('admin.notifications');
        Route::get('/notifications/sytem', 'system')->name('admin.notifications.system');
        Route::get('/notifications/create', 'create')->name('admin.notifications.create');
        Route::post('/notifications', 'store')->name('admin.notifications.store');
        Route::get('/notifications/{id}/show', 'show')->name('admin.notifications.show');
        Route::get('/notifications/system/{id}/show', 'systemShow')->name('admin.notifications.systemShow');
        Route::get('/notifications/mark-all', 'markAllRead')->name('admin.notifications.markAllRead');
        Route::get('/notifications/delete-all', 'deleteAll')->name('admin.notifications.deleteAll');
        Route::post('/notifications/delete', 'delete'); 
    });
    
    // ADMIN GENERAL SETTINGS - GLOBAL SETTINGS
    Route::controller(GlobalController::class)->group(function() {
        Route::get('/settings/global', 'index')->name('admin.settings.global');
        Route::post('/settings/global', 'store')->name('admin.settings.global.store');
    });

    // ADMIN GENERAL SETTINGS - DATABASE BACKUP
    Route::controller(BackupController::class)->group(function() {
        Route::get('/settings/backup', 'index')->name('admin.settings.backup');
        Route::get('/settings/backup/create', 'create')->name('admin.settings.backup.create');
        Route::get('/settings/backup/{file_name}', 'download')->name('admin.settings.backup.download');
        Route::get('/settings/backup/{file_name}/delete', 'destroy')->name('admin.settings.backup.delete');
    });

    // ADMIN GENERAL SETTINGS - SMTP SETTINGS
    Route::controller(SMTPController::class)->group(function() {
        Route::post('/settings/smtp/test', 'test')->name('admin.settings.smtp.test');
        Route::get('/settings/smtp', 'index')->name('admin.settings.smtp');
        Route::post('/settings/smtp', 'store')->name('admin.settings.smtp.store');  
    });      

    // ADMIN GENERAL SETTINGS - REGISTRATION SETTINGS
    Route::controller(RegistrationController::class)->group(function() {
        Route::get('/settings/registration', 'index')->name('admin.settings.registration');
        Route::post('/settings/registration', 'store')->name('admin.settings.registration.store');
    });

    // ADMIN GENERAL SETTINGS - OAUTH SETTINGS
    Route::controller(OAuthController::class)->group(function() {
        Route::get('/settings/oauth', 'index')->name('admin.settings.oauth');
        Route::post('/settings/oauth', 'store')->name('admin.settings.oauth.store');
    });

    // ADMIN GENERAL SETTINGS - ACTIVATION SETTINGS
    Route::controller(ActivationController::class)->group(function() {
        Route::get('/settings/activation', 'index')->name('admin.settings.activation');
        Route::post('/settings/activation', 'store')->name('admin.settings.activation.store');
        Route::get('/settings/activation/remove', 'remove')->name('admin.settings.activation.remove');
        Route::delete('/settings/activation/destroy', 'destroy')->name('admin.settings.activation.destroy');
        Route::get('/settings/activation/manual', 'showManualActivation')->name('admin.settings.activation.manual');
        Route::post('/settings/activation/manual', 'storeManualActivation')->name('admin.settings.activation.manual.store');
    });

    // ADMIN FRONTEND SETTINGS - APPEARANCE SETTINGS
    Route::controller(AppearanceController::class)->group(function() {
        Route::get('/settings/appearance', 'index')->name('admin.settings.appearance');
        Route::post('/settings/appearance', 'store')->name('admin.settings.appearance.store');
    });

    // ADMIN FRONTEND SETTINGS - FRONTEND SETTINGS
    Route::controller(FrontendController::class)->group(function() {
        Route::get('/settings/frontend', 'index')->name('admin.settings.frontend');
        Route::post('/settings/frontend', 'store')->name('admin.settings.frontend.store');
    });

    // ADMIN FRONTEND SETTINGS - BLOG MANAGER
    Route::controller(BlogController::class)->group(function() {
        Route::get('/settings/blog', 'index')->name('admin.settings.blog');
        Route::get('/settings/blog/create', 'create')->name('admin.settings.blog.create');
        Route::post('/settings/blog', 'store')->name('admin.settings.blog.store');   
        Route::put('/settings/blogs/{id}', 'update')->name('admin.settings.blog.update');		
        Route::get('/settings/blogs/{id}/edit', 'edit')->name('admin.settings.blog.edit');        
        Route::post('/settings/blog/delete', 'delete');
    });

    // ADMIN FRONTEND SETTINGS - FAQ MANAGER
    Route::controller(FAQController::class)->group(function() {
        Route::get('/settings/faq', 'index')->name('admin.settings.faq');
        Route::get('/settings/faq/create', 'create')->name('admin.settings.faq.create');        
        Route::post('/settings/faq', 'store')->name('admin.settings.faq.store');   
        Route::put('/settings/faqs/{id}', 'update')->name('admin.settings.faq.update');		
        Route::get('/settings/faqs/{id}/edit', 'edit')->name('admin.settings.faq.edit');        
        Route::post('/settings/faq/delete', 'delete');
    });

    // ADMIN FRONTEND SETTINGS - REVIEW MANAGER
    Route::controller(ReviewController::class)->group(function() {
        Route::get('/settings/review', 'index')->name('admin.settings.review');
        Route::get('/settings/review/create', 'create')->name('admin.settings.review.create');
        Route::post('/settings/review', 'store')->name('admin.settings.review.store');   
        Route::put('/settings/reviews/{id}', 'update')->name('admin.settings.review.update');		
        Route::get('/settings/reviews/{id}/edit', 'edit')->name('admin.settings.review.edit');        
        Route::post('/settings/review/delete', 'delete');
    });
    
    // ADMIN FRONTEND SETTINGS - PAGE MANAGER (PRIVACY & TERMS) 
    Route::controller(PageController::class)->group(function() {
        Route::get('/settings/terms', 'index')->name('admin.settings.terms');
        Route::post('/settings/terms', 'store')->name('admin.settings.terms.store');
    });

    // ADMIN GENERAL SETTINGS - UPGRADE SOFTWARE
    Route::controller(UpgradeController::class)->group(function() {
        Route::get('/settings/upgrade', 'index')->name('admin.settings.upgrade');
        Route::post('/settings/upgrade', 'upgrade')->name('admin.settings.upgrade.start');
    });

    Route::get('/clear', function() {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
    });

    Route::get('/symlink', function() {
        Artisan::call('storage:link');
    });

});
  
    
// REGISTERED USER ROUTES
Route::group(['prefix' => 'user', 'middleware' => ['verified', 'role:user|admin|subscriber', 'PreventBackHistory']], function() {

    // USER DASHBOARD ROUTES
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');   

    // USER ARCHIVE UPLOAD ROUTES
    Route::controller(UploadController::class)->group(function () {
        Route::get('/archive/upload', 'index')->name('user.archive.upload');
        Route::post('/archive/initiateupload', 'initiateUpload');
        Route::post('/archive/createparts', 'createParts');
        Route::post('/archive/completeupload', 'completeUpload');
        Route::post('/archive/cancelupload', 'cancelUpload');
        Route::post('/archive/retrieve', 'retrieveArchive');
        Route::post('/archive/delete', 'delete');
        Route::get('/archive/settings', 'settings');
    });

    // USER ARCHIVE RESULTS ROUTES
    Route::controller(ArchiveResultController::class)->group(function () {
        Route::get('/archive/list', 'index')->name('user.archive.list');
        Route::get('/archive/list/download', 'listDownloadables')->name('user.archive.list.download');  
        Route::get('/archive/list/request', 'listRequested')->name('user.archive.list.request');  
        Route::get('/archive/{id}/show', 'show')->name('user.archive.list.show');
        Route::post('/archive/download', 'download');
    });

    // CHANGE USER PASSWORD ROUTES
    Route::controller(UserPasswordController::class)->group(function() {
        Route::get('/profile/security', 'index')->name('user.security');
        Route::post('/profile/security/password/{id}', 'update')->name('user.security.password');
        Route::post('/profile/security/google/{id}', 'security')->name('user.security.google');
    });

    // USER PROFILE ROUTES
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'index')->name('user.profile');
        Route::put('/profile/{user}', 'update')->name('user.profile.update');
        Route::post('/profile/project', 'updateProject')->name('user.profile.project');
        Route::get('/profile/edit', 'edit')->name('user.profile.edit');     
    }); 

    // USER BALANCE ROUTES
    Route::controller(BalanceController::class)->group(function () {        
        Route::get('/payments', 'listPayments')->name('user.balance.payments');        
        Route::get('/subscriptions', 'listSubscriptions')->name('user.balance.subscriptions');
        Route::get('/payments/show/{id}', 'show')->name('user.balance.payments.show');
    });

    // USER SUBSCRIPTION PLAN ROUTES
    Route::controller(PlanController::class)->group(function () {
        Route::get('/subscription/plans', 'index')->name('user.plans');
        Route::get('/subscription/plan/subscribe/{id}', 'subscribe')->name('user.plan.subscribe')->middleware('unsubscribed'); 
    });      

    // USER PAYMENT ROUTES
    Route::controller(PaymentController::class)->group(function() {
        Route::post('/payments/pay/{id}', 'pay')->name('user.payments.pay')->middleware('unsubscribed');
        Route::post('/payments/approved/razorpay', 'approvedRazorpayPrepaid')->name('user.payments.approved.razorpay');  
        Route::post('/payments/subscription/razorpay', 'approvedRazorpaySubscription')->name('user.payments.subscription.razorpay');
        Route::get('/payments/subscription/approved', 'approvedSubscription')->name('user.payments.subscription.approved');        
        Route::get('/payments/subscription/cancelled', 'cancelledSubscription')->name('user.payments.subscription.cancelled')->middleware('unsubscribed');
        Route::post('/subscriptions/cancel', 'stopSubscription');
    });

    // USER REFERRAL ROUTES
    Route::controller(ReferralController::class)->group(function() {
        Route::get('/referral', 'index')->name('user.referral');
        Route::post('/referral/settings', 'store')->name('user.referral.store');
        Route::get('/referral/gateway', 'gateway')->name('user.referral.gateway');
        Route::post('/referral/gateway', 'gatewayStore')->name('user.referral.gateway.store');
        Route::get('/referral/payouts', 'payouts')->name('user.referral.payout');
        Route::post('/referral/email', 'email')->name('user.referral.email');
        Route::get('/referral/payouts/create', 'payoutsCreate')->name('user.referral.payout.create');
        Route::post('/referral/payouts/store', 'payoutsStore')->name('user.referral.payout.store');
        Route::get('/referral/all', 'referrals')->name('user.referral.referrals');        
        Route::get('/referral/payouts/{id}/show', 'payoutsShow')->name('user.referral.payout.show');
        Route::get('/referral/payouts/{id}/cancel', 'payoutsCancel')->name('user.referral.payout.cancel');
        Route::delete('/referral/payouts/{id}/decline', 'payoutsDecline')->name('user.referral.payout.decline');
    });

    // USER INVOICE ROUTES
    Route::controller(PaymentController::class)->group(function() {
        Route::get('/payments/invoice/{order_id}/generate', 'generatePaymentInvoice')->name('user.payments.invoice');
        Route::get('/payments/invoice/{id}/show', 'showPaymentInvoice')->name('user.payments.invoice.show');
        Route::get('/payments/invoice/{order_id}/transfer', 'bankTransferPaymentInvoice')->name('user.payments.invoice.transfer');
    });

    // USER SUPPORT REQUEST ROUTES  
    Route::controller(UserSupportController::class)->group(function() { 
        Route::get('/support', 'index')->name('user.support');
        Route::post('/support', 'store')->name('user.support.store');
        Route::get('/support/create', 'create')->name('user.support.create'); 
        Route::get('/support/{ticket_id}/show', 'show')->name('user.support.show');
        Route::post('/support/delete', 'delete'); 
    });      

    // USER NOTIFICATION ROUTES
    Route::controller(UserNotificationController::class)->group(function() {
        Route::get('/notification', 'index')->name('user.notifications');
        Route::get('/notification/{id}/show', 'show')->name('user.notifications.show');        
        Route::post('/notification/delete', 'delete');
        Route::get('/notifications/mark-all', 'markAllRead')->name('user.notifications.markAllRead');
        Route::get('/notifications/delete-all', 'deleteAll')->name('user.notifications.deleteAll');
        Route::post('/notifications/mark-as-read', 'markNotification')->name('user.notifications.mark');
    });    

    // USER SEARCH ROUTES
    Route::any('/search', [SearchController::class, 'index'])->name('search');
});