<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function(){

  Route::get('/manifest/houses/{house}', 'ManifestHousesController@show');
  Route::post('/manifest/houses', 'ManifestHousesController@store')
       ->middleware('can:edit_manifest_consolidations');
  Route::put('/manifest/houses/{house}', 'ManifestHouseController@update')
        ->middleware('can:edit_manifest_consolidations');
  Route::delete('/manifest/houses/{house}/delete', 'ManifestHousesController@destroy')
        ->name('manifest.houses.destroy')
        ->middleware('can:delete_manifest_consolidations');

  //------------------------------------------- Organization Routes --------------------------------------------------------------//

  //Download Company Data
  Route::get('/download/companydata', 'SetupOrganizationController@downloadcompanydata')
    ->name('download.companydata')
    ->middleware('can:edit_setup_organization');
  //Upload Company Data
  Route::post('/upload/companydata', 'SetupOrganizationController@uploadcompanydata')
    ->name('upload.companydata')
    ->middleware('can:edit_setup_organization');

  //Create Org from Airlines
  Route::get('/setup/airlines-create/{airline}', 'SetupAirlinesController@organization')
         ->name('create.airlines.organization')
         ->middleware('can:edit_setup_organization');
  //Create Org from Shipping
  Route::get('/setup/shipping-create/{shipping_line}', 'SetupShippinglinesController@organization')
        ->name('create.shipping.organization')
        ->middleware('can:edit_setup_organization');

  //Select2 Org Address
  Route::get('/select2/setup/organization/address', 'SetupOrganizationController@select2address')
      ->name('select2.setup.organization.address');
  //Select2 Org Contacts
  Route::get('/select2/setup/organization/contacts', 'SetupOrganizationController@select2contacts')
      ->name('select2.setup.organization.contacts');
  Route::get('/select2/setup/organization/users', 'SetupOrganizationController@users')
      ->name('select2.setup.organization.users');
  //Get Address Ajax
  Route::get('/setup/organization/address/{organization}', 'SetupOrganizationController@ajaxaddress')
      ->name('setup.organization.address')
      ->middleware('can:edit_setup_organization');
  //Add new OrgAddress
  Route::post('/setup/organization/address', 'SetupOrganizationController@storeaddress')
      ->name('setup.organization.newaddress')
      ->middleware('can:edit_setup_organization');
  //Update Address
  Route::put('/setup/organization/address/{address}', 'SetupOrganizationController@updateaddress')
      ->name('setup.organization.updateaddress')
      ->middleware('can:edit_setup_organization');
  //Destroy Address
  Route::get('/setup/organization/deladdress/{address}',
      'SetupOrganizationController@destroyaddress')
      ->name('setup.organization.deleteaddress')
      ->middleware('can:delete_setup_organization');
  //Change Address State
  Route::post('/setup/organization/address/changestate', 'SetupOrganizationController@changestate')
      ->name('setup.organization.addressstate')
      ->middleware('can:edit_setup_organization');
  //Sync Contact
  Route::post('/override/setup/organization/contact', 'SetupOrganizationController@synccontact')
      ->name('setup.organization.contact')
      ->middleware('can:edit_setup_organization');
  //Get Contact Ajax
  Route::get('/setup/organization/contact/{organization}', 'SetupOrganizationController@ajaxcontact')
      ->name('setup.organization.ajaxcontact');
  //Add New OrgContacts
  Route::post('/setup/organization/contact', 'SetupOrganizationController@storecontact')
      ->name('setup.organization.newcontact')
      ->middleware('can:edit_setup_organization');
  //Update Contact
  Route::put('/setup/organization/contact/{contact}', 'SetupOrganizationController@updatecontact')
        ->name('setup.organization.updatecontact')
        ->middleware('can:edit_setup_organization');
  //Change Address State
  Route::post('/setup/organization/contact/changestate', 'SetupOrganizationController@changecontactstate')
      ->name('setup.organization.contactstate')
      ->middleware('can:edit_setup_organization');
  //Destroy Contact
  Route::get('/setup/organization/delcontact/{contact}', 'SetupOrganizationController@destroycontact')
  ->name('setup.organization.destroycontact')
  ->middleware('can:delete_setup_organization');

  //------------------------------------------- End Organization Routes --------------------------------------------------------------//
});