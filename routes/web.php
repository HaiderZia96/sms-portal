<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes([
    'register' => false
]);

Route::get('/admin', 'HomeController@index')->name('admin');
// Users Groups 
Route::resource('user_groups', 'Users\UserGroupController');
Route::get('user_groups-restore/{id}', 'Users\UserGroupController@restore')->name('user_groups.restore');
Route::delete('/user_groups-delete/{id}', 'Users\UserGroupController@delete')->name('user_groups.delete');
// Users
Route::resource('/users', 'Users\UserController');
Route::get('get-users', 'Users\UserController@getUsers')->name('get-users');
Route::put('update-users','Users\UserController@UpdateUser')->name('update-users');
Route::get('get-all-group','Users\UserController@getAllGroup');
Route::delete('user-delete/{id}', 'Users\UserController@deleteUser');
// Subscriber List
Route::resource('/subscriber-list', 'Subscriber\SubscriberListController');
Route::get('get-subscriber-list', 'Subscriber\SubscriberListController@getSubscriberLists')->name('get-subscriber-list');
Route::put('update-subscriber-list','Subscriber\SubscriberListController@UpdateSubscriber')->name('update-subscriber-list');
Route::delete('subscriber-list-delete/{id}', 'Subscriber\SubscriberListController@deleteSubscriber');
// Subscriber
Route::resource('/subscriber', 'Subscriber\SubscriberController');
Route::post('subscriber-csv-upload', 'Subscriber\SubscriberController@csvUpload')->name('subscriber-csv-upload');
Route::get('get-subscrib', 'Subscriber\SubscriberController@getSubscrib')->name('getSubscrib');
Route::get('get-subscriber/{subscriber_list_id}', 'Subscriber\SubscriberController@getSubscribers')->name('get-subscriber');
Route::get('get-all-subscriber','Subscriber\SubscriberController@getAllSubscriber');
Route::put('update-subscriber','Subscriber\SubscriberController@UpdateSubscriber')->name('update-subscriber');
Route::delete('subscriber-delete/{id}', 'Subscriber\SubscriberController@deleteSubscriber');
// Message
Route::resource('/message', 'Message\MessageController');
Route::get('get-message', 'Message\MessageController@getMessage')->name('get-message');
Route::put('update-message','Message\MessageController@UpdateMessage')->name('update-message');
Route::delete('message-delete/{id}', 'Message\MessageController@deleteMessage');
// Campaign
Route::resource('/campaign', 'Message\CampaignController');
Route::get('get-campaign', 'Message\CampaignController@getCampaign')->name('get-campaign');
Route::get('get-all-campaignSubscriber','Message\CampaignController@getAllSubscriber');
Route::get('get-all-message','Message\CampaignController@getAllMessage');
Route::put('update-campaign','Message\CampaignController@UpdateCampaign')->name('update-campaign');
Route::delete('campaign-delete/{id}', 'Message\CampaignController@deleteCampaign');
Route::get('get-campaign-detail/{campaign_id}', 'Message\CampaignController@getCampaignDetail')->name('get-campaign-detail');
// Instant Message
Route::resource('/instant-message', 'Message\InstantMessageController');
Route::get('get-instant-detail/{u_id}', 'Message\InstantMessageController@getInstantDetail')->name('get-instant-detail');
Route::get('get-instant-message', 'Message\InstantMessageController@getInstantMessage')->name('get-instant-message');
Route::get('instant-check-status/{msg_id}', 'Message\InstantMessageController@instantCheckStatus')->name('instant-check-status');


