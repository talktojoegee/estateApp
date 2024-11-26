<?php

use Illuminate\Support\Facades\Route;
//use Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/',function(){
    return redirect()->route('login');
})->name('home-redirect');*/
Route::get('/book-appointment', [App\Http\Controllers\Portal\BookingController::class, 'showBookingForm'])->name('book-appointment');

/*Route::get('/migration', function(){
    Artisan::call('migrate');
    return dd('success');
});*/
Route::get('/process/payment',[App\Http\Controllers\OnlinePaymentController::class, 'processOnlinePayment']);
Route::prefix('/settings')->group(function(){
   // Route::get('/locations', [App\Http\Controllers\Admin\SettingsController::class, 'locationSetup'])->name('location-setup');
});

Auth::routes();
Route::get('logout', [App\Http\Controllers\Auth\LoginController::class,'logout'])->name('logout');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
//Route::get('/process/payment',[App\Http\Controllers\OnlinePaymentController::class, 'processOnlinePayment']);

Route::get('/home', function(){
    return redirect()->route('settings');
})->name('home');

/*Route::get('/test-cron',function(){
    \Illuminate\Support\Facades\Artisan::call("bulksms:send");
});*/

Route::get("/test-report", [App\Http\Controllers\Portal\ReportsController::class, 'test']);

Route::group(['middleware'=>'auth'], function(){
    Route::get('/dashboard', [App\Http\Controllers\Portal\DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::get('/departments', [App\Http\Controllers\Portal\BranchController::class, 'showChurchBranches'])->name('church-branches');
    Route::post('/assign-section-head', [App\Http\Controllers\Portal\BranchController::class, 'assignSectionHead'])->name('assign-section-head');
    Route::get('/department/{slug}', [App\Http\Controllers\Portal\BranchController::class, 'showChurchBranchDetails'])->name('church-branch-details');
});

Route::get('/attendance-medication-chart', [App\Http\Controllers\Portal\DashboardController::class, 'getAttendanceMedicationChart'])->name('attendance-medication-chart');
Route::group(['prefix'=>'/settings', 'middleware'=>'auth'], function(){
    Route::get('/', [App\Http\Controllers\Portal\SettingsController::class, 'showSettingsView'])->name('settings');
    Route::get('/cells', [App\Http\Controllers\Portal\SettingsController::class, 'showCellsSettingsView'])->name('cells-settings');
    Route::get('/sections', [App\Http\Controllers\Portal\SettingsController::class, 'showBranchesSettingsView'])->name('branches-settings');
    Route::post('/sections', [App\Http\Controllers\Portal\SettingsController::class, 'storeChurchBranch']);
    Route::post('/edit-section', [App\Http\Controllers\Portal\SettingsController::class, 'editChurchBranch'])->name('edit-branch-settings');
    Route::get('/form', [App\Http\Controllers\Portal\SettingsController::class, 'editApptLocations'])->name('edit-appt-locations');
    Route::post('/save-account-settings', [App\Http\Controllers\Portal\SettingsController::class, 'saveAccountSettings'])->name('save-account-settings');
    Route::post('/save-notification-settings', [App\Http\Controllers\Portal\SettingsController::class, 'saveNotificationSettings'])->name('save-notification-settings');
    Route::get('/notifications', [App\Http\Controllers\Portal\SettingsController::class, 'showNotificationSettings'])->name('notification-settings');
    Route::get('/appointments', [App\Http\Controllers\Portal\SettingsController::class, 'showAppointmentSettings'])->name('appointment-settings');
    Route::get('/change-password', [App\Http\Controllers\Portal\SettingsController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [App\Http\Controllers\Portal\SettingsController::class, 'changePassword']);
    Route::post('/digital-signature', [App\Http\Controllers\Portal\SettingsController::class, 'storeDigitalSignature'])->name('digital-signature');
    Route::get('/appointment-types', [App\Http\Controllers\Portal\SettingsController::class, 'showAppointmentTypeSettings'])->name('appointment-types-settings');
    Route::post('/appointment-types', [App\Http\Controllers\Portal\SettingsController::class, 'storeAppointmentType']);
    Route::post('/edit-appointment-types', [App\Http\Controllers\Portal\SettingsController::class, 'editAppointmentType'])->name('edit-appointment-types');
    Route::post('/update-appointment-settings', [App\Http\Controllers\Portal\SettingsController::class, 'updateAppointmentSettings'])->name('update-appointment-settings');
    Route::get('/appt-locations', [App\Http\Controllers\Portal\SettingsController::class, 'showApptLocations'])->name('appt-locations');
    Route::post('/appt-locations', [App\Http\Controllers\Portal\SettingsController::class, 'storeApptLocations']);
    Route::post('/edit-appt-locations', [App\Http\Controllers\Portal\SettingsController::class, 'editApptLocations'])->name('edit-appt-locations');
    Route::post('/update-organization-settings', [App\Http\Controllers\Portal\SettingsController::class, 'updateOrganizationSettings'])->name('update-organization-settings');
});

Route::post('/get-states', [App\Http\Controllers\Controller::class, 'getStates'])->name('get-states');

Route::group(['prefix'=>'/forms', 'middleware'=>'auth'], function(){
    Route::get('/', [App\Http\Controllers\Portal\FormController::class, 'showForms'])->name('forms');
    Route::get('/form/{slug}', [App\Http\Controllers\Portal\FormController::class, 'showFormDetails'])->name('form-details');
    Route::get('/add-form', [App\Http\Controllers\Portal\FormController::class, 'showAddNewForm'])->name('add-new-form');
    Route::post('/process-form', [App\Http\Controllers\Portal\FormController::class, 'processFormData'])->name('process-form');
});

Route::group(['prefix'=>'/calendar', 'middleware'=>'auth'], function(){
    Route::get('/', [App\Http\Controllers\Portal\CalendarController::class, 'showCalendar'])->name('calendar');
    Route::post('/add-calendar-event', [App\Http\Controllers\Portal\CalendarController::class, 'addCalendarEvent'])->name('add-calendar-event');
    Route::post('/add-group-calendar-event', [App\Http\Controllers\Portal\CalendarController::class, 'addGroupCalendarEvent'])->name('add-group-calendar-event');
    Route::post('/add-calendar-block-event', [App\Http\Controllers\Portal\CalendarController::class, 'addBlockCalendarEvent'])->name('add-calendar-block-event');
    Route::prefix('/appointments')->group(function(){
       Route::get('/', [App\Http\Controllers\Portal\CalendarController::class, 'showAppointments'])->name('show-appointments');
       Route::get('/{slug}', [App\Http\Controllers\Portal\CalendarController::class, 'showAppointmentDetails'])->name('show-appointment-details');
        Route::post('/change-status', [App\Http\Controllers\Portal\CalendarController::class, 'changeStatus'])->name('change-status');
       Route::post('/leave-a-note', [App\Http\Controllers\Portal\CalendarController::class, 'leaveANote'])->name('leave-a-note');
       Route::post('/filter-appointment', [App\Http\Controllers\Portal\CalendarController::class, 'filterAppointment'])->name('filter-appointment');
    });
});

Route::get('/my-notifications', [App\Http\Controllers\Portal\NotificationController::class, 'showMyNotification'])->name('my-notifications')->middleware('auth');
Route::get('/clear-all-notifications', [App\Http\Controllers\Portal\NotificationController::class, 'clearAllNotifications'])->name('clear-all-notifications')->middleware('auth');

Route::prefix('/cloud-storage')->group(function(){
    Route::get('/', [App\Http\Controllers\Portal\CloudStorageController::class, 'showCloudStorage'])->name('cloud-storage');
    Route::post('/manage-files', [App\Http\Controllers\Portal\CloudStorageController::class, 'storeFiles'] )->name('upload-files');
    Route::post('/create-folder', [App\Http\Controllers\Portal\CloudStorageController::class, 'createFolder'] )->name('create-folder');
    Route::get('/folder/{slug}', [App\Http\Controllers\Portal\CloudStorageController::class, 'openFolder'] )->name('open-folder');
    Route::get('/download/{slug}', [App\Http\Controllers\Portal\CloudStorageController::class, 'downloadAttachment'] )->name('download-attachment');
    Route::post('/delete-file', [App\Http\Controllers\Portal\CloudStorageController::class, 'deleteAttachment'])->name('delete-file');
    Route::post('/rename-file', [App\Http\Controllers\Portal\CloudStorageController::class, 'renameAttachment'])->name('rename-file');
    Route::post('/move-file', [App\Http\Controllers\Portal\CloudStorageController::class, 'moveAttachment'])->name('move-file');
    Route::post('/delete-folder', [App\Http\Controllers\Portal\CloudStorageController::class, 'deleteFolder'])->name('delete-folder');
});

//renamed users/clients to vendors
Route::group(['prefix'=>'/vendors', 'middleware'=>'auth'], function(){
    Route::get('/', [App\Http\Controllers\Portal\ClientController::class, 'showClients'])->name('clients'); //client now is known as vendors
    Route::post('/user-group', [App\Http\Controllers\Portal\ClientController::class, 'addClientGroup'])->name('client-group');
    Route::post('/edit-user-group', [App\Http\Controllers\Portal\ClientController::class, 'changeClientGroup'])->name('edit-client-group');
    Route::post('/add-user', [App\Http\Controllers\Portal\ClientController::class, 'addClient'])->name('add-client');
    Route::post('/assign-user-to', [App\Http\Controllers\Portal\ClientController::class, 'assignClientTo'])->name('assign-client-to');
    Route::post('/archive-unarchive-user', [App\Http\Controllers\Portal\ClientController::class, 'archiveUnarchiveClient'])->name('archive-unarchive-client');
    Route::post('/edit-user-profile', [App\Http\Controllers\Portal\ClientController::class, 'editClientProfile'])->name('edit-client-profile');
    Route::get('/view-profile/{slug}', [App\Http\Controllers\Portal\ClientController::class, 'viewClientProfile'])->name('view-client-profile');

});


Route::group(['prefix'=>'/tasks', 'middleware'=>'auth'], function(){
    Route::get('/', [App\Http\Controllers\Portal\TaskController::class, 'showTasks'])->name('manage-tasks');
    Route::get('/create', [App\Http\Controllers\Portal\TaskController::class, 'showCreateTaskForm'])->name('create-task');
    Route::post('/create', [App\Http\Controllers\Portal\TaskController::class, 'storeTask']);
    Route::post('/mark-as', [App\Http\Controllers\Portal\TaskController::class, 'markAs'])->name('mark-as');
});


Route::group(['prefix'=>'/inventory', 'middleware'=>'auth'],function(){
    Route::get('/', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showAllProducts'])->name('all-products');
    Route::post('/add-product-category', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'addProductCategory'])->name('add-product-category');
    Route::post('/edit-product-category', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'editProductCategory'])->name('edit-product-category');
    Route::post('/add-product', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'addProduct'])->name('add-product');
    Route::post('/edit-product', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'editProduct'])->name('edit-product');


    #Vendor routes
    Route::get('/vendors', [App\Http\Controllers\Portal\VendorController::class, 'showVendors'])->name('show-vendors');



    Route::get('/income', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showIncome'])->name('income');
    Route::post('/record-income', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'recordIncome'])->name('record-income');
    Route::get('/expense', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showExpense'])->name('expense');


    Route::get('/bulk-import', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showBulkImport'])->name('bulk-import');
    Route::post('/process-bulk-import', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'processBulkImport'])->name('process-bulk-import');
    Route::get('/approve-bulk-import', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'approveBulkImport'])->name('approve-bulk-import');
    Route::get('/view-bulk-import/{batchCode}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'viewBulkImport'])->name('view-bulk-import');
    Route::get('/delete-record/{recordId}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'deleteRecord'])->name('delete-record');
    Route::get('/discard-record/{batchCode}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'discardRecord'])->name('discard-record');
    Route::get('/post-record/{batchCode}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'postRecord'])->name('post-record');

    Route::post('/record-expense', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'recordExpense'])->name('record-expense');
    Route::get('/remittance', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showRemittance'])->name('remittance');
    Route::get('/show-remittance-collection', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showRemittanceCollections'])->name('show-remittance-collections');
    Route::post('/process-remittance-request', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'processRemittanceRequest'])->name('process-remittance-request');

   Route::prefix('/reports')->group(function(){
       Route::get('/cashbook/{type}', [App\Http\Controllers\Portal\ReportsController::class, 'showCashbookReport'])->name('cashbook');
       Route::get('/generate-cashbook-report', [App\Http\Controllers\Portal\ReportsController::class, 'generateCashbookReport'])->name('generate-cashbook-report');
       Route::get('/remittance', [App\Http\Controllers\Portal\ReportsController::class, 'showRemittanceReport'])->name('show-remittance-report');
       Route::get('/generate-remittance-report', [App\Http\Controllers\Portal\ReportsController::class, 'generateRemittanceReport'])->name('generate-remittance-report');
   });

});

Route::prefix('/sales')->group(function(){
    Route::get('/dashboard', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'marketing'])->name('marketing-dashboard');
    Route::get('/dashboard-filter', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'filterSalesRevenueReportDashboard'])->name('marketing-dashboard-filter');
    Route::get('/customers', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showLeads'])->name('leads');
    Route::post('/customers', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'createLead']);
    Route::get('/bulk-import-leads', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showBulkImportLeads'])->name('bulk-import-leads');

    Route::match(['POST', 'GET'],'/new-customer', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showAddNewCustomerForm'])->name('show-new-lead-form');
    Route::post('/bulk-lead-import', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'processLeadBulkImport'])->name("bulk-lead-import");
    Route::get('/manage-bulk-lead-list', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'manageBulkLeadList'])->name("manage-bulk-lead-list");
    Route::get('/manage-bulk-lead-list/{batchCode}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showBulkLeadImportDetails'])->name("view-bulk-lead-details");
    Route::get('/leads/{slug}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'leadProfile'])->name('lead-profile');
    Route::post('/edit-leads', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'editLeadProfile'])->name('edit-lead-profile');
    Route::post('/edit-organization-profile', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'editOrganizationProfile'])->name('edit-organization-profile');
    Route::post('/save-partner-changes', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'savePartnerChanges'])->name('save-partner-changes');

    Route::post('/leave-lead-note', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'leaveLeadNote'])->name('leave-lead-note');
    Route::post('/edit-lead-note', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'editLeadNote'])->name('edit-lead-note');
    Route::post('/delete-lead-note', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'deleteLeadNote'])->name('delete-lead-note');
    Route::get('/delete-lead-entry/{recordId}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'deleteLeadRecord'])->name('delete-lead-record');
    Route::get('/discard-lead-record/{batchCode}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'discardLeadRecord'])->name('discard-lead-record');
    Route::get('/post-lead-record/{batchCode}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'postLeadRecord'])->name('post-lead-record');
    #Follow-up
    Route::get('/schedule-follow-up', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showScheduleFollowupForm'])->name('schedule-follow-up');
    Route::get('/schedule-follow-up-preview', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showScheduleFollowupPreview'])->name('schedule-follow-up-preview');
    Route::get('/submit-follow-up-schedule', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'processFollowupSchedule'])->name('submit-follow-up-schedule');
    Route::get('/manage-schedule', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'manageFollowupSchedule'])->name('manage-schedule');
    Route::get('/manage-schedule/{refCode}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showFollowupDetails'])->name('view-followup-details');
    Route::post('/rate-followup-schedule', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'rateFollowupSchedule'])->name('rate-followup-schedule');


    Route::get('/messaging', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showMessaging'])->name('marketing-messaging');
    Route::get('/compose-messaging', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showComposeMessaging'])->name('marketing-compose-messaging');
    Route::post('/compose-messaging', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'storeMessage']);
    Route::get('/automations', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showAutomations'])->name('marketing-automations');
    Route::get('/create-automation', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showCreateAutomation'])->name('marketing-create-automation');
    Route::post('/create-automation', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'storeAutomation']);
    Route::get('/edit-marketing-automation/{slug}', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showEditAutomationForm'])->name('edit-marketing-automation');
    Route::post('/save-marketing-automation-changes', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'editAutomation'])->name('save-marketing-automation-changes');
});

Route::group(['prefix'=>'/attendance', 'middleware'=>'auth'], function(){
    Route::get('/', [App\Http\Controllers\Portal\CalendarController::class, 'showAttendance'])->name('attendance');
    Route::post('/', [App\Http\Controllers\Portal\CalendarController::class, 'publishAttendance']);
    Route::post('/edit-attendance', [App\Http\Controllers\Portal\CalendarController::class, 'publishAttendance'])->name('edit-attendance');
    Route::get('/chart-attendance', [App\Http\Controllers\Portal\CalendarController::class, 'getAttendanceChart'])->name('chart-attendance');
});

Route::group(['prefix'=>'workflow', 'middleware'=>'auth'], function(){
    Route::get('/', [App\Http\Controllers\Portal\WorkflowController::class, 'showWorkflowView'])->name('workflow');
    Route::post('/', [App\Http\Controllers\Portal\WorkflowController::class, 'storeWorkflowRequest']);
    Route::get('/{slug}', [App\Http\Controllers\Portal\WorkflowController::class, 'viewWorkflowRequest'])->name('view-workflow');
    Route::post('/comment-on-post', [App\Http\Controllers\Portal\WorkflowController::class, 'commentOnPost'])->name('comment-on-post');
    Route::post('/update-workflow', [App\Http\Controllers\Portal\WorkflowController::class, 'updateWorkflowStatus'])->name('update-workflow');

});

Route::group(['prefix'=>'newsfeed', 'middleware'=>'auth'], function(){
   Route::get('/', [App\Http\Controllers\Portal\TimelineController::class, 'showTimeline'])->name('timeline');
   Route::get('/birthdays', [App\Http\Controllers\Portal\TimelineController::class, 'showBirthdays'])->name('birthdays');
   Route::post('/publish-timeline-post', [App\Http\Controllers\Portal\TimelineController::class, 'storeTimelinePost'])->name('publish-timeline-post');
   Route::get('/post/{slug}', [App\Http\Controllers\Portal\TimelineController::class, 'readTimelinePost'])->name('read-timeline-post');
   Route::post('post-comment', [App\Http\Controllers\Portal\TimelineController::class, 'postComment'])->name('post-comment');
});

Route::group(['prefix'=>'/bulk-sms', 'middleware'=>'auth'],function(){

    Route::get('/test', [App\Http\Controllers\Portal\SMSController::class, 'dateTest']);
    //Route::get('/', [App\Http\Controllers\UserController::class, 'customerDashboard'])->name('customer-dashboard');
    Route::get('/fund-wallet', [App\Http\Controllers\Portal\SMSController::class, 'showTopUpForm'])->name('top-up');
    Route::post('/fund-wallet', [App\Http\Controllers\Portal\SMSController::class, 'processTopUpRequest']);
    Route::get('/fund-wallet/transactions', [App\Http\Controllers\Portal\SMSController::class, 'showTopUpTransactions'])->name('top-up-transactions');
    Route::get('/compose-sms', [App\Http\Controllers\Portal\SMSController::class, 'showComposeMessageForm'])->name('compose-sms');
    Route::get('/preview-message',[App\Http\Controllers\Portal\SMSController::class, 'previewMessage'])->name('preview-message');
    Route::post('/inline-preview-message',[App\Http\Controllers\Portal\SMSController::class, 'inlinePreviewMessage'])->name('inline-preview-message');
    Route::post('/send-text-message',[App\Http\Controllers\Portal\SMSController::class, 'sendTextMessage'])->name('send-text-message');

    Route::get('/schedule-sms', [App\Http\Controllers\Portal\SMSController::class, 'showScheduleSmsForm'])->name('schedule-sms');
    Route::get('/api-settings', [App\Http\Controllers\Portal\SMSController::class, 'showApiInterface'])->name('api-settings');

    Route::get('/senders/create', [App\Http\Controllers\Portal\SMSController::class, 'showSenderIdForm'])->name('create-senders');
    Route::post('/senders/create', [App\Http\Controllers\Portal\SMSController::class, 'createSenderId']);
    Route::get('/senders/registered', [App\Http\Controllers\Portal\SMSController::class, 'showRegisteredSenderIds'])->name('registered-senders');

    Route::get('/phone-groups',[App\Http\Controllers\Portal\SMSController::class, 'showPhoneGroupForm'])->name('phone-groups');
    Route::post('/phone-groups',[App\Http\Controllers\Portal\SMSController::class, 'setNewPhoneGroup']);
    Route::post('/edit-phone-group',[App\Http\Controllers\Portal\SMSController::class, 'updatePhoneGroup'])->name('edit-phone-group');

    Route::get('/batch-report', [App\Http\Controllers\Portal\SMSController::class, 'batchReport'])->name('batch-report');

    Route::get('/messages', [App\Http\Controllers\Portal\SMSController::class, 'showBulksmsMessages'])->name('bulksms-messages');
    Route::post('/update-message-status', [App\Http\Controllers\Portal\SMSController::class, 'updateMessageStatus'])->name('update-message-status');

    //Route::post('/regenerate-api-token',[App\Http\Controllers\UserController::class, 'reGenerateApiToken'])->name('regenerate-api-token');
});


Route::group(['prefix'=>'/reports', 'middleware'=>'auth'],function(){
    Route::get('/appointments', [App\Http\Controllers\Portal\ReportsController::class, 'showAppointmentReports'])->name('appointment-reports');
    Route::get('/filter-appointment-reports', [App\Http\Controllers\Portal\ReportsController::class, 'filterAppointments'])->name('filter-appointment-reports');
    Route::get('/revenues', [App\Http\Controllers\Portal\ReportsController::class, 'showRevenueReports'])->name('show-revenue-reports');
    Route::get('/revenue-statistics', [App\Http\Controllers\Portal\ReportsController::class, 'getSalesReportStatistics'])->name('revenue-statistics');
    Route::get('/revenue-statistics-range', [App\Http\Controllers\Portal\ReportsController::class, 'getSalesReportStatisticsRange'])->name('revenue-statistics-range');
    Route::get('/filter-sales-report', [App\Http\Controllers\Portal\ReportsController::class, 'filterSalesRevenueReport'])->name('filter-sales-report');
    Route::get('/practitioners', [App\Http\Controllers\Portal\ReportsController::class, 'showPractitionerReport'])->name('practitioner-report');
    Route::get('/filter-practitioner-report', [App\Http\Controllers\Portal\ReportsController::class, 'filterPractitionerReport'])->name('filter-practitioner-report');
    Route::get('/clients', [App\Http\Controllers\Portal\ReportsController::class, 'showClientReport'])->name('client-report');
    Route::get('/followup-dashboard-chart', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showFollowupDashboardStatistics'])->name('followup-dashboard-chart');
    //Route::get('/followup-dashboard-chart-range', [App\Http\Controllers\Portal\SalesnMarketingController::class, 'showFollowupDashboardStatisticsRange'])->name('followup-dashboard-chart-range');

});

Route::group(['prefix'=>'/follow-up', 'middleware'=>'auth'], function(){
    Route::post('/add-follow-up', [App\Http\Controllers\Portal\MedicationController::class, 'addMedication'])->name('add-medication');
    Route::post('/edit-follow-up', [App\Http\Controllers\Portal\MedicationController::class, 'editMedication'])->name('edit-medication');
    Route::get('/follow-up-details/{slug}', [App\Http\Controllers\Portal\MedicationController::class, 'showMedicationDetails'])->name('medication-details');
    Route::post('/follow-up-report', [App\Http\Controllers\Portal\MedicationController::class, 'submitMedicationReport'])->name('medication-report');
});

Route::group(['prefix'=>'/website', 'middleware'=>'auth'], function(){
    Route::get('/homepage', [App\Http\Controllers\Portal\WebsiteController::class, 'showWebsiteHomepage'])->name('website-homepage');
    Route::get('/settings', [App\Http\Controllers\Portal\WebsiteController::class, 'showWebsiteSettings'])->name('website-settings');
    Route::get('/forms', [App\Http\Controllers\Portal\WebsiteController::class, 'showWebsiteForms'])->name('website-forms');
    Route::get('/create-form', [App\Http\Controllers\Portal\WebsiteController::class, 'showCreateWebsiteForm'])->name('create-website-form');
    Route::post('/create-form', [App\Http\Controllers\Portal\WebsiteController::class, 'CreateWebsiteForm']);
    Route::get('/edit-form/{slug}', [App\Http\Controllers\Portal\WebsiteController::class, 'showEditWebsiteForm'])->name('edit-website-form');
    Route::get('/view-form/{slug}', [App\Http\Controllers\Portal\WebsiteController::class, 'viewWebsiteForm'])->name('view-website-form');
    Route::get('/web-pages', [App\Http\Controllers\Portal\WebsiteController::class, 'showOrgWebpages'])->name('web-pages');
    Route::get('/web-pages/create', [App\Http\Controllers\Portal\WebsiteController::class, 'showCreateWebPageForm'])->name('create-web-page');
    Route::post('/web-pages/create', [App\Http\Controllers\Portal\WebsiteController::class, 'CreateWebPage']);
    Route::post('/web-pages/homepage-settings', [App\Http\Controllers\Portal\WebsiteController::class, 'updateHomepageSettings'])->name('website-homepage-settings');
    Route::post('/web-pages/add-service', [App\Http\Controllers\Portal\WebsiteController::class, 'addService'])->name('add-website-service');
    Route::post('/web-pages/edit-service', [App\Http\Controllers\Portal\WebsiteController::class, 'editService'])->name('edit-website-service');
});

//Radio License activities

Route::group(['prefix'=>'/sales', 'middleware'=>'auth'], function(){
    Route::get('/workstations', [App\Http\Controllers\Portal\RadioController::class, 'showWorkstations'])->name('show-workstations');
    Route::get('/new-workstation', [App\Http\Controllers\Portal\RadioController::class, 'showCreateWorkstationForm'])->name('show-create-workstation');
    Route::post('/new-workstation', [App\Http\Controllers\Portal\RadioController::class, 'storeWorkstation']);
    Route::post('/edit-workstation', [App\Http\Controllers\Portal\RadioController::class, 'editWorkstation'])->name('edit-workstation');
    Route::get('/station/{slug}', [App\Http\Controllers\Portal\RadioController::class, 'showWorkstation'])->name('show-workstation-details');
    #User New application
    Route::get('/new', [App\Http\Controllers\Portal\RadioController::class, 'showNewApplicationForm'])->name('new-radio-application');
    Route::post('/new-license-application', [App\Http\Controllers\Portal\RadioController::class, 'storeNewRadioApplication'])->name('new-license-application');
    Route::get('/applications', [App\Http\Controllers\Portal\RadioController::class, 'showManageApplications'])->name('manage-applications');
    Route::get('/applications/{slug}', [App\Http\Controllers\Portal\RadioController::class, 'showManageApplicationDetails'])->name('show-application-details');
    Route::get('/generate-invoice/{slug}', [App\Http\Controllers\Portal\RadioController::class, 'showGenerateInvoice'])->name('generate-invoice');
    Route::post('/generate-invoice', [App\Http\Controllers\Portal\RadioController::class, 'generateInvoice'])->name('new-invoice');

    #Invoice
    Route::get('/new-invoice', [App\Http\Controllers\Portal\RadioController::class,'showNewInvoiceForm'])->name('new-invoice');
    Route::post('/new-invoice', [App\Http\Controllers\Portal\RadioController::class,'storeNewInvoice']);
    Route::get('/list/{invoices}', [App\Http\Controllers\Portal\RadioController::class,'showManageInvoices'])->name('manage-invoices');
    Route::post('/get-invoice', [App\Http\Controllers\Portal\RadioController::class,'getInvoice'])->name('get-invoice');
    Route::get('/invoices/{slug}', [App\Http\Controllers\Portal\RadioController::class,'showInvoiceDetails'])->name('show-invoice-detail');
    Route::post('/submit-proof-of-payment', [App\Http\Controllers\Portal\RadioController::class,'submitProofOfPayment'])->name('submit-proof-of-payment');
    Route::post('/action-payment', [App\Http\Controllers\Portal\RadioController::class,'actionPayment'])->name('action-payment');
    Route::post('/action-receipt-payment', [App\Http\Controllers\Portal\RadioController::class,'actionReceiptPayment'])->name('action-receipt-payment');
    Route::match(['GET', 'POST', 'PUT'],'/invoice-service', [App\Http\Controllers\Portal\RadioController::class,'handleInvoiceService'])->name('invoice-service');
    Route::get('/receive-payment/{slug}', [App\Http\Controllers\Portal\RadioController::class, 'receivePayment'])->name('receive-payment');
    Route::post('/process-payment', [App\Http\Controllers\Portal\RadioController::class, 'processPayment'])->name('process-payment');
    Route::match(['GET', 'POST'],'/post-invoice', [App\Http\Controllers\Portal\RadioController::class, 'showInvoiceForPosting'])->name('post-invoice');
    Route::match(['GET', 'POST'],'/post-receipt', [App\Http\Controllers\Portal\RadioController::class, 'showReceiptForPosting'])->name('post-receipt');

    #Receipts
    Route::post('/get-receipt', [App\Http\Controllers\Portal\RadioController::class,'getReceipt'])->name('get-receipt');
    Route::get('/receipts/all', [App\Http\Controllers\Portal\RadioController::class, 'showManageReceipts'])->name('show-manage-receipts');
    Route::get('/refunds/all', [App\Http\Controllers\Portal\RadioController::class, 'showAllRefunds'])->name('show-all-refunds');
    Route::get('/new-receipt', [App\Http\Controllers\Portal\RadioController::class, 'showNewReceiptForm'])->name('show-new-receipt-form');
    Route::post('/new-receipt', [App\Http\Controllers\Portal\RadioController::class, 'storeReceipt']);
    Route::get('/new-refund', [App\Http\Controllers\Portal\RadioController::class, 'showNewRefundForm'])->name('show-new-refund-form');
    Route::post('/new-refund', [App\Http\Controllers\Portal\RadioController::class, 'storeRefund']);
    Route::get('/refund/{type}/{id}', [App\Http\Controllers\Portal\RadioController::class, 'actionRefund'])->name('action-refund');
    Route::get('/manage-refund-requests', [App\Http\Controllers\Portal\RadioController::class, 'manageRefundRequests'])->name('manage-refund-requests');
    Route::get('/receipts/{slug}', [App\Http\Controllers\Portal\RadioController::class, 'showManageReceiptDetails'])->name('view-receipt');
    Route::get('/receipt/approve/{ref}', [App\Http\Controllers\Portal\RadioController::class, 'approveReceipt'])->name('approve-receipt');
    Route::get('/receipt/decline/{ref}', [App\Http\Controllers\Portal\RadioController::class, 'declineReceipt'])->name('decline-receipt');
    Route::get('/receipt/send-receipt-as-email/{ref}', [App\Http\Controllers\Portal\RadioController::class, 'sendReceiptAsEmail'])->name('send-receipt-as-email');

    #Show application by status
    Route::get('/category/{type}', [App\Http\Controllers\Portal\RadioController::class, 'showApplicationCategory'])->name('show-application-status');
    Route::get('/assign-license/{slug}', [App\Http\Controllers\Portal\RadioController::class, 'showAssignLicense'])->name('show-assign-license');
    Route::post('assign-frequency', [App\Http\Controllers\Portal\RadioController::class, 'assignFrequency'])->name('assign-frequency');
    Route::get('review/assignment/{slug}', [App\Http\Controllers\Portal\RadioController::class, 'showReviewFrequencyAssignment'])->name('review-assignment');
    Route::post('update-frequency-assignment', [App\Http\Controllers\Portal\RadioController::class, 'updateFrequencyAssignment'])->name('update-frequency-assignment');

    #Certificates
    Route::get('/license/certificate/{type}', [App\Http\Controllers\Portal\RadioController::class, 'showCertificates'])->name('certificates');
    Route::get('/certificates/{slug}', [App\Http\Controllers\Portal\RadioController::class, 'showCertificateDetails'])->name('certificate-details');
});

Route::group(['prefix'=>'tickets', 'middleware'=>'auth'],function(){
    Route::get('/', [App\Http\Controllers\Portal\TicketController::class, 'showTickets'] )->name('tickets');
    Route::post('/', [App\Http\Controllers\Portal\TicketController::class, 'newTicket'] );
    Route::get('/{slug}', [App\Http\Controllers\Portal\TicketController::class, 'showTicketDetails'] )->name('view-ticket');
    Route::post('/reply-ticket', [App\Http\Controllers\Portal\TicketController::class, 'submitTicketReply'] )->name('ticket-reply');
    Route::post('/close-ticket', [App\Http\Controllers\Portal\TicketController::class, 'closeTicket'] )->name('close-ticket');
});
Route::get('/faqs', [App\Http\Controllers\Portal\TicketController::class, 'showFaqs'])->name('faqs');
Route::post('/faqs', [App\Http\Controllers\Portal\TicketController::class, 'postFaq']);
Route::post('/edit-faq', [App\Http\Controllers\Portal\TicketController::class, 'editFaq'])->name('edit-faq');

Route::group(['prefix'=>'persons', 'middleware'=>'auth'], function(){
    Route::get('/', [App\Http\Controllers\Portal\PersonsController::class, 'showPersons'])->name("persons");
    Route::get('/add', [App\Http\Controllers\Portal\PersonsController::class, 'showAddPersonForm'])->name("add-person");
    Route::post('/add', [App\Http\Controllers\Portal\PersonsController::class, 'addNewPerson']);
    Route::get('/view/{slug}', [App\Http\Controllers\Portal\PersonsController::class, 'showPersonProfile'])->name('person-profile');
    Route::post('/update-person-profile', [App\Http\Controllers\Portal\PersonsController::class, 'updatePersonProfile'])->name('update-person-profile');
});

Route::group(['prefix'=>'company', 'middleware'=>'auth'], function(){
    Route::get('/list/{slug}', [App\Http\Controllers\Portal\CompanyController::class, 'showCompanies'])->name('list-companies');
    Route::get('/company/{tag}', [App\Http\Controllers\Portal\CompanyController::class, 'showCompanyProfileByURL'])->name('show-company-profile');
    //Route::get('/profile/{slug}', [App\Http\Controllers\Portal\CompanyController::class, 'showCompanyProfileByTag'])->name('show-company-profile');
    Route::get('/profile', [App\Http\Controllers\Portal\CompanyController::class, 'showCompanyProfile'])->name('company-profile');
    Route::post('/upload-document', [App\Http\Controllers\Portal\CompanyController::class, 'uploadDocument'])->name('upload-document');
    Route::post('/update-company-profile', [App\Http\Controllers\Portal\CompanyController::class, 'updateCompanyProfile'])->name('update-company-profile');
    Route::post('/manage', [App\Http\Controllers\Portal\CompanyController::class, 'showManageCompanies'])->name('manage-companies');
});


Route::group(['prefix'=>'/newsfeed', 'middleware'=>'auth'], function(){

});

Route::group(['prefix'=>'/users', 'middleware'=>'auth'], function(){
    Route::get('/practitioners', [App\Http\Controllers\UserController::class, 'showPractitioners'])->name('practitioners');
    Route::get('/employees', [App\Http\Controllers\UserController::class, 'showAdministrators'])->name('pastors');
    Route::post('/get-lgas', [App\Http\Controllers\UserController::class,'getLocalGovernments'])->name('get-lgas');
    Route::get('/employees/add-new', [App\Http\Controllers\UserController::class, 'showAddNewPastorForm'])->name('add-new-pastor');
    Route::get('/{slug}', [App\Http\Controllers\UserController::class, 'showUserProfile'])->name('user-profile');
    Route::post('/assign-revoke-role', [App\Http\Controllers\UserController::class, 'assignRevokeRole'])->name('assign-revoke-role');
    Route::post('/add-new-user', [App\Http\Controllers\UserController::class, 'addNewUser'])->name('add-new-user');

    Route::get('/update-user-record/{slug}', [App\Http\Controllers\UserController::class, 'showUpdateUserRecord'])->name('show-update-user-record');
    Route::post('/update-user-record', [App\Http\Controllers\UserController::class, 'updateUserRecord'])->name('update-user-record');

    Route::post('/update-user-account', [App\Http\Controllers\UserController::class, 'updateUserProfile'])->name('update-user-account');
    Route::post('/delete-user', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('delete-user');
    Route::post('/grant-permission', [App\Http\Controllers\UserController::class, 'grantPermission'])->name('grant-permission');
    Route::post('/switch-wallpaper', [App\Http\Controllers\UserController::class, 'switchWallpaper'])->name('switch-wallpaper');

    Route::get('/show-payslip/', [App\Http\Controllers\Portal\ClientController::class, 'showPayslip'])->name('show-payslip');
    Route::get('/payslip-report', [App\Http\Controllers\Portal\ClientController::class, 'payslipReport'])->name('payslip-report');
});


Route::group(['prefix'=>'accounting', 'middleware'=>'auth'],function() {
    Route::get('/chart-of-accounts', [App\Http\Controllers\AccountingController::class, 'chartOfAccounts'])->name('chart-of-accounts');
    Route::get('/create-major-transaction-accounts', [App\Http\Controllers\AccountingController::class, 'createMajorTransactionAccounts'])->name('create-major-transaction-accounts');
    Route::get('/new/chart-of-account', [App\Http\Controllers\AccountingController::class, 'showNewChartOfAccountForm'])->name('new-chart-of-account');
    Route::post('/new/chart-of-account', [App\Http\Controllers\AccountingController::class, 'addNewChartOfAccount']);
    Route::post('/get-parent-account', [App\Http\Controllers\AccountingController::class, 'getParentAccount']);
    Route::get('/settings', [App\Http\Controllers\AccountingController::class, 'accountSettings'])->name('account-settings');
    Route::post('/account-settings', [App\Http\Controllers\AccountingController::class, 'setDefaultAccounts'])->name('store-account-settings');
    Route::post('/payment-integration-setup', [App\Http\Controllers\AccountingController::class, 'paymentIntegrationSetup'])->name('payment-integration-setup');
    #Receipt routes
    Route::get('/manage-receipts', [App\Http\Controllers\AccountingController::class, 'manageReceipts'])->name('manage-receipts');
    Route::get('/manage/receipt/{ref}', [App\Http\Controllers\AccountingController::class, 'viewReceipt'])->name('view-receipt-detail');
    Route::get('manage/receipt/approve/{ref}', [App\Http\Controllers\AccountingController::class, 'approveReceipt'])->name('approve-receipt');
    Route::get('manage/receipt/decline/{ref}', [App\Http\Controllers\AccountingController::class, 'declineReceipt'])->name('decline-receipt');
    #Invoice routes
    //Route::get('/manage-invoices', [App\Http\Controllers\AccountingController::class, 'manageInvoices'])->name('manage-invoices');
    Route::get('/generate-new-invoice', [App\Http\Controllers\AccountingController::class, 'showGenerateNewInvoiceForm'])->name('generate-new-invoice');
    Route::post('/generate-new-invoice', [App\Http\Controllers\AccountingController::class, 'generateNewInvoice']);
    Route::get('/view-invoice/{slug}', [App\Http\Controllers\AccountingController::class, 'viewInvoice'])->name('view-invoice');
    Route::get('/decline-invoice/{slug}', [App\Http\Controllers\AccountingController::class, 'declineInvoice'])->name('decline-invoice');
    Route::get('/approve-invoice/{slug}', [App\Http\Controllers\AccountingController::class, 'approveInvoice'])->name('approve-invoice');
    Route::get('/send-invoice-via-email/{slug}', [App\Http\Controllers\AccountingController::class, 'sendInvoiceAsEmail'])->name('send-invoice-via-email');
    //Route::get('/receive-payment/{slug}', [App\Http\Controllers\AccountingController::class, 'receivePayment'])->name('receive-payment');
    Route::post('/process-offline-receipt-payment', [App\Http\Controllers\AccountingController::class, 'processOfflineNewReceiptPayment'])->name('process-offline-payment');

    /*Route::get('/generate-receipt', [App\Http\Controllers\Manager\AccountingController::class, 'showGenerateReceipt']);
    Route::post('/generate-receipt', 'AccountingController@storeReceipt');
    Route::post('/get-debit-note-details', 'AccountingController@getDebitNoteDetails');*/


    #Report routes
    Route::get('/trial-balance', [App\Http\Controllers\AccountingController::class, 'showTrialBalance'])->name('trial-balance');
    Route::post('/trial-balance', [App\Http\Controllers\AccountingController::class, 'trialBalance']);
    Route::get('/statement-of-account', [App\Http\Controllers\AccountingController::class, 'showBalanceSheet'])->name('balance-sheet');
    Route::post('/statement-of-account', [App\Http\Controllers\AccountingController::class, 'balanceSheet']);

    Route::get('/income-statement', [App\Http\Controllers\AccountingController::class, 'showProfitOrLoss'])->name('profit-or-loss');
    Route::post('/income-statement', [App\Http\Controllers\AccountingController::class, 'profitOrLoss']);
    Route::get('/journal-voucher', [App\Http\Controllers\AccountingController::class, 'showJournalVoucher'])->name('journal-voucher');
    Route::post('/journal-voucher', [App\Http\Controllers\AccountingController::class, 'setNewJournalEntry']);
});


/*
Route::group(['prefix'=>'/accounting', 'middleware'=>'auth', 'as'=>'accounting.'], function(){

    Route::get('/categories', [App\Http\Controllers\Portal\AccountingController::class, 'showManageCategories'])->name('categories');
    Route::post('/categories', [App\Http\Controllers\Portal\AccountingController::class, 'addTransactionCategory']);
    Route::post('/edit-category', [App\Http\Controllers\Portal\AccountingController::class, 'editTransactionCategory'])->name('edit-category');
    Route::get('/chart-of-accounts', [App\Http\Controllers\Portal\AccountingController::class, 'showChartOfAccounts'])->name('chart-of-accounts');
    Route::get('/add-new-account', [App\Http\Controllers\Portal\AccountingController::class, 'showCreateChartOfAccountForm'])->name('add-new-account');
    Route::post('/add-new-account', [App\Http\Controllers\Portal\AccountingController::class, 'saveAccount']);
    Route::post('/get-account-type', [App\Http\Controllers\Portal\AccountingController::class, 'getParentAccount'])->name('get-account-type');
    Route::get('/journal-voucher', [App\Http\Controllers\Portal\AccountingController::class, 'showJournalVoucherForm'])->name('journal-voucher');

    #Cashbook routes
    Route::get('/accounts', [App\Http\Controllers\Portal\CashbookController::class, 'showManageAccounts'])->name('accounts');
    Route::post('/accounts', [App\Http\Controllers\Portal\CashbookController::class, 'addCashBook']);
});*/

Route::group(['prefix'=>'app', 'middleware'=>'auth'],function(){
    Route::prefix('/settings')->group(function(){
        Route::get('/church', \App\Http\Livewire\Portal\Settings\Organization::class)->name('organization');
        Route::get('/account', \App\Http\Livewire\Portal\Settings\Account::class)->name('account-settings');
        Route::get('/module-manager', \App\Http\Livewire\Portal\Settings\ModuleManager::class)->name('module-manager');


        Route::get('/sms-settings', [App\Http\Controllers\Portal\SettingsController::class, 'showSMSSettingsForm'])->name('sms-settings');
        Route::post('/sms-settings', [App\Http\Controllers\Portal\SettingsController::class, 'appDefaultSmsSettings']);
        Route::get('/accounting-settings', [App\Http\Controllers\Portal\SettingsController::class, 'showWorkflowSettingsForm'])->name('accounting-settings');
        Route::post('/estate-accounting-settings', [App\Http\Controllers\Portal\SettingsController::class, 'updateEstateAccounts'])->name('estate-accounting-settings');
        Route::post('/accounting-settings', [App\Http\Controllers\Portal\SettingsController::class, 'appDefaultSettings']);


        Route::get('/manage-roles', [App\Http\Controllers\Portal\SettingsController::class, 'manageRoles'])->name('manage-roles');
        Route::post('/add-role', [App\Http\Controllers\Portal\SettingsController::class, 'storeRole'])->name('add-role');
        Route::post('/update-role-permissions', [App\Http\Controllers\Portal\SettingsController::class, 'updateRolePermissions'])->name('update-role-permissions');
        Route::post('/add-permission', [App\Http\Controllers\Portal\SettingsController::class, 'storePermission'])->name('add-permission');
        Route::post('/edit-permission', [App\Http\Controllers\Portal\SettingsController::class, 'editPermission'])->name('edit-permission');
        Route::get('/manage-permissions', [App\Http\Controllers\Portal\SettingsController::class, 'managePermissions'])->name('manage-permissions');
        Route::post('/save-logo', [App\Http\Controllers\Portal\SettingsController::class, 'saveLogo'])->name('save-logo');
        Route::post('/save-favicon', [App\Http\Controllers\Portal\SettingsController::class, 'saveFavicon'])->name('save-favicon');
        Route::get('/general-settings', [App\Http\Controllers\Portal\SettingsController::class, 'generalSettings'])->name('general-settings');
        Route::get('/purchase-or-upgrade-plan', \App\Http\Livewire\Portal\Settings\PurchaseUpgradePlan::class)->name('purchase-or-upgrade-plan');
    });
});
//Route::get('/', [App\Http\Controllers\Controller::class, 'showOrganizationPageDetails'])->name('homepage');


//Route::get('/', [App\Http\Controllers\Controller::class, 'homepage'])->name('homepage');
Route::get('/', function(){
    return redirect()->route('login');
})->name('homepage');


Route::group(['domain'=>'{account}.'.env('APP_URL')],function(){
    Route::get('/', function(){
        return "Thanks";
    })->name('org-homepage');
    Route::post('/process-frontend-form', [App\Http\Controllers\Controller::class, 'processFrontendForm'])->name('process-frontend-form');
    Route::get('/contact-us', [App\Http\Controllers\Controller::class, 'contactUs'])->name('contact-org');
});


Route::group(['prefix'=>'estates', 'middleware'=>'auth'],function(){
   Route::match(['GET', 'POST'], '/', [App\Http\Controllers\Portal\EsateController::class, 'showEstates'])->name('estates');
   Route::match(['GET'], '/view/{slug}', [App\Http\Controllers\Portal\EsateController::class, 'showEstateView'])->name('show-estate-view');
   Route::post('/estate-101', [App\Http\Controllers\Portal\EsateController::class, 'EstateById'])->name('estate-info');
});

Route::group(['prefix'=>'property','middleware'=>'auth'],function(){
   Route::match(['GET', 'POST'],'/new', [App\Http\Controllers\Portal\PropertyController::class, 'showAddProperty'])->name('add-new-property');
   Route::match(['GET', 'POST'],'/property-reservation', [App\Http\Controllers\Portal\PropertyController::class, 'propertyReservation'])->name('property-reservation');
   Route::match(['GET', 'POST'],'/manage-property-reservation-requests', [App\Http\Controllers\Portal\PropertyController::class, 'managePropertyReservation'])->name('manage-property-reservation-requests');
    Route::get('/reservation/{type}/{id}', [App\Http\Controllers\Portal\PropertyController::class, 'actionReservation'])->name('action-reservation');
    Route::post('/get-property', [App\Http\Controllers\Portal\PropertyController::class,'getProperty'])->name('get-property');

   Route::get('/show/{type}', [App\Http\Controllers\Portal\PropertyController::class, 'showManagePropertiesView'])->name('manage-properties');
   Route::get('/manage/{slug}', [App\Http\Controllers\Portal\PropertyController::class, 'showPropertyDetails'])->name('show-property-details');
    Route::post('/property/delete/image', [App\Http\Controllers\Portal\PropertyController::class, 'deletePropertyImage'])->name('delete-property-image');
    Route::post('/update-property-details', [App\Http\Controllers\Portal\PropertyController::class, 'updatePropertyDetails'])->name('update-property-details');
    Route::match(['GET', 'POST'],'/import/properties', [App\Http\Controllers\Portal\PropertyController::class, 'showBulkPropertyImportForm'])->name('show-bulk-property-import-form');
    Route::match(['GET'],'/show/import/properties', [App\Http\Controllers\Portal\PropertyController::class, 'manageImportedProperties'])->name('show-imported-properties');
    Route::match(['GET'],'/show/import/properties/{batchCode}', [App\Http\Controllers\Portal\PropertyController::class, 'showImportedPropertiesDetailList'])->name('show-imported-properties-details');
    Route::match(['POST'],'update-imported-properties', [App\Http\Controllers\Portal\PropertyController::class, 'updateImportedProperties'])->name('update-imported-properties');
    Route::match(['POST', 'GET'],'property-allocation', [App\Http\Controllers\Portal\PropertyController::class, 'showPropertyAllocation'])->name('property-allocation');
    Route::match(['POST'],'property-list', [App\Http\Controllers\Portal\PropertyController::class, 'showProperties'])->name('property-list');
    Route::match(['GET','POST'],'manage-allocations', [App\Http\Controllers\Portal\PropertyController::class, 'managePropertyAllocation'])->name('manage-property-allocations');


    Route::get('/action-property-entry/{recordId}/{action}', [App\Http\Controllers\Portal\PropertyController::class, 'actionPropertyRecord'])->name('action-property-record');
    Route::get('/discard-property-record/{batchCode}', [App\Http\Controllers\Portal\PropertyController::class, 'discardPropertyRecord'])->name('discard-property-record');
    Route::get('/post-property-record/{batchCode}', [App\Http\Controllers\Portal\PropertyController::class, 'postPropertyRecord'])->name('post-property-record');
});


Route::group(['prefix'=>'payroll', 'middleware'=>'auth'],function(){
    Route::match(['GET', 'POST', 'PUT'], '/payment-definition', [App\Http\Controllers\Portal\PayrollController::class, 'handlePaymentDefinition'])->name('payment-definition');
    Route::match(['GET', 'POST', 'PUT'], '/salary-structure', [App\Http\Controllers\Portal\PayrollController::class, 'handleSalaryStructure'])->name('salary-structure');
    Route::match(['GET'], '/new-salary-allowance', [App\Http\Controllers\Portal\PayrollController::class, 'showNewSalaryAllowanceForm'])->name('new-salary-allowance');
    Route::match(['GET', 'POST', 'PUT'], '/salary-allowances', [App\Http\Controllers\Portal\PayrollController::class, 'handleSalaryAllowances'])->name('salary-allowances');
    Route::match(['GET'], '/salary-allowances/{slug}', [App\Http\Controllers\Portal\PayrollController::class, 'showSalaryAllowances'])->name('view-salary-allowances');
    Route::match(['GET', 'POST'], '/payroll-month-year', [App\Http\Controllers\Portal\PayrollController::class, 'showPayrollMonthYear'])->name('payroll-month-year');

    Route::group(['prefix'=>'process'],function(){
       Route::match(['GET'], '/salary-structures', [App\Http\Controllers\Portal\PayrollController::class, 'showSalaryStructures'])->name('salary-structures');
       Route::get( '/salary-structures/{slug}', [App\Http\Controllers\Portal\PayrollController::class, 'showSalarySetupForm'])->name('salary-setup-form');
       Route::get( '/employee/salary-structure/{slug}', [App\Http\Controllers\Portal\PayrollController::class, 'showEmployeeSalaryStructure'])->name('employee-salary-structure');
       Route::get( '/employee/edit-salary-structure/{slug}', [App\Http\Controllers\Portal\PayrollController::class, 'editEmployeeSalaryStructure'])->name('edit-salary-structure');
       Route::post( '/setup-salary-structure', [App\Http\Controllers\Portal\PayrollController::class, 'setupSalaryStructure'])->name('setup-salary-structure');
       Route::post( '/update-salary-structure', [App\Http\Controllers\Portal\PayrollController::class, 'updateSalaryStructure'])->name('update-salary-structure');
        Route::match(['GET', 'POST'], '/approve-payroll-routine', [App\Http\Controllers\Portal\PayrollController::class, 'showApprovePayrollRoutineView'])->name('approve-payroll-routine');
        Route::match(['GET', 'POST'], '/payroll-routine', [App\Http\Controllers\Portal\PayrollController::class, 'showPayrollRoutine'])->name('payroll-routine');
        Route::get( '/run-payroll-routine', [App\Http\Controllers\Portal\PayrollController::class, 'runPayrollRoutine'])->name('run-payroll-routine');
        Route::get( '/operation/{action}/{batch_code}', [App\Http\Controllers\Portal\PayrollController::class, 'approveOrUndoPayrollRoutine'])->name('action-routine');
        Route::get( '/payroll-report', [App\Http\Controllers\Portal\PayrollController::class, 'showPayrollReportForm'])->name('payroll-report');
    });

});
