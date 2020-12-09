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

// Route::get('/', function () {
//     // return view('welcome');
// });

Route::get('/','BookController@index')->name('books.all');

###################### Begin Authors routes #####################
Route::group(['prefix' => 'authors'], function () {
    Route::get('index','AuthorController@index')->name('authors.all');
    Route::get('create', 'AuthorController@createAuthor');
    Route::post('store', 'AuthorController@storeAuthor')->name('authors.store');
    Route::get('edit/{author_id}', 'AuthorController@editAuthor');
    Route::post('update/{author_id}', 'AuthorController@UpdateAuthor')->name('authors.update');
    Route::get('delete/{author_id}', 'AuthorController@deleteAuthor')->name('authors.delete');
});
###################### End Authors routes #####################

###################### Begin Books routes #####################
Route::group(['prefix' => 'books'], function () {
    Route::get('index','BookController@index')->name('books.all');
    Route::get('search','BookController@search')->name('books.search');
    Route::get('create', 'BookController@createBook');
    Route::post('store', 'BookController@storeBook')->name('books.store');
    Route::get('edit/{book_id}', 'BookController@editBook')->name('books.edit');;
    Route::post('update/{book_id}', 'BookController@UpdateBook')->name('books.update');
    Route::get('delete/{book_id}', 'BookController@deleteBook')->name('books.delete');

    Route::get('exportBooksWithAuthorstoCSV', 'BookController@exportBooksWithAuthorstoCSV')->name('books.authors.csv');
    Route::get('exportBookstoCSV', 'BookController@exportBookstoCSV')->name('books.csv');
    Route::get('exportAuthorstoCSV', 'BookController@exportAuthorstoCSV')->name('authors.csv');
    Route::get('exportAuthorstoXML', 'BookController@exportAuthorstoXML')->name('authors.xml');
    Route::get('exportBookstoXML', 'BookController@exportBookstoXML')->name('books.xml');
    Route::get('exportBooksWithAuthorstoXML', 'BookController@exportBooksWithAuthorstoXML')->name('books.authors.xml');

});

###################### End Books routes #####################
