<?php
/**
 * Copyright (c) 2016 Ely Colmenarez
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace SISAUGES\Http\Controllers;

use Illuminate\Http\Request;

use SISAUGES\ClasificacionActividad;
use SISAUGES\Http\Requests;
use SISAUGES\Http\Controllers\Controller;
use SISAUGES\SectorActividad;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;
/**
 * Class SectorActividadController
 *
 * Esta clase se diseño para manejar las transancciones del sector en la base de
 * datos, estas pueden ser agregar,
 * modificar, eliminar o listar.
 *
 * @author Ely Colmenarez ElyJColmenarez@Gmail.com
 * @copyright 2016 Ely Colmenarez
 * @package SISAUGES\Http\Controllers
 */
class SectorActividadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Metodo diseñado para direccionar a la pantalla principal del modulo
     *
     * Este metodo redirige a la pantalla principal del modulo sector
     * esta pantalla mostrara un listado de los sectors agregados y un boton para modificar
     * y eliminar
     *
     * @param void
     *
     * @return $sector devuelve objeto de tipo sector
     */
    public function index()
    {
        $sectorActividad = DB::table('sector_actividad')->orderBy('id_sector_ac')->get();
        return view('sectoractividad.index')->with('sectorActividad',$sectorActividad);
    }

    public function renderform(Request $request){

        if ($request->typeform=='add') {
            $action="test/crear";
        }elseif($request->typeform=='modify'){
            $action="test/modificar";
        }

        $fields=array(

            'nombre' => array(
                'type'  => 'text',
                'value' => '',
                'id'    => 'nombre',
                'label' => 'Campo'
            ),
            'apellido' => array(
                'type'  => 'text',
                'value' => '',
                'id'    => 'apellido',
                'label' => 'Campo'
            ),
            'cedula' => array(
                'type'  => 'text',
                'value' => '',
                'id'    => 'cedula',
                'label' => 'Campo'
            ),
            'telefono' => array(
                'type'      => 'select',
                'value'     => '',
                'id'        => 'telefono',
                'label'     => 'Campo',
                'options'   => array(
                    '212',
                    '412',
                    '414',
                    '424',
                    '416',
                    '426'
                )
            ),
            'rol' => array(
                'type'      => 'select',
                'value'     => '',
                'id'        => 'telefono',
                'label'     => 'Campo',
                'options'   => array(
                    'XXX',
                    'YYY',
                    'ZZZ',
                )
            ),
            'email' => array(
                'type'  => 'text',
                'value' => '',
                'id'    => 'email',
                'label' => 'Campo'
            ),
            'username' => array(
                'type'  => 'text',
                'value' => '',
                'id'    => 'username',
                'label' => 'Campo'
            ),
            'password' => array(
                'type'  => 'text',
                'value' => '',
                'id'    => 'password',
                'label' => 'Campo'
            ),
        );

        $htmlbody=View::make('layouts.regularform',compact('action','fields'))->render();

        if ($htmlbody) {
            $retorno=array(
                'result'=>true,
                'html'  =>$htmlbody
            );
        }else{
            $retorno=array(
                'result'=>false,
            );
        }

        echo json_encode($retorno);

    }
    
    /**
     * Metodo diseñado para direccionar a la pantalla de agregar un sector
     *
     * Este metodo redirige a la pantalla agregar sector
     * la cual mostrara un formulario con los campos necesarios para almacenar
     * en la base de datos
     *
     * @param void
     *
     * @return $sector devuelve objeto de tipo sector
     */
    public function create()
    {
        return view('sectoractividad.crear');
    }
    /**
     * Metodo diseñado para almacenar el sector en la base de datos
     *
     * @param void
     *
     * @return $menssage retorna el resultado de la operación
     */
    public function store()
    {
        $sectorActividad = new SectorActividad();
        $sectorActividad->descripcion_sector = \Request::Input('descripcionSector');
        $sectorActividad->save();
        return redirect('sectorActividad')->with('message','Se ha realizado la insercion con exito');

    }
    /**
     * Metodo diseñado para direccionar a la pantalla de editar un sector
     *
     * Este metodo redirige a la pantalla editar sector
     * la cual mostrara un formulario con los datos del representate seleccionado,
     * con los campos necesarios para almacenar en la base de datos
     *
     * @param $id codigo de asociación del sector en la base de datos
     *
     * @return $array un arreglo de objetos de los datos del sector
     */
    public function edit($id)
    {
        $sectorActividad = SectorActividad::find($id);
        return view('sectoractividad.editar')->with(['sectorActividad'=>$sectorActividad]);
    }
    /**
     * Metodo diseñado para actualizar los datos de un sector en la base de datos
     *
     * @param $id codigo de asociación del sector en la base de datos, $request datos enviados atravez del formulario
     *
     * @return $menssage retorna el resultado de la operación.
     */
    public function update($id)
    {
        $sectorActividad = SectorActividad::find($id);
        $campo = $sectorActividad->descripcion_sector;
        $sectorActividad->descripcion_sector = \Request::Input('descripcionSector');
        $sectorActividad->save();
        return redirect('sectorActividad')
                ->with('message','Se ha realizado la modificacion del campo: '. $campo
                        .' a: '.$sectorActividad->descripcion_sector.' con éxito');
    }
    /**
     * Metodo diseñado para eliminar los datos de un sector en la base de datos
     *
     * @param $id codigo de asociación del sector en la base de datos
     *
     * @return $menssage retorna el resultado de la operación.
     */
    public function destroy($id)
    {
        $sectorActividad = SectorActividad::find($id);
        $sectorActividad->delete();
        return redirect('sectorActividad')->with('message','Se ha realizado una eliminacion con exito');
    }
}
