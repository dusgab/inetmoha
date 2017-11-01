@extends('layouts.principal')

@section('content')
	<center><h3>Detalle de Contra Oferta</h3></center>
	@if(empty($cofertas))
		<center><h4>La Oferta no posee ninguna Orden de Compra</h4></center> 
	@else
	<div class="row">
		<div class="col-md-12">
          <h1 class="h1-tabla">Oferta Original</h1>
          <div class="table-responsive">
              <table class="table">
                  <thead>
                      <tr>
                          <th>Producto</th>
                          <th>Cantidad</th>
                          <th>Precio</th>
                          <th>Fecha Fin</th>
                          <th>Puesto</th>
                          <th>Cobro</th>
                          <th>Modo</th>
                      </tr>
                  </thead>
                     <tbody>
                         <tr>
                           <input type="hidden" name="id" value="{{$of->id}}">
                         	<td><input type="text" class="input-table" name="producto" value="{{$of->producto->nombre}}" disabled></td>
                         	<td><input type="text" class="input-table" name="cantidad" value="{{$of->cantidad}}" readonly="true"></td>
                         	<td><input type="text" class="input-table" name="precio" value="{{$of->precio}}" readonly="true"></td>
                         	<td><input type="text" class="input-table" name="fechafin" value="{{$of->fechaFin}}" readonly="true"></td>
                         	<td><input type="text" class="input-table" name="puesto" value="{{$of->puesto}}" readonly="true"></td>
                         	<td><input type="text" class="input-table" name="cobro" value="{{$of->cobro}}" readonly="true"></td>
                         	<td><input type="text" class="input-table" name="modo" value="{{$of->modo}}" readonly="true"></td>
                         </tr>
                     </tbody>
              </table>
          </div>
		</div>
		</div>
		<hr>
		<div class="row">
      <div class="col-md-6">
          <h1 class="h1-tabla">Ordenes de Compra (Contra Ofertas)</h1>
          <div class="table-responsive">
              <table class="table">
                  <thead>
                      <tr>
                          <th>Comprador</th>
                          <th>Cantidad</th>
                          <th></th>
                      </tr>
                  </thead>
                  @foreach($cofertas as $co)
                     <tbody>
                         <tr> 
                         	<td><input type="text" class="input-table" name="comprador" value="{{$co->user->apellido}} {{$co->user->name}}" disabled></td>
                         	<td><input type="text" class="input-table" name="cantidad" value="{{$co->cant}}" readonly="true"></td>
                         	<td><a type="button" href="/usuario/aceptarOferta/{{$co->id}}" class="btn btn-success admin tabla" title="Aceptar Contra Oferta">Aceptar Contra Oferta</a></td>
                         </tr>
                     </tbody>
                 @endforeach
              </table>
          </div>
      </div>
   	</div>
    @endif
    <a type="button" href="/usuario/ofertas" class="btn btn-primary admin" title="Volver">Volver</a>
@endsection