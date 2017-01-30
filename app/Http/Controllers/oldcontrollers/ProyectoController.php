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

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use SISAUGES\Proyecto;
use SISAUGES\Http\Requests;
use SISAUGES\Http\Controllers\Controller;
use SISAUGES\Institucion;
use SISAUGES\Representante;
use SISAUGES\SectorActividad;

use Illuminate\Support\Facades\View;
/**
 * Class ActividadController
 *
 * Esta clase se diseño para manejar las transancciones de la actividad en la base de
 * datos, estas pueden ser agregar,
 * modificar, eliminar o listar.
 *
 * @author Ely Colmenarez ElyJColmenarez@Gmail.com
 * @copyright 2016 Ely Colmenarez
 * @package SISAUGES\Http\Controllers
 */
class ProyectoController extends Controller
{
    protected $sector;
    protected $institucion;

    public function __construct()
    {
        $this->middleware('auth');
        $this->sector           =   SectorActividad::all()->pluck('descripcion_sector','id_sector_ac');
        $this->institucion    =   Institucion::all()->pluck('institucion','id_institucion');
    }
    /**
     * Metodo diseñado para direccionar a la pantalla principal del modulo
     *
     * Este metodo redirige a la pantalla principal del modulo actividad
     * esta pantalla mostrara un listado de la actividad agregada y un boton para modificar
     * y eliminar
     *
     * @param void
     *
     * @return $actividad devuelve objeto de tipo actividad
     */
    public function index(/*Request $request*/)
    {
        //$nombreActividad=$request->input('nombreActividad');
        $actividad = Proyecto::with('sector')->orderBy('id_actividad','asc')->paginate(5);
        $actividad->setPath('actividad');
        return view('actividad.index')->with('actividad',$actividad);
    }
    /**
     * Metodo diseñado para direccionar a la pantalla de agregar una actividad
     *
     * Este metodo redirige a la pantalla agregar actividad
     * la cual mostrara un formulario con los campos necesarios para almacenar
     * en la base de datos
     *
     * @param void
     *
     * @return $actividad devuelve objeto de tipo actividad
     */
    public function create()
    {
        return view('actividad.crear')->with(['sectorActividad' =>  $this->sector,
                                              'institucion'   =>  $this->institucion]);
    }
    /**
     * Metodo diseñado para almacenar la actividad en la base de datos
     *
     * @param void
     *
     * @return $menssage retorna el resultado de la operación
     */
    public function store()
    {
        $actividad = new Proyecto();
        $actividad->nombre_actividad    = \Request::Input('nombreActividad');
        $actividad->status_actividad    = \Request::Input('statusActividad');
        $actividad->permiso_actividad   = \Request::Input('permisoActividad');
        $actividad->sector()->associate(\Request::Input('sectorActividad'));
        $actividad->save();

        $representante = \Request::Input('representante');

        foreach($representante as $rep)
        {
            $actividad->institucion()->attach($rep);
        }


        return redirect('actividad')->with('message','Se ha agregado una Actividad con exito');
    }
    /**
     * Metodo diseñado para direccionar a la pantalla de editar una actividad
     *
     * Este metodo redirige a la pantalla editar actividad
     * la cual mostrara un formulario con los datos del representate seleccionado,
     * con los campos necesarios para almacenar en la base de datos
     *
     * @param $id codigo de asociación de la actividad en la base de datos
     *
     * @return $array un arreglo de objetos de los datos de la actividad
     */
    public function edit($id)
    {
        $actividad = Proyecto::find($id);
        $representanteSeleccionado = Proyecto::find($id)->institucion()
                                    ->pluck('institucion_proyecto.id_institucion')
                                    ->toArray();
        return view('actividad.editar')->with([ 'actividad'                 =>  $actividad,
                                                'sectorActividad'           =>  $this->sector,
                                                'institucion'               =>  $this->institucion,
                                                'representanteSeleccionado' =>  $representanteSeleccionado
                                                ]);
    }
    /**
     * Metodo diseñado para actualizar los datos de un actividad en la base de datos
     *
     * @param $id codigo de asociación de la actividad en la base de datos, $request datos enviados atravez del formulario
     *
     * @return $menssage retorna el resultado de la operación.
     */
    public function update($id)
    {
        $actividad = Proyecto::find($id);
        $actividad->nombre_actividad    = \Request::Input('nombreActividad');
        $actividad->status_actividad    = \Request::Input('statusActividad');
        $actividad->permiso_actividad   = \Request::Input('permisoActividad');
        $actividad->id_sector_ac        = \Request::Input('sectorActividad');
        $actividad->save();

        $representante = \Request::Input('representante');
        $actividad->institucion()->sync($representante);
        return redirect('actividad')->with('message','Se ha modificado una Actividad con exito');
    }
    /**
     * Metodo diseñado para eliminar los datos de un actividad en la base de datos
     *
     * @param $id codigo de asociación del actividad en la base de datos
     *
     * @return $menssage retorna el resultado de la operación.
     */
    public function destroy($id)
    {
        $actividad = Proyecto::find($id);
        $actividad->institucion()->detach();
        $actividad->delete();
        return redirect('actividad')->with('message','Se ha eliminado una actividad con exito');
    }
}
