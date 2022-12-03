<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function(){

  Route::get('/manifest/houses/{house}', 'ManifestHousesController@show');
  Route::post('/manifest/houses', 'ManifestHousesController@store')
       ->middleware('can:update_manifest_consolidations');
  Route::put('/manifest/houses/{house}', 'ManifestHouseController@update')
        ->middleware('can:update_manifest_consolidations');
  Route::delete('/manifest/houses/{house}/delete', 'ManifestHousesController@destroy')
        ->name('manifest.houses.destroy')
        ->middleware('can:delete_manifest_consolidations');
});