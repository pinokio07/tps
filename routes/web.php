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

//Main Routing
Route::get('/', 'AuthController@index')->name('login');
Route::post('/', 'AuthController@postlogin');

//Route Group for Authenticated User
Route::group(['middleware' => 'auth'], function(){
  Route::get('/logout', 'AuthController@logout')->name('logout');//Logout
  Route::get('/dashboard', 'DashboardController@index')->name('dashboard');//Dashboard

  //Super-Admin Routes
  Route::group(['middleware' => 'role:super-admin', 'as' => 'admin.'], function(){
    Route::get('/administrator', 'AdminDashboardController@index')->name('dashboard');
    Route::get('/administrator/profile', 'AuthController@profile')->name('profile');
    Route::put('/administrator/profile/{user}', 'AuthController@update')->name('profile.update');
    
    try {

      $menu = \App\Models\Menu::with(['parent_items' => function ($q) {
                                  $q->where('active', true)
                                    ->orderBy('order', 'asc');
                              }, 'parent_items.children' => function($c){
                                $c->where('active', true)
                                  ->orderBy('order', 'asc');
                              }])
                              ->where('name', 'admin')
                              ->first();
      if($menu){
        foreach($menu->parent_items as $main){
          $mainUrl = $main->link();
          $mainTitle = Str::lower($main->title);
          $routeName = Str::replace(' ', '_', $mainTitle);
          $singular = Str::singular(Str::replace(' ','_', $mainTitle));
          if($mainTitle != 'dashboard'){           
            $ctName = Str::replace(' ', '', Str::title($mainTitle));

            Route::get($mainUrl, 'Admin'. $ctName .'Controller@index')->name($routeName);
            Route::get($mainUrl.'/create', 'Admin'. $ctName .'Controller@create')->name($routeName.'.create');
            Route::post($mainUrl, 'Admin'. $ctName .'Controller@store')->name($routeName.'.store');
            Route::get($mainUrl.'/{'. $singular .'}', 'Admin'. $ctName .'Controller@show')->name($routeName.'.show');           
            Route::get($mainUrl.'/{'. $singular .'}/edit', 'Admin'. $ctName .'Controller@edit')->name($routeName.'.edit');
            Route::put($mainUrl.'/{'. $singular .'}', 'Admin'. $ctName .'Controller@update')->name($routeName.'.update');
            Route::delete($mainUrl.'/{'. $singular .'}', 'Admin'. $ctName .'Controller@destroy')->name($routeName.'.delete'); 
            
            if($mainTitle == 'menus'){
              Route::get($mainUrl.'/{'. $singular .'}/builder', 'Admin'. $ctName .'BuilderController@index')->name($routeName.'.builder');
              Route::post($mainUrl.'/{'. $singular .'}/builder', 'Admin'. $ctName .'BuilderController@store')->name($routeName.'.builder.store');
              Route::put($mainUrl.'/{'. $singular .'}/builder/{id}', 'Admin'.$ctName .'BuilderController@update')->name($routeName.'.builder.update');
              Route::delete($mainUrl.'/{'. $singular .'}/builder/{id}', 'Admin'.$ctName .'BuilderController@destroy')->name($routeName.'.builder.destroy');

              Route::post($mainUrl.'/order', 'Admin'. $ctName .'BuilderController@order_item')->name($routeName.'.order');
            }
            
            if($mainTitle == 'companies'){
              Route::get($mainUrl .'/{'. $singular . '}/branches', 'Admin'. $ctName .'BranchesController@index')
                   ->name($routeName.'.branches.index');
              Route::get($mainUrl .'/{'. $singular . '}/branches/create', 'Admin'. $ctName .'BranchesController@create')
                   ->name($routeName.'.branches.create');
              Route::post($mainUrl.'/{'. $singular.'}/branches', 'Admin'.$ctName.'BranchesController@store')
                   ->name($routeName.'.branches.store');
              Route::get($mainUrl.'/{'. $singular.'}/branches/{branch}', 'Admin'.$ctName.'BranchesController@show')
                   ->name($routeName.'.branches.show');
              Route::get($mainUrl.'/{'. $singular.'}/branches/{branch}/edit', 'Admin'.$ctName.'BranchesController@edit')
                   ->name($routeName.'.branches.edit');
              Route::put($mainUrl.'/{'. $singular.'}/branches/{branch}', 'Admin'.$ctName.'BranchesController@update')
                   ->name($routeName.'.branches.update');
              Route::delete($mainUrl.'/{'. $singular.'}/branches/{branch}', 'Admin'.$ctName.'BranchesController@destroy')
                   ->name($routeName.'.branches.destroy');
            }
  
          }
        }
      }     
        
    } catch (\Throwable $th) {
      throw $th;
    }
  
  });
  //End Super-Admin Routes
});
