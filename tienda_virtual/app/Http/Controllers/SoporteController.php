<?php

namespace App\Http\Controllers;

use App\Models\ClienteModel;
use App\Support\AvisoAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoporteController extends Controller
{
    private function idCliente(Request $request)
    {
        return DB::table('cliente')
            ->where('Id_usuario', $request->user()->Id_usuario)
            ->value('Id_cliente');
    }

    public function misReportes(Request $request)
    {
        $idCliente = $this->idCliente($request);

        if (!$idCliente) {
            return response()->json([]);
        }

        $reportes = DB::table('reporte_cliente')
            ->where('Id_cliente', $idCliente)
            ->where('Oculto_cliente', 0)
            ->orderByDesc('Id_reporte')
            ->get(['Id_reporte', 'Asunto', 'Mensaje', 'Fecha_creacion', 'Respuesta', 'Fecha_respuesta', 'Respuesta_vista']);

        return response()->json($reportes);
    }

    public function contador(Request $request)
    {
        $idCliente = $this->idCliente($request);

        if (!$idCliente) {
            return response()->json(['total' => 0]);
        }

        $total = DB::table('reporte_cliente')
            ->where('Id_cliente', $idCliente)
            ->where('Oculto_cliente', 0)
            ->whereNotNull('Respuesta')
            ->where('Respuesta_vista', 0)
            ->count();

        return response()->json(['total' => $total]);
    }

    public function marcarVistas(Request $request)
    {
        $idCliente = $this->idCliente($request);

        if ($idCliente) {
            DB::table('reporte_cliente')
                ->where('Id_cliente', $idCliente)
                ->whereNotNull('Respuesta')
                ->where('Respuesta_vista', 0)
                ->update(['Respuesta_vista' => 1]);
        }

        return response()->json(['message' => 'Respuestas marcadas como vistas']);
    }

    public function eliminar(Request $request, $id)
    {
        $idCliente = $this->idCliente($request);

        if (!$idCliente) {
            return response()->json(['message' => 'Mensaje no encontrado.'], 404);
        }

        $reporte = DB::table('reporte_cliente')
            ->where('Id_reporte', (int) $id)
            ->where('Id_cliente', $idCliente)
            ->first();

        if (!$reporte) {
            return response()->json(['message' => 'Mensaje no encontrado.'], 404);
        }

        $adminNoLoVio = (int) $reporte->Leido === 0;
        $adminYaLoOculto = (int) $reporte->Oculto_admin === 1;

        if ($adminNoLoVio || $adminYaLoOculto) {
            DB::table('reporte_cliente')->where('Id_reporte', $reporte->Id_reporte)->delete();
        } else {
            DB::table('reporte_cliente')
                ->where('Id_reporte', $reporte->Id_reporte)
                ->update(['Oculto_cliente' => 1]);
        }

        return response()->json(['message' => 'Mensaje eliminado.']);
    }

    public function enviar(Request $request)
    {
        $datos = $request->validate([
            'asunto'  => ['required', 'string', 'max:150'],
            'mensaje' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'required' => 'Completá el campo :attribute.',
            'min'      => 'El campo :attribute tiene que tener al menos :min caracteres.',
            'max'      => 'El campo :attribute puede tener hasta :max caracteres.',
            'string'   => 'El campo :attribute no es válido.',
        ], [
            'asunto'  => 'asunto',
            'mensaje' => 'mensaje',
        ]);

        $cliente = ClienteModel::firstOrCreate(
            ['Id_usuario' => $request->user()->Id_usuario],
            [
                'Nombre'        => '',
                'Apellido'      => '',
                'DNI_CUIT'      => null,
                'Telefono'      => '00000000',
                'Condicion_IVA' => 'Consumidor Final',
            ]
        );

        DB::table('reporte_cliente')->insert([
            'Id_cliente' => $cliente->Id_cliente,
            'Asunto'     => $datos['asunto'],
            'Mensaje'    => $datos['mensaje'],
            'Leido'      => 0,
        ]);

        $nombre = trim(($cliente->Nombre ?? '') . ' ' . ($cliente->Apellido ?? ''));

        AvisoAdmin::enviar(
            'Kiddy Shop: nuevo reporte de un cliente',
            [
                'Un cliente envió un reporte.',
                'Cliente: ' . ($nombre !== '' ? $nombre : 'sin nombre cargado') . ' (' . $request->user()->email . ').',
                'Asunto: ' . $datos['asunto'],
                'Mensaje: ' . $datos['mensaje'],
            ],
            url('/admin/problemas')
        );

        return response()->json(['message' => '¡Gracias! Recibimos tu mensaje y lo vamos a revisar.'], 201);
    }
}