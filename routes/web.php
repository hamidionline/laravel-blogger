<?php

Route::get('autocomplete', 'Frontend\HomepageController@autocomplete');

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
 */
//Authentication routes
Route::auth();
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('password/reset/{token}/{email}', 'Auth\ResetPasswordController@showResetForm');

//OAuth routes
Route::get('auth/facebook', 'OAuth\FacebookController@login');
Route::get('auth/facebook/callback', "OAuth\FacebookController@callback");

Route::get('auth/twitter', 'OAuth\TwitterController@login');
Route::get('auth/twitter/callback', "OAuth\TwitterController@callback");

Route::get('auth/google', 'OAuth\GoogleController@login');
Route::get('auth/google/callback', "OAuth\GoogleController@callback");

/*
|--------------------------------------------------------------------------
| Front-end routes
|--------------------------------------------------------------------------
 */

Route::get("/", 'Frontend\HomepageController@index');
Route::get('contact', 'Frontend\HomepageController@contact');
Route::get('about', 'Frontend\HomepageController@about');
Route::get('search', 'Frontend\HomepageController@search');

//       Route::get(LaravelLocalization::transRoute('routes.login'),'Auth\LoginController@showLoginForm');
//       Route::get(LaravelLocalization::transRoute('routes.articles'),function($slug){
//         return $slug;
//       });
//             Route::get(LaravelLocalization::transRoute('routes.about'),function(){
//                 return View::make('about');
//             });
//             Route::get(LaravelLocalization::transRoute('routes.view'),function($id){
//                 return View::make('view',['id'=>$id]);
//             });
//
//     //  Route::get('/{login}/','Auth\LoginController@showLoginForm')->where('login', trans('routes.auth.login.url'));
//
//       Route::get('/', function () {
//           return view('welcome');
//       });
//       // Auth::routes();
//
//       Route::get('/{articles}/{slug}', function ($slug) {
//           return "Artykul ".$slug;
//       })->where('articles', trans('routes.blog.articles.url'));;
//
// Route::get('/terms','Frontend\HomeController@terms');
// Route::get('/home', 'HomeController@index');

/*
|--------------------------------------------------------------------------
| Back-end routes
|--------------------------------------------------------------------------
 */

Route::group(['prefix' => 'filemanager', 'middleware' => 'role:editor'], function () {
    Route::get('show', 'Backend\FilemanagerLaravelController@getShow');
    Route::get('connectors', 'Backend\FilemanagerLaravelController@getConnectors');
    Route::post('connectors', 'Backend\FilemanagerLaravelController@postConnectors');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {
    Route::get('/', 'Backend\DashboardController@index');
    Route::get('profile', 'Backend\ProfileController@index');

    Route::get('tools', 'Backend\ToolsController@index');

    //admin only
    Route::group(['middleware' => 'role:admin'], function () {
        Route::resource('users', 'Backend\UserController');

        Route::get('impersonate/{id}', 'Backend\ImpersonificationController@impersonate');
        Route::get('settings', 'Backend\SettingsController@index');
        Route::post('settings/update', 'Backend\SettingsController@update');
    });

    //admin and editor
    Route::group(['middleware' => 'role:editor'], function () {
        Route::resource('categories', 'Backend\CategoryController');
        Route::resource('tags', 'Backend\TagController');
        Route::resource('articles', 'Backend\ArticleController');
        Route::get('manager', 'Backend\MediaController@manager');
    });

    Route::get('/back-to-admin-mode', 'Backend\ImpersonificationController@backToAdminMode');

    Route::get('subscriptions', 'Backend\SubscriptionController@index');

    //Route::get('categories', 'Backend\CategoryController@index');
    Route::get('tags', 'Backend\TagController@index');
    Route::get('media', 'Backend\MediaController@index');
    Route::get('help', 'Backend\HelpController@index');
});
