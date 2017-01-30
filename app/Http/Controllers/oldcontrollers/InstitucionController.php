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
use SISAUGES\Http\Requests;
use SISAUGES\Http\Controllers\Controller;
use SISAUGES\Institucion;
use SISAUGES\Representante;

use Illuminate\Support\Facades\View;
/**
 * Class UserController
 *
 * Esta clase se diseño para manejar las transancciones de la institución en la base de
 * datos, estas pueden ser agregar,
 * modificar, eliminar o listar.
 *
 * @author Ely Colmenarez ElyJColmenarez@Gmail.com
 * @copyright 2016 Ely Colmenarez
 * @package SISAUGES\Http\Controllers
 */
class InstitucionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Metodo diseñado para direccionar a la pantalla principal del modulo
     *
     * Este metodo redirige a la pantalla principal del modulo institución
     * esta pantalla mostrara un listado de la institución agregada y un boton para modificar
     * y eliminar
     *
     * @param void
     *
     * @return $institución devuelve objeto de tipo institución
     */
    public function index()
    {

        $instituciones=Institucion::orderBy('nombre_institucion', 'desc')->paginate(20);

        return view('institucion.index',compact('instituciones'));
        
    }


    public function renderform(Request $request){

        if ($request->typeform=='add') {
            $action="institucion/crear";
        }elseif($request->typeform=='modify'){
            $action="institucion/modificar/".$request->field_id;
        }elseif($request->typeform=='deleted'){
            $action="institucion/eliminar/".$request->field_id;
        }

        $institucion = Institucion::find($request->field_id);

        if ($request->typeform=='deleted') {
            $fields=false;
        }else{


            $fields=array(

                'field_id'=>array(
                    'type'  => 'hidden',
                    'value' => $request->field_id,
                    'id'    => 'field_id',
                ),
                'nombre_institucion' => array(
                    'type'  => 'text',
                    'value' => (empty($institucion))? '' : $institucion->nombre_institucion,
                    'id'    => 'nombre_institucion',
                    'label' => 'Nombre de la institucion'
                ),
                'correo_institucional' => array(
                    'type'  => 'text',
                    'value' => (empty($institucion))? '' : $institucion->correo_institucional,
                    'id'    => 'correo_institucional',
                    'label' => 'Correo de la institucion'
                ),
                'direccion_institucion' => array(
                    'type'  => 'text',
                    'value' => (empty($institucion))? '' : $institucion->direccion_institucion,
                    'id'    => 'direccion_institucion',
                    'label' => 'Direccion de la institucion'
                ),
                'telefono_institucion' => array(
                    'type'  => 'text',
                    'value' => (empty($institucion))? '' : $institucion->telefono_institucion,
                    'id'    => 'telefono_institucion',
                    'label' => 'Telefono de la institucion'
                ),
                'status' => array(
                    'type'      => 'select',
                    'value'     => (empty($institucion))? '' : $institucion->status,
                    'id'        => 'status',
                    'label'     => 'Status',
                    'options'   => array(
                        'Seleccione...',
                        'true'=>'Activo',
                        'false'=>'Inactivo'
                    )
                )
            );


        }

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
    
    public function crear(Request $request){

        $institucion=new Institucion($request->all());

        $aux=$request->all();

        $cont=0;

        foreach ($aux as $key => $value) {
            
            $value=trim($value);

            if ($value=='') {
                $cont++;
            }
        }

        if ($cont>0) {
            $val=false;
        }else{
            $val=$institucion->save();
        }

        $retorno=array();

        if ($val) {
            //Datos Validos
            $retorno['resultado']='success';
            $retorno['mensaje']='El registro de los datos fue exitoso...';

        }else{
            //Datos Invalidos
            $retorno['resultado']='danger';
            $retorno['mensaje']='Los datos no suministrados no son validos';

        }

        echo json_encode($retorno);

    }


    public function modificar(Request $request, $id){

        $institucion=Institucion::find($id);


        $aux=$request->all();

        $cont=0;

        foreach ($aux as $key => $value) {
            
            $value=trim($value);

            if ($value=='' && $key!='_token') {
                $cont++;
            }
        }

        if ($cont>0) {
            $val=false;
        }else{

            $institucion->nombre_institucion=$request->nombre_institucion;
            $institucion->direccion_institucion=$request->direccion_institucion;
            $institucion->correo_institucional=$request->correo_institucional;
            $institucion->telefono_institucion=$request->telefono_institucion;
            $institucion->status=$request->status;

            $val=$institucion->save();
        }

        $retorno=array();

        if ($val) {
            //Datos Validos
            $retorno['resultado']='success';
            $retorno['mensaje']='El registro de los datos fue exitoso...';

        }else{
            //Datos Invalidos
            $retorno['resultado']='danger';
            $retorno['mensaje']='Los datos no suministrados no son validos';

        }

        echo json_encode($retorno);

    }


    public function eliminar(Request $request, $id){

        $institucion=Institucion::find($id);

        $val=$institucion->delete();

        $retorno=array();

        if ($val) {
            //Datos Validos
            $retorno['resultado']='success';
            $retorno['mensaje']='El registro de los datos fue exitoso...';

        }else{
            //Datos Invalidos
            $retorno['resultado']='danger';
            $retorno['mensaje']='Los datos no suministrados no son validos';

        }

        echo json_encode($retorno);

    }


    public function buscar(Request $request){

        $retorno=array('data'=>Institucion::orderBy('nombre_institucion', 'desc'));

        echo json_encode($retorno);

    }



}
