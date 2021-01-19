<?php


use App\Http\Controllers\AppTicketsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'user'], function () {

    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');
    Route::post('updateProfilePicture', 'UserController@updateProfilePicture');
    Route::post('updateFcmKey', 'UserController@updateFcmKey');
    Route::post('searchUsers', 'UserController@searchUsers');
    Route::post('loginWithId', 'UserController@loginWithId');
    Route::post('userProfile', 'UserController@userProfile');
    Route::post('resetpassword', 'UserController@resetpassword');
    Route::post('resetpasswordNow', 'UserController@resetpasswordNow');
    Route::post('updateProfile', 'UserController@updateProfile');
    Route::post('sendMail', 'MailController@sendMail');
    Route::post('appFaqs', 'UserController@appFaqs');
    Route::post('loginAdmin', 'UserController@loginAdmin');
});
Route::group(['prefix' => 'ticket'], function () {

    Route::post('allTickets', 'AppTicketsController@allTickets');
    Route::post('homeTickets', 'AppTicketsController@homeTickets');
    Route::post('getDepartments', 'AppTicketsController@getDepartments');

    Route::post('createTicket', 'AppTicketsController@createTicket');
    Route::post('sendMail', 'AppTicketsController@sendMail');
    Route::post('notices', 'AppTicketsController@notices');
    Route::get('smsApp', 'AppTicketsController@smsApp');
    Route::post('getReplies', 'AppTicketsController@getReplies');
    Route::post('sendReply', 'AppTicketsController@sendReply');
    Route::post('sendNotification', 'AppTicketsController@sendNotification');

});
Route::group(['prefix' => 'staff'], function () {

    Route::post('assignedTickets', 'AppTicketsController@assignedTickets');


});

Route::post('uploadFile', 'FileUploadController@uploadFile');
Route::post('uploadFileToUploads', 'FileUploadController@uploadFileToUploads');
