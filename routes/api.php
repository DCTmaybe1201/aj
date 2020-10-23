<?php
use App\User;
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
// middleware('auth:api')->
Route::get('/user', function () {
    // return $request->user();
    Route::get("/{id}", function ($id) {
        return User::find($id);
    });

    return User::all();
    // return json_encode($request);
});

// Route::group(['prefix' => 'user'], function () {
//     // return $request->user();
//     Route::get("/", function () {
//         return User::all();
//     });
//     Route::get("/{id}", function ($id) {
//         return User::find($id);
//     })->middleware(CheckAge::class);;

//     // return json_encode($request);
// });
