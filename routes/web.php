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
Auth::routes();

Route::namespace('Auth')->group(function () {
    Route::get('logout', 'LoginController@logout')->name('logout');
});

Route::group(['prefix' => 'manager', 'middleware' => ['auth'], 'as' => 'manager.' ], function () {
    Route::namespace('Manager')->group(function () {

        # DASHBOARD
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        Route::group(['middleware' => ['admin']], function(){

            # CATEGORIES
            Route::resource('categories', 'CategoriesController');
        });

        # TOPICS
        Route::resource('topics', 'TopicsController');
    });
});

Route::namespace('Site')->group(function () {

    # HOME
    Route::get('/', 'HomeController@index')->name('home');

    # TOPICS
    Route::get('/topics', 'HomeController@index')->name('search.topics');
});
