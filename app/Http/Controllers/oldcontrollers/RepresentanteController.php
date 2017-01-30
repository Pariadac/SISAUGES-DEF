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

use Mockery\Exception;
use SISAUGES\Departamento;
use SISAUGES\Http\Requests;
use SISAUGES\Http\Controllers\Controller;
use SISAUGES\Institucion;
use SISAUGES\Representante;

use Illuminate\Support\Facades\View;

/**
 * Class RepresentanteController
 *
 * Esta clase se diseño para manejar las transancciones del representante en la base de
 * datos, estas pueden ser agregar,
 * modificar, eliminar o listar.
 *
 * @author Ely Colmenarez ElyJColmenarez@Gmail.com
 * @copyright 2016 Ely Colmenarez
 * @package SISAUGES\Http\Controllers
 */
class RepresentanteController extends Controller
{
    protected $institucion;
    protected $departamento;
    protected $representante;

    public function __construct()
    {
        $this->middleware('auth');
        $this->institucion = Institucion::all()->pluck('nombre_institucion','id_institucion');
        $this->departamento = Departamento::all()->pluck('descripcion_departamento','id_departamento');
        $this->representante = Representante::all();
    }

    /**
     * Metodo diseñado para direccionar a la pantalla principal del modulo
     *
     * Este metodo redirige a la pantalla principal del modulo Representante
     * esta pantalla mostrara un listado de los representantes agregados y un boton para modificar
     * y eliminar
     *
     * @param void
     *
     * @return $representante devuelve objeto de tipo representante
     */
    public function index()
    {
        return view('representante.index')->with(['representante'   =>  $this->representante]);
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
     * Metodo diseñado para direccionar a la pantalla de agregar un Representante
     *
     * Este metodo redirige a la pantalla agregar Representante
     * la cual mostrara un formulario con los campos necesarios para almacenar
     * en la base de datos
     *
     * @param void
     *
     * @return $representante devuelve objeto de tipo representante
     */
    public function create()
    {
        return view('representante.crear')->with(['departamento'=>$this->departamento,
                                                  'institucion'=>$this->institucion]);
    }
    /**
     * Metodo diseñado para almacenar el representantes en la base de datos
     *
     * @param void
     *
     * @return $menssage retorna el resultado de la operación
     */
    public function store()
    {
        $representante = new Representante();
        $representante->cedula = \Request::Input('cedula');
        $representante->nombre = \Request::Input('nombre');
        $representante->apellido = \Request::Input('apellido');
        $representante->email = \Request::Input('email');
        $representante->telefono = \Request::Input('telefono');
        $representante->save();

        $institucion = \Request::Input('institucion');
        $departamento = \Request::Input('departamento');
        foreach($institucion as $ins)
        {
            foreach($departamento as $dep)
            {
                $representante->institucion()->attach($ins, ['id_departamento'=>$dep]);
            }
        }

        return redirect('representante')->with('message', 'Se ha agregado el representante con exito');
    }
    /**
     * Metodo diseñado para direccionar a la pantalla de editar un Representante
     *
     * Este metodo redirige a la pantalla editar Representante
     * la cual mostrara un formulario con los datos del representate seleccionado,
     * con los campos necesarios para almacenar en la base de datos
     *
     * @param $id codigo de asociación del representante en la base de datos
     *
     * @return $array un arreglo de objetos de los datos del representante
     */
    public function edit($id)
    {
        $representante = Representante::find($id);
        $representanteInstitucion = Representante::find($id)->institucion()->pluck('institucion_departamento_representante.id_institucion')
                                    ->toArray();
        $representanteDepartamento = Representante::find($id)->institucion()->pluck('institucion_departamento_representante.id_departamento')
                                    ->toArray();
        return view('representante.editar')->with(['representante'              =>  $representante,
                                                    'institucion'               =>  $this->institucion,
                                                    'departamento'              =>  $this->departamento,
                                                    'representanteInstitucion'  =>  $representanteInstitucion,
                                                    'representanteDepartamento' =>  $representanteDepartamento]);
    }
    /**
     * Metodo diseñado para actualizar los datos de un representante en la base de datos
     *
     * @param $id codigo de asociación del representante en la base de datos, $request datos enviados atravez del formulario
     *
     * @return $menssage retorna el resultado de la operación.
     */
    public function update($id)
    {
        $representante = Representante::find($id);
        $representante->cedula=\Request::Input('cedula');
        $representante->nombre=\Request::Input('nombre');
        $representante->apellido=\Request::Input('apellido');
        $representante->email=\Request::Input('email');
        $representante->telefono=\Request::Input('telefono');
        $representante->save();
        $representante->institucion()->detach();
        $institucion = \Request::Input('institucion');
        $departamento = \Request::Input('departamento');
        foreach($institucion as $ins)
        {
            foreach($departamento as $dep)
            {

                $representante->institucion()->attach($ins, ['id_departamento'=>$dep]);
            }
        }
        return redirect('representante')->with('message','El representante N°'.$id.' ha sido editado');
    }
    /**
     * Metodo diseñado para eliminar los datos de un representante en la base de datos
     *
     * @param $id codigo de asociación del representante en la base de datos
     *
     * @return $menssage retorna el resultado de la operación.
     */
    public function destroy($id)
    {
        $representante = Representante::find($id);
        $representante->institucion()->detach();
        $representante->actividades()->detach();
        $representante->delete();
        return redirect('representante')->with('message','El representante '.$id.' ha sido eliminado');
    }
}