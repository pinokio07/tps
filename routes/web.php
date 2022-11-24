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
    Route::put('/administrator', 'AdminDashboardController@update');
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

  //Menu Routes
  try {
    
    $menus = \App\Models\MenuItem::where('menu_id', '<>', 1)
                                 ->where('active', true)
                                 ->get();

    if($menus){
      foreach($menus as $menu){
        $mainUrl = $menu->link();
        $mainTitle = Str::lower($menu->title);
        $singular = Str::singular(Str::replace(['-',' '],'_', $mainTitle));
        $routeName = Str::replace('/','.',ltrim($mainUrl,'/'));
        $permit = str_replace(['.', '-'], '_', $routeName);
        
        ($menu->permission != '') 
              ? $middle = "can:$menu->permission" 
              : $middle = 'auth';        

        if($mainUrl != '#' && $menu->controller != ''){
          $cont = $menu->controller;
          Route::get($mainUrl, $cont .'Controller@index')
                ->name($routeName)
                ->middleware($middle);

          Route::get($mainUrl.'/create', $cont .'Controller@create')
                ->name($routeName.'.create')
                ->middleware('can:create_'.$permit);

          Route::post($mainUrl, $cont .'Controller@store')
                ->name($routeName.'.store')
                ->middleware('can:create_'.$permit);

          Route::get($mainUrl.'/{'. $singular .'}', $cont .'Controller@show')
                ->name($routeName.'.show')
                ->middleware('can:view_'.$permit);

          Route::get($mainUrl.'/{'. $singular .'}/edit', $cont .'Controller@edit')
                ->name($routeName.'.edit')
                ->middleware('can:edit_'.$permit);

          Route::put($mainUrl.'/{'. $singular .'}', $cont .'Controller@update')
                ->name($routeName.'.update')
                ->middleware('can:edit_'.$permit);

          Route::delete($mainUrl.'/{'. $singular .'}', $cont .'Controller@destroy')
                ->name($routeName.'.delete')
                ->middleware('can:delete_'.$permit);

          //Select2 Route
          Route::get('/select2'.$mainUrl, $cont . 'Controller@select2')
               ->name('select2.'.$routeName);
          //Download Route
          Route::get('/download'.$mainUrl, $cont . 'Controller@download')
               ->name('download.'.$routeName);
          //Upload Route
          Route::post('/upload'.$mainUrl, $cont . 'Controller@upload')
               ->name('upload.'.$routeName);

        } elseif($mainUrl != '#' && $menu->controller == ''){

          $page = getPage('pages.'.$mainTitle.'.index');

          Route::get($mainUrl, function() use ($page){
            return view($page);
          })->middleware($middle);

        }

      }
    }     
      
  } catch (\Throwable $th) {

    throw $th;
    
  }
  //End Menu Routes  
});
