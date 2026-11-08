<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminAvisoController extends Controller
{
    private static function consultaReportes()
    {
        return DB::table('reporte_cliente as r')
            ->join('cliente as c', 'c.Id_cliente', '=', 'r.Id_cliente')
            ->where('r.Oculto_admin', 0)
            ->select('r.Id_reporte', 'r.Asunto', 'r.Mensaje', 'r.Leido', 'r.Fecha_creacion', 'r.Respuesta', 'r.Fecha_respuesta', 'c.Id_usuario', 'c.Nombre', 'c.Apellido')
            ->orderByDesc('r.Id_reporte');
    }

    private static function agregarMails($filas)
    {
        $ids = $filas->pluck('Id_usuario')->unique()->all();
        $usuarios = User::whereIn('Id_usuario', $ids)->get()->keyBy('Id_usuario');

        return $filas->map(function ($fila) use ($usuarios) {
            $usuario = $usuarios->get($fila->Id_usuario);
            $fila->email = $usuario ? $usuario->email : '';
            return $fila;
        });
    }

    public static function pendientes()
    {
        $vacio = (object) ['total' => 0, 'lista' => collect()];

        if (!Schema::hasTable('reporte_cliente')) {
            return $vacio;
        }

        $filas = self::consultaReportes()->where('r.Leido', 0)->get();

        if ($filas->isEmpty()) {
            return $vacio;
        }

        return (object) [
            'total' => $filas->count(),
            'lista' => self::agregarMails($filas->take(3)->values()),
        ];
    }

    public function reportes()
    {
        $filas = self::consultaReportes()->get();

        return response()->json(self::agregarMails($filas)->values());
    }

    public function contador()
    {
        $total = DB::table('reporte_cliente')
            ->where('Leido', 0)
            ->where('Oculto_admin', 0)
            ->count();

        return response()->json(['total' => $total]);
    }

    public function marcarLeido($id)
    {
        DB::table('reporte_cliente')->where('Id_reporte', (int) $id)->update(['Leido' => 1]);

        return response()->json(['message' => 'Marcado como leído']);
    }

    public function eliminar($id)
    {
        $reporte = DB::table('reporte_cliente')->where('Id_reporte', (int) $id)->first();

        if (!$reporte) {
            return response()->json(['message' => 'Reporte no encontrado.'], 404);
        }

        if ((int) $reporte->Oculto_cliente === 1) {
            DB::table('reporte_cliente')->where('Id_reporte', $reporte->Id_reporte)->delete();
        } else {
            DB::table('reporte_cliente')
                ->where('Id_reporte', $reporte->Id_reporte)
                ->update(['Oculto_admin' => 1, 'Leido' => 1]);
        }

        return response()->json(['message' => 'Reporte eliminado.']);
    }

    public function responder(Request $request, $id)
    {
        $datos = $request->validate([
            'respuesta' => ['required', 'string', 'min:2', 'max:2000'],
        ], [
            'required' => 'Escribí la respuesta.',
            'min'      => 'La respuesta es muy corta.',
            'max'      => 'La respuesta puede tener hasta :max caracteres.',
        ]);

        $actualizados = DB::table('reporte_cliente')
            ->where('Id_reporte', (int) $id)
            ->whereNull('Respuesta')
            ->update([
                'Respuesta'       => $datos['respuesta'],
                'Fecha_respuesta' => DB::raw('CURRENT_TIMESTAMP'),
                'Leido'           => 1,
                'Respuesta_vista' => 0,
            ]);

        if ($actualizados === 0) {
            return response()->json(['message' => 'Este reporte ya tiene respuesta o no existe.'], 422);
        }

        return response()->json(['message' => 'Respuesta enviada']);
    }
}