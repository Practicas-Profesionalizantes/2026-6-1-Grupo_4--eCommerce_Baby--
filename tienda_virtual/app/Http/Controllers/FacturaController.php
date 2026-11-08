<?php

namespace App\Http\Controllers;

use App\Models\FacturaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    private const VENDEDOR = [
        'razon_social'  => 'Kiddy Shop',
        'cuit'          => '00-00000000-0',
        'condicion_iva' => 'Responsable Inscripto',
        'domicilio'     => 'Ciudad Autónoma de Buenos Aires, Argentina',
        'punto_venta'   => '0001',
    ];

    public function index()
    {
        $facturas = DB::table('factura as f')
            ->join('pedido as p', 'p.Id_pedido', '=', 'f.Id_pedido')
            ->join('cliente as c', 'c.Id_cliente', '=', 'p.Id_cliente')
            ->join('usuario as u', 'u.Id_usuario', '=', 'c.Id_usuario')
            ->select(
                'f.Id_factura',
                'f.Id_pedido',
                'f.Tipo_Comprobante',
                'f.Nro_Comprobante',
                'f.CUIT_Receptor',
                'f.Fecha_Emision',
                'f.Leida',
                'p.Total_Final',
                'c.Nombre',
                'c.Apellido',
                'u.Email as Mail'
            )
            ->orderBy('f.Id_factura', 'desc')
            ->get();

        return response()->json($facturas);
    }

    public function contador()
    {
        $total = DB::table('factura')->where('Leida', 0)->count();
        return response()->json(['total' => $total]);
    }

    public function marcarLeida($id)
    {
        DB::table('factura')->where('Id_factura', $id)->update(['Leida' => 1]);
        return response()->json(['success' => true]);
    }

    public function misFacturas()
    {
        $idCliente = DB::table('cliente')
            ->where('Id_usuario', Auth::user()->Id_usuario)
            ->value('Id_cliente');

        if (!$idCliente) {
            return response()->json([]);
        }

        $filas = DB::table('factura as f')
            ->join('pedido as p', 'p.Id_pedido', '=', 'f.Id_pedido')
            ->where('p.Id_cliente', $idCliente)
            ->select('f.Id_pedido as pedido', 'f.Id_factura as factura')
            ->get();

        return response()->json($filas);
    }

    public function comprobante($id)
    {
        $f = DB::table('factura as f')
            ->join('pedido as p', 'p.Id_pedido', '=', 'f.Id_pedido')
            ->join('cliente as c', 'c.Id_cliente', '=', 'p.Id_cliente')
            ->join('usuario as u', 'u.Id_usuario', '=', 'c.Id_usuario')
            ->leftJoin('direccion as d', 'd.Id_direccion', '=', 'p.Id_direccion')
            ->leftJoin('metodo_envio as me', 'me.Id_metodo_envio', '=', 'p.Id_metodo_envio')
            ->where('f.Id_factura', $id)
            ->select(
                'f.Id_factura',
                'f.Id_pedido',
                'f.Tipo_Comprobante',
                'f.Nro_Comprobante',
                'f.CUIT_Receptor',
                'f.Fecha_Emision',
                'p.Fecha_Pedido',
                'p.Subtotal',
                'p.Descuento_Aplicado',
                'p.Total_Final',
                'p.Destinatario_Nombre',
                'p.Destinatario_Apellido',
                'p.Destinatario_Telefono',
                'c.Nombre',
                'c.Apellido',
                'c.Condicion_IVA',
                'u.Id_usuario',
                'u.Email',
                'd.Calle_Numero',
                'd.Calle',
                'd.Numero',
                'd.Departamento',
                'd.Barrio',
                'd.Ciudad',
                'd.Provincia',
                'd.Codigo_Postal',
                'me.Nombre as Envio'
            )
            ->first();

        if (!$f) {
            return response()->json(['message' => 'No encontrada'], 404);
        }

        $usuario = Auth::user();
        $esAdmin = (int) $usuario->Id_rol === 2;

        if (!$esAdmin && (int) $f->Id_usuario !== (int) $usuario->Id_usuario) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $items = DB::table('detalle_pedido as dp')
            ->join('variante_producto as v', 'v.Id_variante', '=', 'dp.Id_variante')
            ->join('producto as pr', 'pr.Id_producto', '=', 'v.Id_producto')
            ->where('dp.Id_pedido', $f->Id_pedido)
            ->orderBy('dp.Id_detalle')
            ->select('pr.Nombre as Producto', 'v.Talle', 'v.Color', 'dp.Cantidad', 'dp.Precio_Unitario_Cobrado')
            ->get()
            ->map(function ($x) {
                return [
                    'nombre'   => $x->Producto,
                    'talle'    => $x->Talle,
                    'color'    => $x->Color,
                    'cantidad' => (int) $x->Cantidad,
                    'precio'   => (float) $x->Precio_Unitario_Cobrado,
                    'subtotal' => round((float) $x->Precio_Unitario_Cobrado * (int) $x->Cantidad, 2),
                ];
            })
            ->values();

        $pago = DB::table('transaccion_pago as t')
            ->join('metodo_pago as m', 'm.Id_metodo_pago', '=', 't.Id_metodo_pago')
            ->where('t.Id_pedido', $f->Id_pedido)
            ->orderBy('t.Id_transaccion')
            ->select('m.Nombre as Metodo', 't.Estado_Pago')
            ->first();

        $subtotal  = (float) $f->Subtotal;
        $descuento = (float) $f->Descuento_Aplicado;
        $total     = (float) $f->Total_Final;
        $envio     = max(0, round($total - ($subtotal - $descuento), 2));
        $iva       = round($total / 1.21 * 0.21, 2);

        $documento = $f->CUIT_Receptor;
        if (!$documento || preg_match('/^0+$/', $documento)) {
            $documento = null;
        }

        $direccion = null;
        if ($f->Calle_Numero !== null) {
            $calle = $f->Calle_Numero;
            if ($f->Calle !== null && $f->Calle !== '') {
                $calle = trim($f->Calle . ' ' . $f->Numero);
                if ($f->Departamento !== null && $f->Departamento !== '') {
                    $calle .= ', Dpto ' . $f->Departamento;
                }
                if ($f->Barrio !== null && $f->Barrio !== '') {
                    $calle .= ', Barrio ' . $f->Barrio;
                }
            }
            $direccion = $calle . ', ' . $f->Ciudad . ', ' . $f->Provincia;
            if ($f->Codigo_Postal) {
                $direccion .= ' (CP ' . $f->Codigo_Postal . ')';
            }
        }

        $destinatario = null;
        if ($f->Destinatario_Nombre !== null) {
            $destinatario = [
                'nombre'   => trim($f->Destinatario_Nombre . ' ' . $f->Destinatario_Apellido),
                'telefono' => $f->Destinatario_Telefono,
            ];
        }

        return response()->json([
            'es_admin' => $esAdmin,
            'vendedor' => self::VENDEDOR,
            'comprobante' => [
                'id'            => $f->Id_factura,
                'tipo'          => $f->Tipo_Comprobante,
                'numero'        => $f->Nro_Comprobante,
                'fecha_emision' => $f->Fecha_Emision,
                'fecha_pedido'  => $f->Fecha_Pedido,
                'pedido'        => $f->Id_pedido,
            ],
            'receptor' => [
                'nombre'        => trim($f->Nombre . ' ' . $f->Apellido),
                'documento'     => $documento,
                'condicion_iva' => $f->Condicion_IVA ?: 'Consumidor Final',
                'email'         => $f->Email,
                'direccion'     => $direccion,
            ],
            'destinatario' => $destinatario,
            'items' => $items,
            'totales' => [
                'subtotal'       => $subtotal,
                'descuento'      => $descuento,
                'envio'          => $envio,
                'total'          => $total,
                'iva_contenido'  => $iva,
            ],
            'envio' => $f->Envio,
            'pago'  => $pago ? ['metodo' => $pago->Metodo, 'estado' => $pago->Estado_Pago] : null,
        ]);
    }

    public function showFactura($id)
    {
        $factura = FacturaModel::findOrFail($id);
        return response()->json($factura);
    }

    public function updateFactura(Request $actualizacion, $id)
    {
        $factura = FacturaModel::findOrFail($id);
        $factura->update($actualizacion->all());
        return response()->json([
            'message' => 'Actualizado con éxito',
            'data' => $factura
        ]);
    }

    public function destroyFactura($id)
    {
        $factura = FacturaModel::findOrFail($id);
        $factura->delete();
        return response()->json(['message' => 'Eliminado con éxito']);
    }
}