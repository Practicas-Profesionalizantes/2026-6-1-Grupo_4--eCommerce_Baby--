<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CuentaController;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:30,1')->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/mi-cuenta', function () {
    return view('cuenta.index');
})->middleware('auth')->name('cuenta.index');

Route::post('/mi-cuenta', [CuentaController::class, 'actualizar'])->middleware('auth')->name('cuenta.actualizar');

Route::get('/mi-cuenta/pedidos', function () {
    return view('cuenta.pedidos');
})->middleware('auth')->name('cuenta.pedidos');

Route::get('/mi-cuenta/configuracion/apariencia', function () {
    return view('cuenta.apariencia');
})->middleware('auth')->name('cuenta.apariencia');

Route::get('/mi-cuenta/configuracion/tipografia', function () {
    return view('cuenta.tipografia');
})->middleware('auth')->name('cuenta.tipografia');

Route::get('/mi-cuenta/configuracion/soporte', function () {
    return view('cuenta.soporte');
})->middleware('auth')->name('cuenta.soporte');

Route::get('/factura/{id}', function ($id) {
    return view('factura', ['id' => (int) $id]);
})->whereNumber('id')->middleware('auth')->name('factura.ver');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalogo', [ProductoController::class, 'catalogo']);

Route::get('/terminos-de-privacidad', function () {
    return view('terminos-privacidad');
})->name('terminos');

Route::get('/producto/{id}', [CompraController::class, 'mostrarProducto'])->name('producto.show');

Route::get('/carrito', function () {
    return view('carrito');
})->middleware('auth')->name('carrito.ver');

Route::get('/checkout/carrito', [CompraController::class, 'comprarCarrito'])->middleware('auth')->name('checkout.carrito');

Route::get('/checkout/datos', [CompraController::class, 'datosComprador'])->middleware('auth')->name('checkout.datos');
Route::post('/checkout/datos/guardar', [CompraController::class, 'guardarDatosComprador'])->middleware('auth')->name('checkout.datos.guardar');

Route::get('/checkout/finalizar', [CompraController::class, 'finalizarCompra'])->middleware('auth')->name('checkout.finalizar');
Route::post('/checkout/procesar-pago', [CompraController::class, 'procesarPago'])->middleware(['auth', 'throttle:10,1,pago'])->name('checkout.pago');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.seccion', [
            'titulo' => 'Panel de control',
            'texto' => 'Elegí una sección del menú para empezar.',
        ]);
    })->name('inicio');

    Route::get('/productos', function () {
        return view('admin.productos');
    })->name('productos');

    Route::get('/productos/nuevo', function () {
        return view('admin.producto-form', ['id' => null]);
    })->name('productos.crear');

    Route::get('/productos/{id}/editar', function ($id) {
        return view('admin.producto-form', ['id' => (int) $id]);
    })->whereNumber('id')->name('productos.editar');

    Route::get('/problemas', function () {
        return view('admin.problemas');
    })->name('problemas');

    Route::get('/facturas', function () {
        return view('admin.facturas');
    })->name('facturas');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::view('/admin/pedidos', 'admin.pedidos')->name('admin.pedidos');
});