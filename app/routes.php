<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
* Homepage of the application
*/
Route::get('/', 'HomeController@showMain');

/**
* Handles form submission from root, allows user to match header data to CSV columns
*/
Route::post('uploadCSV', 'CSVController@processCSV');

/**
* Prevent people from loading the UploadCSV location directly
*/
Route::get('uploadCSV', function()
{
    return Redirect::to('/')->with('errorMsg', 'Please upload a CSV file.');
});

/**
* Takes header inforation from /uploadCSV and sends data to a Google Maps interface
*/
Route::post('processHeaders', 'CSVController@processHeaders');

/**
* Prevent people from loading the processHeaders location directly
*/
Route::get('processHeaders', function()
{
    return Redirect::to('/')->with('errorMsg', 'Please upload a CSV file.');
});
