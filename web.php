<?php

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

Route::redirect('/admin', '/backend');

Auth::routes(['verify' => true]);

Route::get('login/{provider}', 'Auth\LoginController@redirect')->name('login.socialite');
Route::get('login/{provider}/callback', 'Auth\LoginController@callback');

Route::view('register/success', 'auth.success')->name('register.success');
Route::get('register/connect', 'Auth\RegisterController@instagramConnect')->name('register.connect');

Route::post('register', 'Auth\RegisterController@register')->name('register');
Route::get('register/{user_type?}', 'Auth\RegisterController@showRegistrationForm')->name('register');

Route::middleware(['auth', 'verified', 'checkStatus', 'preventBackHistory'])->group(function () {
    Route::any('/', 'HomeController@index')->name('home');

    Route::middleware('role:Administrator')->namespace('Backend')->prefix('backend')->group(function () {
        Route::resource('masters', 'MasterController', ['as' => 'backend']);
        Route::resource('roles', 'RoleController', ['as' => 'backend'])->middleware('password.confirm');
        Route::resource('users', 'UserController', ['as' => 'backend']);
        Route::get('categories/listing/{id?}', 'CategoryManagementController@index', ['as' => 'backend'])->name('backend.categories.index');
        // Route::get('user/listing', 'UserController@userListing')->name('user.listing');
        Route::get('campaigns', 'CampaignController@campaignListing')->name('campaigns');

        Route::get('profile', 'ProfileController@show')->name('backend.profile');
        Route::put('profile', 'ProfileController@update');
        Route::get('notification/all', 'NotificationController@notificationListing')->name('notification.admin.listing');
        // Route::get('/', 'CampaignController@campaignListing', ['as' => 'backend'])->name('campaigns');
        Route::get('/', 'UserController@userListing', ['as' => 'backend'])->name('user.listing');
        Route::get('creator/listing', 'UserController@creatorListing', ['as' => 'backend'])->name('creator.listing');
        Route::get('collaborations/request/', 'CampaignController@collaborationsListing')->name('collaboration.requests');
        Route::get('collaborations/list/{id}', 'CampaignController@collaborationsRequestListing')->name('collaboration.list');
        Route::post('admin-change-password', 'UserController@changePassword', ['as' => 'backend'])->name('admin.change.password');
        Route::get('products/{id}', 'ProductController@index')->name('products');
        Route::get('project/listing/{id}', 'UserController@projectListing', ['as' => 'backend'])->name('backend.project.listing');
        Route::get('product/listing/{id}', 'ProductController@productListing', ['as' => 'backend'])->name('backend.product.listing');
        Route::get('states/listing', 'UserController@stateListing', ['as' => 'backend'])->name('states.listing');
        // Route::get('product/bulk/upload/{id}', 'ProductController@bulkProductUpload', ['as' => 'brand'])->name('backend.product.bulk');
        // Route::post('product/bulk/upload/{id}', 'ProductController@bulkProductUploadStore', ['as' => 'brand'])->name('backend.product.bulk.submit');
        Route::get('projects/pitch-projects', 'UserController@pitchProjectListing', ['as' => 'backend'])->name('pitch.project.requests');
        /*Route::resource('general-campaigns', 'CampaignController', ['as' => 'backend'])->only([
            'index', 'create' ,'edit'
        ]);
        Route::get('general-campaigns/all', 'CampaignController@campaignAll', ['as' => 'backend'])->name('general.campaign.all');*/
        Route::get('brands/my-brand-list', 'UserController@myBrandUserListing', ['as' => 'backend'])->name('users.brand-user-listing');

        Route::get('brands/my-brand-register', 'MyBrandListController@myBrandRegsiter', ['as' => 'backend'])->name('backend.my-brand-register');
    });

    Route::middleware('role:Brand')->namespace('Brand')->prefix('brand')->group(function () {
        Route::redirect('/', 'campaign/all');

        Route::resource('campaign', 'CampaignController', ['as' => 'brand'])->only([
            'index', 'create' ,'edit'
        ]);
        Route::get('campaign/all', 'CampaignController@campaignAll', ['as' => 'brand'])->name('campaign.all');
        Route::get('campaign/completed', 'CampaignController@campaignCompleted', ['as' => 'brand'])->name('campaign.completed');
        Route::get('campaign/applicants/{id}', 'CampaignController@campaignApplicants', ['as' => 'brand'])->name('campaign.applicants');
        Route::get('creator/directory', 'CreatorController@directory', ['as' => 'brand'])->name('creator.directory');
        Route::get('creator/favorite', 'CreatorController@favorite', ['as' => 'brand'])->name('creator.favorite');
        Route::get('setting', 'SettingController@account', ['as' => 'brand'])->name('brand.setting');
        Route::get('product/listing', 'BrandController@productListing', ['as' => 'brand'])->name('product.listing');
        Route::get('lead-gen-request', 'CampaignController@leadGenReq', ['as' => 'brand'])->name('brand.leadGenReq');
        //Route::get('creator/profile/{id}', 'CreatorController@creatorProfile', ['as' => 'brand'])->name('creator.profile');
        Route::get('notification/all', 'NotificationController@notificationListing')->name('notification.brand.listing');
        Route::post('change-password', 'SettingController@changePassword', ['as' => 'brand'])->name('brand.change.password');
        Route::post('media/save', 'BrandController@mediaSave', ['as' => 'brand'])->name('brand.media.save');
        Route::get('campaign/stop/{id}/{status}', 'CampaignController@campaignStopped', ['as' => 'brand'])->name('campaign.stop');
        Route::get('document/esign/{coll_req_id}/{coll_contract_id}/{envelope_id}', 'CampaignController@documentEsign', ['as' => 'brand'])->name('esign-document');
        Route::post('card', 'SettingController@cardUpdate', ['as' => 'brand'])->name('brand.card');
        Route::get('payment-confirm', 'CampaignController@paymentConfirm', ['as' => 'brand'])->name('brand.payment.confirm');
        Route::get('pitch-payment-confirm', 'CampaignController@pitchPaymentConfirm', ['as' => 'brand'])->name('pitch.payment.confirm');
        Route::get('payment-history', 'PaymentHistoryController@paymentListing', ['as' => 'brand'])->name('brand.payment.history.listing');
        Route::get('tour', 'SettingController@tour', ['as' => 'brand'])->name('brand.tour');
        Route::get('product/bulk/upload', 'BrandController@bulkProductUpload', ['as' => 'brand'])->name('brand.product.bulk');
        Route::post('product/bulk/upload', 'BrandController@bulkProductUploadStore', ['as' => 'brand'])->name('brand.product.bulk.submit');
        Route::get('request/pricing/{name}', 'CreatorController@requestPricing', ['as' => 'brand'])->name('brand.request.pricing');
    });

    Route::middleware('role:Creator')->namespace('Creator')->prefix('creator')->group(function () {
        Route::redirect('/', 'campaign/listing');
        Route::get('campaign/listing/{id?}', 'CampaignController@campaignListing', ['as' => 'creator'])->name('campaign.listing');
        Route::get('campaign/details/{id}', 'CampaignController@campaignDetails', ['as' => 'creator'])->name('campaign.details');

        Route::get('collaboration-requests', 'CreatorController@collaborationRequests', ['as' => 'creator'])->name('collaboration-requests');
        Route::get('pitch-project', 'CreatorController@pitchProject', ['as' => 'creator'])->name('creator.pitch-project');
        Route::resource('media', 'MediaController', ['as' => 'creator']);
        Route::get('setting', 'SettingController@account', ['as' => 'creator'])->name('creator.setting');
        Route::post('edit/setting', 'SettingController@editCreatorAccount', ['as' => 'creator'])->name('creator.edit.setting');
        Route::get('project/listing', 'CreatorController@projectListing', ['as' => 'creator'])->name('project.listing');
        //Route::get('creator/profile', 'CreatorController@creatorProfile', ['as' => 'creator'])->name('creator.profile');
        Route::get('notification/all', 'NotificationController@notificationListing', ['as' => 'creator'])->name('notification.creator.listing');
        Route::post('change-password', 'SettingController@changePassword', ['as' => 'creator'])->name('creator.change.password');
        Route::post('media/save', 'MediaController@store', ['as' => 'creator'])->name('media.save');
        Route::post('payment-preference', 'SettingController@paymentPreference', ['as' => 'creator'])->name('creator.payment.preference');
        Route::get('document/esign/{coll_req_id}/{coll_contract_id}/{envelope_id}', 'CampaignController@documentEsign', ['as' => 'creator'])->name('sign-document');
        Route::get('instagram/connect', 'SettingController@redirectToFacebookProvider', ['as' => 'creator'])->name('instagram.connect');
        Route::get('instagram/disconnect', 'SettingController@instagramDisconnect', ['as' => 'creator'])->name('instagram.disconnect');
        Route::get('instagram/connect/callback', 'SettingController@handleProviderFacebookCallback');
        Route::get('test', 'SettingController@test', ['as' => 'creator'])->name('test');
        Route::get('payment-history', 'PaymentHistoryController@paymentListing', ['as' => 'creator'])->name('creator.payment.history.listing');
        Route::get('tour', 'SettingController@tour', ['as' => 'creator'])->name('creator.tour');
        Route::get('aaaaa', 'SettingController@test')->name('brand.test');
        Route::get('project/bulk/upload', 'CreatorController@bulkProjectUpload', ['as' => 'creator'])->name('creator.project.bulk');
        Route::post('project/bulk/upload', 'CreatorController@bulkProjectUploadStore', ['as' => 'creator'])->name('creator.project.bulk.submit');
        Route::get('campaign/marketplace', 'CampaignController@marketplaceListing', ['as' => 'creator'])->name('campaign.marketplace');
        Route::get('campaign/product-category', 'CampaignController@productCategoryListing', ['as' => 'creator'])->name('campaign.product-category');
        Route::get('campaign/product-list/{id}', 'CampaignController@productListing', ['as' => 'creator'])->name('campaign.product-list');
        Route::get('brand-profile/{id}', 'CampaignController@brandProfile', ['as' => 'creator'])->name('brand-profile');
        Route::get('campaign/new-arrival-product-list', 'CampaignController@newArrivalProducts', ['as' => 'creator'])->name('campaign.new.arrival.product-list');
        Route::get('project/bulk/export', 'CreatorController@exportProducts', ['as' => 'creator'])->name('creator.project.bulk.export');
        Route::get('general-campaign/brands-list', 'CampaignController@generalCampaignListing', ['as' => 'creator'])->name('general-campaign.listing');
        Route::get('general-campaign/list/{id?}', 'CampaignController@brandGeneralCampaignList', ['as' => 'creator'])->name('general-campaign.list');

        Route::get('general-campaign/campaignsList/{id}', 'CampaignController@generalCampaignDetails', ['as' => 'creator'])->name('general-campaign.general_campaign_details');
    });

    Route::get('creator/profile/{id}', 'Brand\CreatorController@creatorProfile')->name('creator.profile');
    Route::get('creator/instagram/profile/{id}', 'Brand\CreatorController@creatorProfileNew')->name('creator.profile.new');
    Route::get('creator/product-details/{id}', 'Creator\CampaignController@projectDetail', ['as' => 'creator'])->name('creator.project.details');
    Route::get('product/bulk/upload/{id}', 'Backend\ProductController@bulkProductUpload')->name('backend.product.bulk');
    Route::post('product/bulk/upload/{id}', 'Backend\ProductController@bulkProductUploadStore')->name('backend.product.bulk.submit');
    Route::get('product/bulk/export', 'Backend\ProductController@exportProducts')->name('backend.product.bulk.export');
});

Route::get('backend/general-campaigns/all', 'Backend\CampaignController@campaignAll')->name('backend.general.campaigns.all');

Route::get('backend/general-campaigns/create', 'Backend\CampaignController@create')->name('backend.general.campaigns.create');

Route::get('backend/general-campaigns/{id}/edit', 'Backend\CampaignController@edit')->name('backend.general.campaigns.edit');


Route::get('csv/upload', 'CsvUploadController@index')->name('csv.upload');
//Route::get('delete/user/{id}', 'CsvUploadController@deleteUserRecord')->name('delete.user');
Route::get('import/modash', 'CsvUploadController@modash')->name('csv.modash');
Route::get('modash/upload', 'CsvUploadController@modashUserData')->name('modash.upload');
Route::get('modash/updatefollowerscount', 'CsvUploadController@updateModashUserFollowersCount')->name('modash.updatefollowerscount');
Route::get('modash/updateusercatgeorytype', 'CsvUploadController@updateUserCatgeoryType')->name('modash.updateusercatgeorytype');
Route::post('fetch/countries', 'Auth\RegisterController@fetchCountries')->name('fetch-countries');
Route::post('register/fetch/countries', 'Auth\RegisterController@fetchCountries')->name('fetch-countries');

//Route::get('delete/creator', 'CsvUploadController@deleteCreator')->name('delete_creator');
Route::post('brand/creator/fetch/states', 'Brand\CreatorController@fetchStates')->name('fetch-states');

Route::post('backend/creator/admin/fetch/states', 'Backend\UserController@fetchStates');
Route::post('user/notification', 'HomeController@updateUser')->name('user-notification');

//Route::get('user/updates', 'CsvUploadController@updateModashUser');
//Route::get('user/update-er', 'CsvUploadController@userUpdateEr')->name('userUpdateEr');
//Route::get('user/modash-update', 'CsvUploadController@updateModashUser')->name('user-modash-update');
