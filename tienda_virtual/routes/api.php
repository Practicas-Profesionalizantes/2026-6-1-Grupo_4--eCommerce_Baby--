<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminAvisoController;
use App\Http\Controllers\AdminProductoController;
use App\Http\Controllers\BebeController;
use App\Http\Controllers\CarritoClienteController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\Falla_ProduccionController;
use App\Http\Controllers\Imagen_ProductoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\Item_CarritoController;
use App\Http\Controllers\Metodo_EnvioController;
use App\Http\Controllers\Metodo_PagoController;
use App\Http\Controllers\Movimiento_StockController;
use App\Http\Controllers\Orden_ProduccionController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoGestionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\Receta_ProduccionController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SoporteController;
use App\Http\Controllers\Transaccion_PagoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Variante_ProductoController;

Route::get('/catalogo/productos', [ProductoController::class, 'catalogoApi']);
Route::get('/catalogo/productos/{id}', [ProductoController::class, 'detalleApi']);

Route::middleware(['web', 'auth', 'throttle:120,1'])->group(function () {
    Route::get('/mis-pedidos', [PedidoController::class, 'misPedidos']);
    Route::post('/mis-pedidos/{id}/cancelar', [PedidoGestionController::class, 'cancelarCliente'])->whereNumber('id');
    Route::get('/mis-avisos-pedidos', [PedidoGestionController::class, 'avisosCliente']);
    Route::post('/mis-avisos-pedidos/vistos', [PedidoGestionController::class, 'avisosVistos']);

    Route::get('/mi-carrito', [CarritoClienteController::class, 'index']);
    Route::get('/mi-carrito/contador', [CarritoClienteController::class, 'contador']);
    Route::post('/mi-carrito/items', [CarritoClienteController::class, 'agregar']);
    Route::put('/mi-carrito/items/{id}', [CarritoClienteController::class, 'actualizar'])->whereNumber('id');
    Route::delete('/mi-carrito/items/{id}', [CarritoClienteController::class, 'quitar'])->whereNumber('id');

    Route::get('/mis-facturas', [FacturaController::class, 'misFacturas']);
    Route::get('/comprobante/{id}', [FacturaController::class, 'comprobante'])->whereNumber('id');

    Route::get('/mis-reportes', [SoporteController::class, 'misReportes']);
    Route::post('/mis-reportes', [SoporteController::class, 'enviar'])->middleware('throttle:5,1,reporte');
    Route::get('/mis-reportes/contador', [SoporteController::class, 'contador']);
    Route::post('/mis-reportes/vistos', [SoporteController::class, 'marcarVistas']);
    Route::delete('/mis-reportes/{id}', [SoporteController::class, 'eliminar'])->whereNumber('id');

    Route::get('/cliente/{id}', [ClienteController::class, 'showCliente'])->whereNumber('id');
    Route::put('/cliente/{id}', [ClienteController::class, 'updateCliente'])->whereNumber('id');
    Route::get('/cliente/{id}/direccion', [DireccionController::class, 'porCliente'])->whereNumber('id');
    Route::put('/cliente/{id}/direccion', [DireccionController::class, 'guardarPorCliente'])->whereNumber('id');
});

Route::middleware(['web', 'auth', 'admin'])->group(function () {

    Route::prefix('admin')->group(function () {
        Route::get('/categorias', [AdminProductoController::class, 'categorias']);
        Route::get('/productos', [AdminProductoController::class, 'listar']);
        Route::post('/productos', [AdminProductoController::class, 'crear']);
        Route::get('/productos/{id}', [AdminProductoController::class, 'mostrar']);
        Route::post('/productos/{id}/actualizar', [AdminProductoController::class, 'actualizar']);
        Route::post('/productos/{id}/visibilidad', [AdminProductoController::class, 'visibilidad']);
        Route::post('/variantes/{id}/stock', [AdminProductoController::class, 'cambiarStock'])->whereNumber('id');
        Route::get('/reportes', [AdminAvisoController::class, 'reportes']);
        Route::get('/reportes/contador', [AdminAvisoController::class, 'contador']);
        Route::post('/reportes/{id}/leido', [AdminAvisoController::class, 'marcarLeido'])->whereNumber('id');
        Route::post('/reportes/{id}/responder', [AdminAvisoController::class, 'responder'])->whereNumber('id');
        Route::delete('/reportes/{id}', [AdminAvisoController::class, 'eliminar'])->whereNumber('id');

        Route::get('/facturas', [FacturaController::class, 'index']);
        Route::get('/facturas/contador', [FacturaController::class, 'contador']);
        Route::post('/facturas/{id}/leida', [FacturaController::class, 'marcarLeida'])->whereNumber('id');

        Route::get('/gestion-pedidos', [PedidoGestionController::class, 'listar']);
        Route::get('/gestion-pedidos/{id}', [PedidoGestionController::class, 'detalle'])->whereNumber('id');
        Route::post('/gestion-pedidos/{id}/estado', [PedidoGestionController::class, 'cambiarEstado'])->whereNumber('id');
        Route::post('/gestion-pedidos/{id}/envio', [PedidoGestionController::class, 'guardarEnvio'])->whereNumber('id');
        Route::post('/gestion-pedidos/{id}/cancelar', [PedidoGestionController::class, 'cancelarAdmin'])->whereNumber('id');
        Route::post('/gestion-pedidos/{id}/visto', [PedidoGestionController::class, 'visto'])->whereNumber('id');
        Route::get('/pedidos-aviso/contador', [PedidoGestionController::class, 'contador']);
    });

    Route::get('/bebe', [BebeController::class, 'index']);
    Route::post('/bebe', [BebeController::class, 'storeBebe']);
    Route::get('/bebe/{id}', [BebeController::class, 'showBebe']);
    Route::put('/bebe/{id}', [BebeController::class, 'updateBebe']);
    Route::delete('/bebe/{id}', [BebeController::class, 'destroyBebe']);

    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::post('/carrito', [CarritoController::class, 'storeCarrito']);
    Route::get('/carrito/{id}', [CarritoController::class, 'showCarrito']);
    Route::put('/carrito/{id}', [CarritoController::class, 'updateCarrito']);
    Route::delete('/carrito/{id}', [CarritoController::class, 'destroyCarrito']);

    Route::get('/categoria', [CategoriaController::class, 'index']);
    Route::post('/categoria', [CategoriaController::class, 'storeCategoria']);
    Route::get('/categoria/{id}', [CategoriaController::class, 'showCategoria']);
    Route::put('/categoria/{id}', [CategoriaController::class, 'updateCategoria']);
    Route::delete('/categoria/{id}', [CategoriaController::class, 'destroyCategoria']);

    Route::get('/cliente', [ClienteController::class, 'index']);
    Route::post('/cliente', [ClienteController::class, 'storeCliente']);
    Route::delete('/cliente/{id}', [ClienteController::class, 'destroyCliente']);

    Route::get('/direccion', [DireccionController::class, 'index']);
    Route::post('/direccion', [DireccionController::class, 'storeDireccion']);
    Route::get('/direccion/{id}', [DireccionController::class, 'showDireccion']);
    Route::put('/direccion/{id}', [DireccionController::class, 'updateDireccion']);
    Route::delete('/direccion/{id}', [DireccionController::class, 'destroyDireccion']);

    Route::get('/envio', [EnvioController::class, 'index']);
    Route::post('/envio', [EnvioController::class, 'storeEnvio']);
    Route::get('/envio/{id}', [EnvioController::class, 'showEnvio']);
    Route::put('/envio/{id}', [EnvioController::class, 'updateEnvio']);
    Route::delete('/envio/{id}', [EnvioController::class, 'destroyEnvio']);

    Route::get('/factura', [FacturaController::class, 'index']);
    Route::post('/factura', [FacturaController::class, 'storeFactura']);
    Route::get('/factura/{id}', [FacturaController::class, 'showFactura']);
    Route::put('/factura/{id}', [FacturaController::class, 'updateFactura']);
    Route::delete('/factura/{id}', [FacturaController::class, 'destroyFactura']);

    Route::get('/falla_produccion', [Falla_ProduccionController::class, 'index']);
    Route::post('/falla_produccion', [Falla_ProduccionController::class, 'storeFalla']);
    Route::get('/falla_produccion/{id}', [Falla_ProduccionController::class, 'showFalla']);
    Route::put('/falla_produccion/{id}', [Falla_ProduccionController::class, 'updateFalla']);
    Route::delete('/falla_produccion/{id}', [Falla_ProduccionController::class, 'destroyFalla']);

    Route::get('/imagen_producto', [Imagen_ProductoController::class, 'index']);
    Route::post('/imagen_producto', [Imagen_ProductoController::class, 'storeImagen_Producto']);
    Route::get('/imagen_producto/{id}', [Imagen_ProductoController::class, 'showImagen_Producto']);
    Route::put('/imagen_producto/{id}', [Imagen_ProductoController::class, 'updateImagen_Producto']);
    Route::delete('/imagen_producto/{id}', [Imagen_ProductoController::class, 'destroyImagen_Producto']);

    Route::get('/insumo', [InsumoController::class, 'index']);
    Route::post('/insumo', [InsumoController::class, 'storeInsumo']);
    Route::get('/insumo/{id}', [InsumoController::class, 'showInsumo']);
    Route::put('/insumo/{id}', [InsumoController::class, 'updateInsumo']);
    Route::delete('/insumo/{id}', [InsumoController::class, 'destroyInsumo']);

    Route::get('/item', [Item_CarritoController::class, 'index']);
    Route::post('/item', [Item_CarritoController::class, 'storeItem_Carrito']);
    Route::get('/item/{id}', [Item_CarritoController::class, 'showItem_Carrito']);
    Route::put('/item/{id}', [Item_CarritoController::class, 'updateItem_Carrito']);
    Route::delete('/item/{id}', [Item_CarritoController::class, 'destroyItem_Carrito']);

    Route::get('/metodo_envio', [Metodo_EnvioController::class, 'index']);
    Route::post('/metodo_envio', [Metodo_EnvioController::class, 'storeMetodo_Envio']);
    Route::get('/metodo_envio/{id}', [Metodo_EnvioController::class, 'showMetodo_Envio']);
    Route::put('/metodo_envio/{id}', [Metodo_EnvioController::class, 'updateMetodo_Envio']);
    Route::delete('/metodo_envio/{id}', [Metodo_EnvioController::class, 'destroyMetodo_Envio']);

    Route::get('/metodo_pago', [Metodo_PagoController::class, 'index']);
    Route::post('/metodo_pago', [Metodo_PagoController::class, 'storeMetodo_Pago']);
    Route::get('/metodo_pago/{id}', [Metodo_PagoController::class, 'showMetodo_Pago']);
    Route::put('/metodo_pago/{id}', [Metodo_PagoController::class, 'updateMetodo_Pago']);
    Route::delete('/metodo_pago/{id}', [Metodo_PagoController::class, 'destroyMetodo_Pago']);

    Route::get('/movimiento_stock', [Movimiento_StockController::class, 'index']);
    Route::post('/movimiento_stock', [Movimiento_StockController::class, 'storeMovimiento_Stock']);
    Route::get('/movimiento_stock/{id}', [Movimiento_StockController::class, 'showMovimiento_Stock']);
    Route::put('/movimiento_stock/{id}', [Movimiento_StockController::class, 'updateMovimiento_Stock']);
    Route::delete('/movimiento_stock/{id}', [Movimiento_StockController::class, 'destroyMovimiento_Stock']);

    Route::get('/orden_produccion', [Orden_ProduccionController::class, 'index']);
    Route::post('/orden_produccion', [Orden_ProduccionController::class, 'storeOrden_Produccion']);
    Route::get('/orden_produccion/{id}', [Orden_ProduccionController::class, 'showOrden_Produccion']);
    Route::put('/orden_produccion/{id}', [Orden_ProduccionController::class, 'updateOrden_Produccion']);
    Route::delete('/orden_produccion/{id}', [Orden_ProduccionController::class, 'destroyOrden_Produccion']);

    Route::get('/pedido', [PedidoController::class, 'index']);
    Route::post('/pedido', [PedidoController::class, 'storePedido']);
    Route::get('/pedido/{id}', [PedidoController::class, 'showPedido']);
    Route::put('/pedido/{id}', [PedidoController::class, 'updatePedido']);
    Route::delete('/pedido/{id}', [PedidoController::class, 'destroyPedido']);

    Route::get('/producto', [ProductoController::class, 'index']);
    Route::post('/producto', [ProductoController::class, 'storeProducto']);
    Route::get('/producto/{id}', [ProductoController::class, 'showProducto']);
    Route::put('/producto/{id}', [ProductoController::class, 'updateProducto']);
    Route::delete('/producto/{id}', [ProductoController::class, 'destroyProducto']);

    Route::get('/promocion', [PromocionController::class, 'index']);
    Route::post('/promocion', [PromocionController::class, 'storePromocion']);
    Route::get('/promocion/{id}', [PromocionController::class, 'showPromocion']);
    Route::put('/promocion/{id}', [PromocionController::class, 'updatePromocion']);
    Route::delete('/promocion/{id}', [PromocionController::class, 'destroyPromocion']);

    Route::get('/proveedor', [ProveedorController::class, 'index']);
    Route::post('/proveedor', [ProveedorController::class, 'storeProveedor']);
    Route::get('/proveedor/{id}', [ProveedorController::class, 'showProveedor']);
    Route::put('/proveedor/{id}', [ProveedorController::class, 'updateProveedor']);
    Route::delete('/proveedor/{id}', [ProveedorController::class, 'destroyProveedor']);

    Route::get('/receta_produccion', [Receta_ProduccionController::class, 'index']);
    Route::post('/receta_produccion', [Receta_ProduccionController::class, 'storeReceta_Produccion']);
    Route::get('/receta_produccion/{id}', [Receta_ProduccionController::class, 'showReceta_Produccion']);
    Route::put('/receta_produccion/{id}', [Receta_ProduccionController::class, 'updateReceta_Produccion']);
    Route::delete('/receta_produccion/{id}', [Receta_ProduccionController::class, 'destroyReceta_Produccion']);

    Route::get('/rol', [RolController::class, 'index']);
    Route::post('/rol', [RolController::class, 'storeRol']);
    Route::get('/rol/{id}', [RolController::class, 'showRol']);
    Route::put('/rol/{id}', [RolController::class, 'updateRol']);
    Route::delete('/rol/{id}', [RolController::class, 'destroyRol']);

    Route::get('/transaccion_pago', [Transaccion_PagoController::class, 'index']);
    Route::post('/transaccion_pago', [Transaccion_PagoController::class, 'storeTransaccion_Pago']);
    Route::get('/transaccion_pago/{id}', [Transaccion_PagoController::class, 'showTransaccion_Pago']);
    Route::put('/transaccion_pago/{id}', [Transaccion_PagoController::class, 'updateTransaccion_Pago']);
    Route::delete('/transaccion_pago/{id}', [Transaccion_PagoController::class, 'destroyTransaccion_Pago']);

    Route::get('/usuario', [UsuarioController::class, 'index']);
    Route::post('/usuario', [UsuarioController::class, 'storeUsuario']);
    Route::get('/usuario/{id}', [UsuarioController::class, 'showUsuario']);
    Route::put('/usuario/{id}', [UsuarioController::class, 'updateUsuario']);
    Route::delete('/usuario/{id}', [UsuarioController::class, 'destroyUsuario']);

    Route::get('/variante_producto', [Variante_ProductoController::class, 'index']);
    Route::post('/variante_producto', [Variante_ProductoController::class, 'storeVariante_Produccion']);
    Route::get('/variante_producto/{id}', [Variante_ProductoController::class, 'showVariante_Produccion']);
    Route::put('/variante_producto/{id}', [Variante_ProductoController::class, 'updateVariante_Produccion']);
    Route::delete('/variante_producto/{id}', [Variante_ProductoController::class, 'destroyVariante_Produccion']);
});