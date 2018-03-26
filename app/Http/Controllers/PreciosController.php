<?php

namespace MOHA\Http\Controllers;

use Illuminate\Http\Request;
use MOHA\Operacionoferta;
use MOHA\Operaciondemanda;
use MOHA\contraoferta;
use MOHA\Demanda;
use Illuminate\Support\Facades\DB;

class PreciosController extends Controller
{
    //

	public function precios () {

		$hoy = Date('Y-m-j');//Obtengo la fecha actual
		
		$mes = strtotime('-1 month', strtotime($hoy));
		$mes = strtotime('Y-m-j', $mes);

		$mes2 = strtotime('-2 month', strtotime($hoy));
		$mes2 = strtotime('Y-m-j', $mes2);

		$fecha = strtotime('-7 day', strtotime($hoy));//Resto 7 días a la fecha actual
															 //Para luego filtrar los precios solo de la ultima semana
		$fecha = date('Y-m-j', $fecha);//Doy formato a la fecha resultante

		//Obtengo precios del Día
		$preciosd = Operacionoferta::leftJoin('contraofertas', 'operacionofertas.id_contra', '=', 'contraofertas.id')
										->join('ofertas', 'contraofertas.id_oferta', '=', 'ofertas.id')
										->join('productos', 'ofertas.id_prod', '=', 'productos.id')
										->join('modos', 'ofertas.id_modo', '=', 'modos.id')
										->join('medidas', 'ofertas.id_medida', '=', 'medidas.id')
										->select(DB::raw('CONCAT(productos.nombre, " ", productos.descripcion, " ", productos.descripcion2, " ", modos.descripcion, " ", "X", " ", ofertas.peso, " ",  medidas.descripcion) as nombre'), DB::raw('max(contraofertas.precio) as max'), DB::raw('min(contraofertas.precio) as min'), DB::raw('CAST(avg(contraofertas.precio) as int) AS prom'))
										->whereDate('operacionofertas.fecha', '=', $hoy)
										->groupBy('productos.nombre')
										->groupBy('productos.descripcion')
										->groupBy('productos.descripcion2')
										->groupBy('modos.descripcion')
										->groupBy('ofertas.peso')
										->groupBy('medidas.descripcion')
										->orderBy('productos.nombre', 'DESC')
										->get(['operacionofertas.*']);

		//Precios Ofrecidos
		$precioso = Contraoferta::leftJoin('ofertas', 'contraofertas.id_oferta', '=', 'ofertas.id')
										->join('productos', 'ofertas.id_prod', '=', 'productos.id')
										->join('modos', 'ofertas.id_modo', '=', 'modos.id')
										->join('medidas', 'ofertas.id_medida', '=', 'medidas.id')
										->select(DB::raw('CONCAT(productos.nombre, " ", productos.descripcion, " ", productos.descripcion2, " ", modos.descripcion, " ", "X", " ", ofertas.peso, " ",  medidas.descripcion) as nombre'), DB::raw('max(contraofertas.precio) as max'), DB::raw('min(contraofertas.precio) as min'), DB::raw('CAST(avg(contraofertas.precio) as int) AS prom'))
										->groupBy('productos.nombre')
										->groupBy('productos.descripcion')
										->groupBy('productos.descripcion2')
										->groupBy('modos.descripcion')
										->groupBy('ofertas.peso')
										->groupBy('medidas.descripcion')
										->orderBy('productos.nombre', 'DESC')
										->get(['contraofertas.*']);

		//Tendencia Históricos
		$preciost = Demanda::leftJoin('productos', 'demandas.id_prod', '=', 'productos.id')
										->join('modos', 'demandas.id_modo', '=', 'modos.id')
										->join('medidas', 'demandas.id_medida', '=', 'medidas.id')
										->join('puestos', 'demandas.id_puesto', '=', 'puestos.id')
										->select(DB::raw('CONCAT(productos.nombre, " ", productos.descripcion, " ", productos.descripcion2, " ", modos.descripcion, " ", "X", " ", demandas.peso, " ",  medidas.descripcion) as nombre'), DB::raw('max(demandas.precio) as max'), DB::raw('min(demandas.precio) as min'), DB::raw('CAST(avg(demandas.precio) as int) AS prom'))
										->where('puestos.descripcion', 'LIKE', '%buenos aires%')
										->groupBy('productos.nombre')
										->groupBy('productos.descripcion')
										->groupBy('productos.descripcion2')
										->groupBy('modos.descripcion')
										->groupBy('demandas.peso')
										->groupBy('medidas.descripcion')
										->orderBy('productos.nombre', 'DESC')
										->get(['demandas.*']);

		return view('precios', array('preciosd' => $preciosd, 'precioso' => $precioso, 'preciost' => $preciost));
	}

	public function filtrarPrecios(Request $request) {
		
		if(empty($request->precioDia)){
			$fecha = Date('Y-m-j');
		}else{
			$fecha = $request->precioDia;
		}
		
		if(empty($request->fechai)){
			$hoy = Date('Y-m-j');
		
			$mes = strtotime('-1 month', strtotime($hoy));
			$fechai = strtotime('Y-m-j', $mes);
			$fechaf = $hoy;
		}else{
			$fechai = $request->fechai;
			$fechaf = $request->fechaf;
		}
		

		$preciosd = Operacionoferta::leftJoin('contraofertas', 'operacionofertas.id_contra', '=', 'contraofertas.id')
										->join('ofertas', 'contraofertas.id_oferta', '=', 'ofertas.id')
										->join('productos', 'ofertas.id_prod', '=', 'productos.id')
										->join('modos', 'ofertas.id_modo', '=', 'modos.id')
										->join('medidas', 'ofertas.id_medida', '=', 'medidas.id')
										->select(DB::raw('CONCAT(productos.nombre, " ", productos.descripcion, " ", productos.descripcion2, " ", modos.descripcion, " ", "X", " ", ofertas.peso, " ",  medidas.descripcion) as nombre'), DB::raw('max(contraofertas.precio) as max'), DB::raw('min(contraofertas.precio) as min'), DB::raw('CAST(avg(contraofertas.precio) as int) AS prom'))
										->whereDate('operacionofertas.fecha', '=', $fecha)
										->groupBy('productos.nombre')
										->groupBy('productos.descripcion')
										->groupBy('productos.descripcion2')
										->groupBy('modos.descripcion')
										->groupBy('ofertas.peso')
										->groupBy('medidas.descripcion')
										->orderBy('productos.nombre', 'DESC')
										->get(['operacionofertas.*']);
		//Precios Demandados
		$precioso = Contraoferta::leftJoin('ofertas', 'contraofertas.id_oferta', '=', 'ofertas.id')
										->join('productos', 'ofertas.id_prod', '=', 'productos.id')
										->join('modos', 'ofertas.id_modo', '=', 'modos.id')
										->join('medidas', 'ofertas.id_medida', '=', 'medidas.id')
										->select(DB::raw("productos.id as id"), DB::raw('CONCAT(productos.nombre, " ", productos.descripcion, " ", productos.descripcion2, " ", modos.descripcion, " ", "X", " ", ofertas.peso, " ",  medidas.descripcion) as nombre'), DB::raw('max(contraofertas.precio) as max'), DB::raw('min(contraofertas.precio) as min'), DB::raw('CAST(avg(contraofertas.precio) as int) AS prom'))
										->whereBetween('contraofertas.created_at', [$fechai, $fechaf])
										->groupBy('productos.nombre')
										->groupBy('productos.descripcion')
										->groupBy('productos.descripcion2')
										->groupBy('modos.descripcion')
										->groupBy('ofertas.peso')
										->groupBy('medidas.descripcion')
										->orderBy('productos.nombre', 'DESC')
										->get(['contraofertas.*']);

		//Tendencia Históricos
		$preciost = Demanda::leftJoin('productos', 'demandas.id_prod', '=', 'productos.id')
										->join('modos', 'demandas.id_modo', '=', 'modos.id')
										->join('medidas', 'demandas.id_medida', '=', 'medidas.id')
										->join('puestos', 'demandas.id_puesto', '=', 'puestos.id')
										->select(DB::raw('CONCAT(productos.nombre, " ", productos.descripcion, " ", productos.descripcion2, " ", modos.descripcion, " ", "X", " ", demandas.peso, " ",  medidas.descripcion) as nombre'), DB::raw('max(demandas.precio) as max'), DB::raw('min(demandas.precio) as min'), DB::raw('CAST(avg(demandas.precio) as int) AS prom'))
										->where('puestos.descripcion', 'LIKE', '%buenos aires%')
										->groupBy('productos.nombre')
										->groupBy('productos.descripcion')
										->groupBy('productos.descripcion2')
										->groupBy('modos.descripcion')
										->groupBy('demandas.peso')
										->groupBy('medidas.descripcion')
										->orderBy('productos.nombre', 'DESC')
										->get(['demandas.*']);

		return view('precios', array('preciosd' => $preciosd, 'precioso' => $precioso, 'preciost' => $preciost));
	}
	

	public function graficarPrecios($id) {
		$viewer = Contraoferta::leftJoin('ofertas', 'contraofertas.id_oferta', '=', 'ofertas.id')
		->join('productos', 'ofertas.id_prod', '=', 'productos.id')
		->join('modos', 'ofertas.id_modo', '=', 'modos.id')
		->join('medidas', 'ofertas.id_medida', '=', 'medidas.id')
		->select(DB::raw('CONCAT(productos.nombre, " ", productos.descripcion, " ", productos.descripcion2, " ", modos.descripcion, " ", "X", " ", ofertas.peso, " ",  medidas.descripcion) as nombre'), DB::raw('max(contraofertas.precio) as max'), DB::raw('min(contraofertas.precio) as min'), DB::raw('CAST(avg(contraofertas.precio) as int) AS prom'))
		->where('productos.id', $id)
		->groupBy('productos.nombre')
		->groupBy('productos.descripcion')
		->groupBy('productos.descripcion2')
		->groupBy('modos.descripcion')
		->groupBy('ofertas.peso')
		->groupBy('medidas.descripcion')
		->orderBy('contraofertas.created_at', 'DESC')
		->get(['contraofertas.*'])->toArray();
		
		$max = array_column($viewer, 'max');
		$min = array_column($viewer, 'min');
		$prom = array_column($viewer, 'prom');
		$nombre = array_column($viewer, 'nombre');

		return view('/reportes/precios')->with('max',json_encode($max,JSON_NUMERIC_CHECK))
										->with('min',json_encode($min,JSON_NUMERIC_CHECK))
										->with('prom',json_encode($prom,JSON_NUMERIC_CHECK))
										->with('nombre',json_encode($nombre,JSON_NUMERIC_CHECK));

	}
	
}
