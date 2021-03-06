<?php

namespace App\Shop;

use Route;

class Shop
{
    protected $domain;

    public function routes($subdomain='shop')
    {
        $domain = sprintf("%s.%s", $subdomain, config('app.domain'));

        Route::group(['domain' => $domain, 'namespace' => 'Shop'], function () {
            Route::get('/', 'ProductsController@showListPage');

            Route::get('pictures/{type}/{id}', 'PicturesController@show')->name('shop.picture');

            Route::get('/product/{slug}', 'ProductsController@show')->name('shop.product');
            Route::get('/catalog/{category?}', 'ProductsController@showListPage')->name('shop.catalog');

            Route::group(['middleware' => 'auth'], function () {
                Route::get('cart', 'CartController@showCartPage');
                Route::get('checkout', 'CartController@showCheckoutForm')->name('shop.checkout');
                Route::post('checkout', 'CartController@checkout');

                Route::get('account', 'AccountController@showIndexPage');
                Route::get('account/orders', 'AccountController@showOrdersPage');

                Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
                    Route::group(['prefix' => 'cart'], function () {
                        Route::post('get', 'CartController@get')->name('shop.api.cart.get');
                        Route::post('append', 'CartController@append')->name('shop.api.cart.append');
                        Route::post('set', 'CartController@set')->name('shop.api.cart.set');
                        Route::post('remove', 'CartController@remove')->name('shop.api.cart.remove');
                        Route::post('remove-product', 'CartController@removeProduct')->name('shop.api.cart.remove-product');
                    });
                });

                Route::group(['prefix' => 'admin', 'middleware' => 'shopAdmin', 'namespace' => 'Admin'], function () {
                    Route::get('/', 'HomeController@index');

                    Route::get('categories', 'CategoriesController@showListPage')->name('shop.admin.categories.list');
                    Route::get('categories/new', 'CategoriesController@showNewForm')->name('shop.admin.categories.new');
                    Route::get('products', 'ProductsController@showListPage')->name('shop.admin.products.list');
                    Route::get('products/new', 'ProductsController@showNewForm')->name('shop.admin.products.new');
                    Route::get('products/{id}', 'ProductsController@showEditForm')->name('shop.admin.products.edit');
                    Route::get('orders', 'OrderController@showListPage')->name('shop.admin.orders.list');

                    Route::post('categories/new', 'CategoriesController@store');
                    Route::post('products/new', 'ProductsController@store');
                    Route::post('products/{id}', 'ProductsController@edit');
                    Route::post('products/{id}/new-photo', 'ProductsController@storePhoto')->name('shop.admin.products.new-photo');
                    Route::post('orders/{id}', 'OrderController@status')->name('shop.admin.orders.status');
                });
            });
        });
    }
}