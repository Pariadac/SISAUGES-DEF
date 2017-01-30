<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstitucionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $instituciones=Institucion::orderBy('nombre_institucion', 'desc')->paginate(20);

        return view('institucion.index',compact('instituciones'));
        
    }

    public function renderForm(Request $request){

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
                    'validaciones'=>array(

                        'solonumero',
                        'solocaracteres',
                        'solocorreo',
                        'obligatorio'

                    ),
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store($request){

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

        return $val;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update($request, $id){

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

        return $val;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, $id){

        $institucion=Institucion::find($id);

        $val=$institucion->delete();

        return $val;

    }



    public function search(Request $request){

        

    }


    public function ajaxRegularStore(Request $request){

        $val=$this->store($request);

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

    public function ajaxRegularUpdate(Request $request, $id){

        $val=$this->update($request,$id);

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

    public function ajaxRegularDestroy(Request $request){

        $val=$this->destroy($request);

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

    public function ajaxRegularSearch(Request $request){


    }



}
