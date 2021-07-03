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

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::group(['middleware' => ['web', 'auth', 'roles']],function(){

	Route::group(['roles'=>['Admin']],function(){

		Route::get('/home', 'HomeController@index')->name('home');

		Route::group(['namespace' => 'User'], function () {
		    Route::name('User.')->prefix('user')->group(function () {
		    	Route::get('/', 'UserController@index')->name('index');
		    	Route::get('/scopedata', 'UserController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'UserController@edit')->name('edit');
		    	Route::post('store', 'UserController@store')->name('store');
		    	Route::get('/destroy/{id}', 'UserController@destroy')->name('destroy');
		    	Route::post('cekEmail', 'UserController@cekEmail')->name('cekEmail');
		    });
		});

		Route::group(['namespace' => 'Toko'], function () {
		    Route::name('Toko.')->prefix('toko')->group(function () {
		    	Route::get('/', 'TokoController@index')->name('index');
		    	Route::get('/scopedata', 'TokoController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'TokoController@edit')->name('edit');
		    	Route::post('store', 'TokoController@store')->name('store');
		    	Route::get('/destroy/{id}', 'TokoController@destroy')->name('destroy');
		    	Route::post('cekEmail', 'TokoController@cekEmail')->name('cekEmail');
		    });
		});

		Route::group(['namespace' => 'Master'], function () {
		    Route::name('Kategori.')->prefix('kategori')->group(function () {
		    	Route::get('/', 'MasterKategoriController@index')->name('index');
		    	Route::get('/scopedata', 'MasterKategoriController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'MasterKategoriController@edit')->name('edit');
		    	Route::post('store', 'MasterKategoriController@store')->name('store');
		    	Route::get('/destroy/{id}', 'MasterKategoriController@destroy')->name('destroy');
		    	Route::get('/getVariasi', 'MasterKategoriController@getVariasi')->name('getVariasi');
		    });
		});

		Route::group(['namespace' => 'Master'], function () {
		    Route::name('Merk.')->prefix('merk')->group(function () {
		    	Route::get('/', 'MasterBrandController@index')->name('index');
		    	Route::get('/scopedata', 'MasterBrandController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'MasterBrandController@edit')->name('edit');
		    	Route::post('store', 'MasterBrandController@store')->name('store');
		    	Route::get('/destroy/{id}', 'MasterBrandController@destroy')->name('destroy');
		    });
		});

		Route::group(['namespace' => 'Master'], function () {
		    Route::name('Variasi.')->prefix('variasi')->group(function () {
		    	Route::get('/', 'MasterVariasiController@index')->name('index');
		    	Route::get('/scopedata', 'MasterVariasiController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'MasterVariasiController@edit')->name('edit');
		    	Route::post('store', 'MasterVariasiController@store')->name('store');
		    	Route::get('/destroy/{id}', 'MasterVariasiController@destroy')->name('destroy');
		    });
		});

		Route::group(['namespace' => 'Master'], function () {
		    Route::name('Bank.')->prefix('bank')->group(function () {
		    	Route::get('/', 'MasterBankController@index')->name('index');
		    	Route::get('/scopedata', 'MasterBankController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'MasterBankController@edit')->name('edit');
		    	Route::post('store', 'MasterBankController@store')->name('store');
		    	Route::get('/destroy/{id}', 'MasterBankController@destroy')->name('destroy');
		    });
		});

		Route::group(['namespace' => 'Master'], function () {
		    Route::name('Produk.')->prefix('produk')->group(function () {
		    	Route::get('/', 'MasterProdukController@index')->name('index');
		    	Route::get('/scopedata', 'MasterProdukController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'MasterProdukController@edit')->name('edit');
		    	Route::get('/show/{id}', 'MasterProdukController@show')->name('show');
		    	Route::post('store', 'MasterProdukController@store')->name('store');
		    	Route::get('/destroy/{id}', 'MasterProdukController@destroy')->name('destroy');
		    	Route::post('checkCodeProduct', 'MasterProdukController@checkCodeProduct')->name('checkCodeProduct');
		    });
		});

		Route::group(['namespace' => 'Cart'], function () {
		    Route::name('Cart.')->prefix('cart')->group(function () {
		    	Route::get('/', 'CartController@index')->name('index');
		    	Route::get('/scopedata', 'CartController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'CartController@edit')->name('edit');
		    });
		});

		Route::group(['namespace' => 'Pesanan'], function () {
		    Route::name('Pesanan.')->prefix('pesanan')->group(function () {
		    	Route::get('/', 'PesananController@index')->name('index');
		    	Route::get('/scopedata', 'PesananController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'PesananController@edit')->name('edit');
		    	Route::post('verifikasiPesanan', 'PesananController@verifikasiPesanan')->name('verifikasiPesanan');
		    	Route::post('teruskanPesanan', 'PesananController@teruskanPesanan')->name('teruskanPesanan');
		    	Route::get('/pilihKurir', 'PesananController@pilihKurir')->name('pilihKurir');
		    	Route::get('/viewstruk/{id}', 'PesananController@viewstruk')->name('viewstruk');
		    });
		});

		Route::group(['namespace' => 'Dashboard'], function () {
		    Route::name('Dashboard.')->prefix('dashboard')->group(function () {
		    	Route::get('/PenjualanPerKategori', 'DashboardController@PenjualanPerKategori')->name('PenjualanPerKategori');
		    	Route::get('/PenjualanPerMerk', 'DashboardController@PenjualanPerMerk')->name('PenjualanPerMerk');
		    	Route::get('/kurir', 'DashboardController@kurir')->name('kurir');
		    	Route::get('/detailKurir/{id}', 'DashboardController@detailKurir')->name('detailKurir');
		    	Route::get('/DetailKurirPesanan/{id}', 'DashboardController@DetailKurirPesanan')->name('DetailKurirPesanan');
		    });
		});

		Route::group(['namespace' => 'Laporan'], function () {
		    Route::name('Laporan.')->prefix('laporan')->group(function () {
		    	Route::get('/', 'LaporanController@index')->name('index');
		    	Route::post('periode', 'LaporanController@periode')->name('periode');
		    	Route::post('excel', 'LaporanController@excel')->name('excel');
		    });
		});

	});


	Route::group(['roles'=>['Toko','Admin']],function(){

		Route::get('/home', 'HomeController@index')->name('home');	

		/*Route::group(['namespace' => 'Master'], function () {
		    Route::name('ProdukToko.')->prefix('produktoko')->group(function () {
		    	Route::get('/', 'MasterProdukTokoController@index')->name('index');
		    	Route::get('/scopedata', 'MasterProdukTokoController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'MasterProdukTokoController@edit')->name('edit');
		    	Route::get('/show/{id}', 'MasterProdukTokoController@show')->name('show');
		    	Route::post('store', 'MasterProdukTokoController@store')->name('store');
		    	Route::get('/destroy/{id}', 'MasterProdukTokoController@destroy')->name('destroy');
		    	Route::post('checkCodeProduct', 'MasterProdukTokoController@checkCodeProduct')->name('checkCodeProduct');
		    });
		});*/

		Route::group(['namespace' => 'Cart'], function () {
		    Route::name('Cart.')->prefix('cart')->group(function () {
		    	Route::get('/', 'CartController@index')->name('index');
		    	Route::get('/scopedata', 'CartController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'CartController@edit')->name('edit');
		    });
		});

		Route::group(['namespace' => 'Pesanan'], function () {
		    Route::name('Pesanan.')->prefix('pesanan')->group(function () {
		    	Route::get('/', 'PesananController@index')->name('index');
		    	Route::get('/scopedata', 'PesananController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'PesananController@edit')->name('edit');
		    	Route::post('verifikasiPesanan', 'PesananController@verifikasiPesanan')->name('verifikasiPesanan');
		    	Route::post('teruskanPesanan', 'PesananController@teruskanPesanan')->name('teruskanPesanan');
		    	Route::get('/pilihKurir', 'PesananController@pilihKurir')->name('pilihKurir');
		    	Route::get('/viewstruk/{id}', 'PesananController@viewstruk')->name('viewstruk');
		    });
		});

		Route::group(['namespace' => 'Master'], function () {
		    Route::name('Produk.')->prefix('produk')->group(function () {
		    	Route::get('/', 'MasterProdukController@index')->name('index');
		    	Route::get('/scopedata', 'MasterProdukController@scopedata')->name('scopedata');
		    	Route::get('/edit/{id}', 'MasterProdukController@edit')->name('edit');
		    	Route::get('/show/{id}', 'MasterProdukController@show')->name('show');
		    	Route::post('store', 'MasterProdukController@store')->name('store');
		    	Route::get('/destroy/{id}', 'MasterProdukController@destroy')->name('destroy');
		    	Route::post('checkCodeProduct', 'MasterProdukController@checkCodeProduct')->name('checkCodeProduct');
		    });
		});

		Route::group(['namespace' => 'Dashboard'], function () {
		    Route::name('Dashboard.')->prefix('dashboard')->group(function () {
		    	Route::get('/PenjualanPerKategori', 'DashboardController@PenjualanPerKategori')->name('PenjualanPerKategori');
		    	Route::get('/PenjualanPerMerk', 'DashboardController@PenjualanPerMerk')->name('PenjualanPerMerk');
		    	Route::get('/kurir', 'DashboardController@kurir')->name('kurir');
		    	Route::get('/detailKurir/{id}', 'DashboardController@detailKurir')->name('detailKurir');
		    	Route::get('/DetailKurirPesanan/{id}', 'DashboardController@DetailKurirPesanan')->name('DetailKurirPesanan');
		    });
		});

		Route::group(['namespace' => 'Laporan'], function () {
		    Route::name('Laporan.')->prefix('laporan')->group(function () {
		    	Route::get('/', 'LaporanController@index')->name('index');
		    	Route::post('periode', 'LaporanController@periode')->name('periode');
		    	Route::post('excel', 'LaporanController@excel')->name('excel');
		    });
		});

	});

});

/*Route::get('/sign', 'WebController@sign')->name('sign');*/
Route::get('/sign', function(){
    if (Auth::user()) {
        return redirect('/home');
    }else{
        return view('sign');
    }
})->name('sign');

Route::get('/login', function(){
    return redirect('/sign');
})->name('login');

Route::post('attempt', 'SignController@attempt')->name('signin.attempt');