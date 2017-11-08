<?php

namespace SISAUGES\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use SISAUGES\Http\Requests;
use SISAUGES\Http\Controllers\Controller;

use SISAUGES\Http\Controllers\InstitucionController;

use SISAUGES\Models\Institucion;
use SISAUGES\Models\Departamento;
use SISAUGES\Models\Tutor;
use SISAUGES\Models\Estudiante;
use SISAUGES\Models\Muestra;
use SISAUGES\Models\Proyecto;
use SISAUGES\Models\Archivo;
use SISAUGES\Models\Persona;
use Storage;
use Validator;
use File;
use Imagick;
use mPDF;

use Illuminate\Support\Facades\View;

class ProyectoController extends Controller
{



    public function fieldsRegisterCall($proyecto,$instituciones=null,$departamentos=null,$tutores=null,$estudiantes=null,$muestras=null,$mestudiantes=null){

            
        $fields=array(

            'titulo1'=>array(
                'type'      => 'titulo',
                'value'     => 'Datos de la Institución'
            ),

            'institucion'=>array(

                'type'      => 'relacion',
                'value'     => (isset($instituciones[1]))? $instituciones[1]->id_institucion:'',
                'id'        => 'id_institucion',
                'label'     => 'Institución',
                'selecttype'=> 'obj',
                'values_seting'=> $instituciones[2],
                'objkeys'   => array('id_institucion','nombre_institucion'),
                'options'   => $instituciones[0],
                'selectadd' => array(
                    'btnadd'=>'Agregar Institución',
                    'btnlabel'=>'Registrar Institución',
                    'btnfinlavel'=>'Registrar Institución',
                    'url'=> url('institucion/registerform')
                ),
                'relation_table'=>array(
                    'title'=>'Instituciones Asociadas al Proyecto',
                    'table_fields'=>array(
                        'Nombre de la Institucion'
                    ),
                    'table_key'=>'nombre_institucion',
                    'table_obj'=>(isset($proyecto->institucion))? $proyecto->institucion()->get() :null,
                ),
                'relacion_campo'=>'id_institucion'

            ),
            'separador1'=>array('type'=>'separador'),
            /*'departamento'=>array(

                'type'      => 'select',
                'value'     => (isset($departamentos[1]))? $departamentos[1]->id_departamento:'',
                'id'        => 'id_departamento',
                'label'     => 'Departamento',
                'selecttype'=> 'obj',
                'objkeys'   => array('id_departamento','descripcion_departamento'),
                'options'   => $departamentos[0],
                'selectadd' => array(
                    'btnlabel'=>'Agregar Departamento',
                    'btnfinlavel'=>'Registrar Departamento',
                    'url'=> url('departamento/registerform')
                )

            ),
            'separador2'=>array('type'=>'separador'),

            'tutor'=>array(

                'type'      => 'select',
                'value'     => (isset($tutores[1]))? $tutores[1]->id_tutor:'',
                'id'        => 'id_tutor',
                'label'     => 'Tutor',
                'selecttype'=> 'obj',
                'objkeys'   => array('id_tutor','cedula_persona'),
                'options'   => $tutores[0],
                'selectadd' => array(
                    'btnlabel'=>'Agregar Tutor',
                    'btnfinlavel'=>'Registrar tutor',
                    'url'=> url('tutor/registerform')
                )

            ),
            'separador3'=>array('type'=>'separador'),*/

            'titulo3'=>array(
                'type'      => 'titulo',
                'value'     => 'Datos del Proyecto'
            ),

            'nombre_proyecto' => array(
                'type'  => 'text',
                'value' => (isset($proyecto->nombre_proyecto))? $proyecto->nombre_proyecto:'',
                'id'    => 'nombre_proyecto',
                'label' => 'Nombre del Proyecto'
            ),
            'estatus_proyecto' => array(
                'type'  => 'select',
                'value' => (isset($proyecto->estatus_proyecto))? $proyecto->estatus_proyecto:'',
                'id'    => 'estatus_proyecto',
                'label' => 'Estatus del Proyecto',
                'options'   => array(
                    ''=>'Seleccione...',
                    'No iniciado'=>'No iniciado',
                    'En progreso'=>'En progreso',
                    'Culminado'=>'Culminado'
                )
            ),
            'fecha_inicio' => array(
                'type'  => 'date',
                'value' => (isset($proyecto->fecha_inicio))? $proyecto->fecha_inicio:'',
                'id'    => 'fecha_inicio',
                'label' => 'Fecha de Recepción del proyecto'
            ),
            'fecha_final' => array(
                'type'  => 'date',
                'value' => (isset($proyecto->fecha_final))? $proyecto->fecha_final:'',
                'id'    => 'fecha_final',
                'label' => 'Fecha de Finalización del proyecto'
            ),
            'permiso_proyecto' => array(
                'type'      => 'select',
                'value'     => (isset($proyecto->permiso_proyecto))? $proyecto->permiso_proyecto:'',
                'id'        => 'permiso_proyecto',
                'label'     => 'Permiso',
                'options'   => array(
                    ''=>'Seleccione...',
                    'Publico'=>'Publico',
                    'Privado'=>'Privado'
                )
            ),
            'separador4'=>array('type'=>'separador')
        );

		if ($mestudiantes!=null) {
			
			$fields['titulo2']=array(
                'type'      => 'titulo',
                'value'     => 'Datos del Estudiante'
            );

            $fields['estudiante']=array(

                'type'      => 'relacion',
                'value'     => '',
                'id'        => 'id_estudiante',
                'label'     => 'Estudiante',
                'selecttype'=> 'obj',
                'objkeys'   => array('id_estudiante','cedula_persona'),
                'options'   => $proyecto->estudiante,
                'selectadd' => array(
                    'btnadd'=>'Agregar Estudiante',
                    'btnlabel'=>'Registrar Estudiante',
                    'btnfinlavel'=>'Registrar Estudiante',
                    'url'=> url('estudiante/registerform')
                ),
                'relation_table'=>array(
                    'title'=>'Estudiantes involucrados en el Proyecto',
                    'table_fields'=>array(
                        'Cedula del Estudiante'
                    ),
                    'table_key'=>'cedula_persona',
                    'table_obj'=>(isset($proyecto->estudiante))? $proyecto->estudiante()->get() :null,
                ),
                'relacion_campo'=>'id_estudiante'

            );

            $fields['separador5']=array('type'=>'separador');

		}


        if ($muestras!=null) {

            $fields['titulo4']=array(
                'type'      => 'titulo',
                'value'     => 'Muestras Asociadas'
            );

            $fields['muestras']=array(

                'type'      => 'relacion',
                'value'     => '',
                'id'        => 'id_muestra',
                'label'     => 'Muestras',
                'selecttype'=> 'obj',
                'objkeys'   => array('id_muestra','codigo_muestra'),
                'options'   => $muestras,
                'selectadd' => array(
                    'btnadd'=>'Agregar Muestras',
                    'btnlabel'=>'Registrar Muestras',
                    'btnfinlavel'=>'Registrar Muestras',
                    'url'=> url('muestra/registerform')
                ),
                'relation_table'=>array(
                    'title'=>'Muestras Asociadas al Proyecto',
                    'table_fields'=>array(
                        'Codigo de la Muestra'
                    ),
                    'table_key'=>'codigo_muestra',
                    'table_obj'=>(isset( $proyecto->muestras ))? $proyecto->muestras()->get() :null,
                ),
                'relacion_campo'=>'id_muestra',
                'editinclude'=> true

            );

        }
        

        return $fields;

    }

    public function fieldsSearchCall($request,$instituciones){
        $fields=array(

            'nombre_proyecto' => array(
                'type'  => 'text',
                'value' => (isset($request->nombre_proyecto))? $request->nombre_proyecto:'',
                'id'    => 'nombre_proyecto',
                'label' => 'Nombre del Proyecto'
            ),
            'estatus_proyecto' => array(
                'type'  => 'select',
                'value' => (isset($request->estatus_proyecto))? $request->estatus_proyecto:'',
                'id'    => 'estatus_proyecto',
                'label' => 'Estatus del Proyecto',
                'options'   => array(
                    ''=>'Seleccione...',
                    'No iniciado'=>'No iniciado',
                    'En progreso'=>'En progreso',
                    'Culminado'=>'Culminado'
                )
            ),
            'fecha_inicio' => array(
                'type'  => 'date',
                'value' => (isset($request->fecha_inicio))? $request->fecha_inicio:'',
                'id'    => 'fecha_inicio',
                'label' => 'Fecha de Recepción del proyecto'
            ),
            'fecha_final' => array(
                'type'  => 'date',
                'value' => (isset($request->fecha_final))? $request->fecha_final:'',
                'id'    => 'fecha_final',
                'label' => 'Fecha de Finalización del proyecto'
            ),

            'nombre_institucion'=>array(

                'type'      => 'select',
                'value'     => (isset($request->id_institucion))? $request->id_institucion:'',
                'id'        => 'id_institucion',
                'label'     => 'Institución',
                'selecttype'=> 'obj',
                'objkeys'   => array('nombre_institucion','nombre_institucion'),
                'options'   => $instituciones

            ),

            
        );


        if (Auth::user()->id_rol != 4) {

            $fields['permiso_proyecto'] = array(
                'type'      => 'select',
                'value'     => (isset($request->permiso_proyecto))? $request->permiso_proyecto:'',
                'id'        => 'permiso_proyecto',
                'label'     => 'Permiso',
                'options'   => array(
                    ''=>'Seleccione...',
                    'Publico'=>'Publico',
                    'Privado'=>'Privado'
                )
            );

        }

        return $fields;
    }


    public function fieldsReportCall($proyecto,$instituciones=null,$departamentos=null,$tutores=null,$estudiantes=null,$muestras=null,$mestudiantes=null){

            
        $fields=array(

            'titulo1'=>array(
                'type'      => 'titulo',
                'value'     => 'Datos de la Institución'
            ),

            'institucion'=>array(

                'type'      => 'report_relacion',
                'value'     => '',
                'id'        => 'id_institucion',
                'label'     => 'Institución',
                'selecttype'=> 'obj',
                'values_seting'=> $instituciones[2],
                'objkeys'   => array('id_institucion','nombre_institucion'),
                'options'   => $instituciones[0],
                'selectadd' => array(
                    'btnadd'=>'Agregar Institución',
                    'btnlabel'=>'Registrar Institución',
                    'btnfinlavel'=>'Registrar Institución',
                    'url'=> url('institucion/registerform')
                ),
                'relation_table'=>array(
                    'title'=>'Instituciones Asociadas al Proyecto',
                    'table_fields'=>array(
                        'Nombre de la Institucion'
                    ),
                    'table_key'=>'nombre_institucion',
                    'table_obj'=>(isset($proyecto->institucion))? $proyecto->institucion()->get() :null,
                ),
                'relacion_campo'=>'id_institucion'

            ),
            'separador1'=>array('type'=>'separador'),

        );

		if ($mestudiantes!=null) {
			
			$fields['titulo2']=array(
                'type'      => 'titulo',
                'value'     => 'Datos del Estudiante'
            );

            $fields['estudiante']=array(

                'type'      => 'report_relacion',
                'value'     => '',
                'id'        => 'id_estudiante',
                'label'     => 'Estudiante',
                'selecttype'=> 'obj',
                'objkeys'   => array('id_estudiante','cedula_persona'),
                'options'   => $estudiantes[0],
                'values_seting'=> $estudiantes[2],
                'selectadd' => array(
                    'btnadd'=>'Agregar Estudiante',
                    'btnlabel'=>'Registrar Estudiante',
                    'btnfinlavel'=>'Registrar Estudiante',
                    'url'=> url('estudiante/registerform')
                ),
                'relation_table'=>array(
                    'title'=>'Estudiantes involucrados en el Proyecto',
                    'table_fields'=>array(
                        'Cedula del Estudiante'
                    ),
                    'table_key'=>'cedula_persona',
                    'table_obj'=>(isset($proyecto->estudiante))? $proyecto->estudiante()->get() :null,
                ),
                'relacion_campo'=>'id_estudiante'

            );

            $fields['separador5']=array('type'=>'separador');

		}


        if ($muestras!=null) {

            $fields['titulo4']=array(
                'type'      => 'titulo',
                'value'     => 'Muestras Asociadas'
            );

            $fields['muestras']=array(

                'type'      => 'report_muestra',
                'value'     => '',
                'id'        => 'id_muestra',
                'label'     => 'Muestras',
                'selecttype'=> 'obj',
                'objkeys'   => array('id_muestra','codigo_muestra'),
                'options'   => $proyecto->muestras()->get(),
                'selectadd' => array(
                    'btnadd'=>'Agregar Muestras',
                    'btnlabel'=>'Registrar Muestras',
                    'btnfinlavel'=>'Registrar Muestras',
                    'url'=> url('muestra/registerform')
                ),
                'relation_table'=>array(
                    'title'=>'Muestras Asociadas al Proyecto',
                    'table_fields'=>array(
                        'Codigo de la Muestra'
                    ),
                    'table_key'=>'codigo_muestra',
                    'table_obj'=>(isset( $proyecto->muestras ))? $proyecto->muestras()->get() :null,
                ),
                'relacion_campo'=>'id_muestra',
                'editinclude'=> true

            );

        }
        

        return $fields;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Auth::user()->id_rol != 4) {

            $proyectos=Proyecto::nombreproyecto($request->nombre_proyecto)->
            permisoproyecto($request->permiso_proyecto)->
            fechainicioproyecto($request->fecha_inicio)->
            fechafinalproyecto($request->fecha_final)->
            whereHas('institucion', function($query) use ($request){

                    $query->nombreinstitucion($request->nombre_institucion);

            })->
            orderBy('id_proyecto', 'desc')->paginate(20);

        }else{

            $proyectos=Proyecto::nombreproyecto($request->nombre_proyecto)->
            permisoproyecto('Publico')->
            fechainicioproyecto($request->fecha_inicio)->
            fechafinalproyecto($request->fecha_final)->
            whereHas('institucion', function($query) use ($request){

                    $query->nombreinstitucion($request->nombre_institucion);

            })->
            orderBy('id_proyecto', 'desc')->paginate(20);

        }

        

        $action="proyecto/listar";

        $instituciones=Institucion::statusinstitucion(1)->get();

        $fields=$this->fieldsSearchCall($request,$instituciones);

        $data=array(

            'title'=>'Proyecto',
            'principal_search'=>'nombre_proyecto',
            'registros'=>$proyectos,
            'carpeta'=>'proyecto'

        );

        return view('layouts.index',compact('data','action','fields','request'));
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function renderForm(Request $request){

        
        if ($request->typeform=='add') {
            $action="proyecto/crear";
        }elseif($request->typeform=='modify'){
            $action="proyecto/editar/".$request->field_id;
        }elseif($request->typeform=='deleted'){
            $action="proyecto/eliminar/".$request->field_id;
        }elseif ($request->typeform=='report') {
        	$action="proyecto/reporte/".$request->field_id;
        }


        /*if (isset($request->nextproyectstep)) {
            $step=$request->nextproyectstep+1;
        }else{
            $step=1;
        }*/


        $proyecto = Proyecto::find($request->field_id);

        if ($request->typeform=='deleted') {
            $fields=false;
            $modulo='Proyecto';
            $hiddenfields=false;
        }else{

            $hiddenfields=array(

                'field_id'=>array(
                    'type'  => 'hidden',
                    'value' => $request->field_id,
                    'id'    => 'field_id',
                ),
                'extra_url'=>array(
                    'type'  => 'hidden',
                    'value' =>  url('proyecto/registerform'),
                    'id'    => 'extra_url',
                ),

            );


            $ins=Institucion::find($request->institucion);
            $dep=Departamento::find($request->departamento);
            $tut=Tutor::find($request->tutor);
            $est=Estudiante::find($request->estudiante);


            $inses=Institucion::statusinstitucion(1)->get();
            $depes=Departamento::statusdepartamento(1)->get();
            $tutes=Tutor::statustutor(1)->get();
            $estes=Estudiante::statusestudiante(1)->get();
            $muestras=Muestra::get();

            if ($request->typeform=='modify') {
                $fields=$this->fieldsRegisterCall($proyecto,array($inses,$ins,$request),array($depes,$dep),array($tutes,$tut),array($estes,$est,$request),$muestras,$estes);
            }elseif ($request->typeform=='report') {

            	$hiddenfields['report']=array(
                    'type'  => 'hidden',
                    'value' =>  url($action),
                    'id'    => 'report',
                );

            	$fields=$this->fieldsReportCall($proyecto,array($inses,$ins,$request),array($depes,$dep),array($tutes,$tut),array($estes,$est,$request),$muestras,$estes);
            }else{
                $fields=$this->fieldsRegisterCall($proyecto,array($inses,$ins,$request),array($depes,$dep),array($tutes,$tut),array($estes,$est,$request));
            }

            $modulo='Proyecto';
            
            
        }

        $htmlbody=View::make('layouts.regularform',compact('action','fields','hiddenfields','request','modulo'))->render();


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

        $proyecto=new Proyecto($request->all());

        $aux=$request->all();

        $validator=Validator::make($request->all(),[

            'nombre_proyecto'=>'required|min:1|max:255',
            'estatus_proyecto'=>'required|min:1|max:255',
            'fecha_inicio'=>'required|min:1|max:255',
            'fecha_final'=>'required|min:1|max:255',
            'permiso_proyecto'=>'required|min:1|max:255',
            'addeninid_institucion.*'=>'required'

        ]);


        if ($validator->passes()) {

            $val=$proyecto->save();


            foreach ($request->addeninid_institucion as $prokey => $provalue) {

                if (!$proyecto->institucion()->find($provalue)) {

                    $proyecto->institucion()->attach($provalue);

                    $proyecto->save();
                }

            }


            


        }else{
            $val=$validator->passes();
        }

        return array('result'=>$val,'obj'=>$proyecto->id_proyecto,'keystone'=>'id_proyecto');

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update($request, $id){

        $proyecto=Proyecto::find($id);

        $aux=$request->all();

        $validator=Validator::make($request->all(),[

            'nombre_proyecto'=>'required|min:1|max:255',
            'estatus_proyecto'=>'required|min:1|max:255',
            'fecha_inicio'=>'required|min:1|max:255',
            'fecha_final'=>'required|min:1|max:255',
            'permiso_proyecto'=>'required|min:1|max:255',
            'addeninid_institucion.*'=>'required'

        ]);


        if ($validator->passes()) {


            $proyecto->nombre_proyecto=$request->nombre_proyecto;
            $proyecto->estatus_proyecto=$request->estatus_proyecto;
            $proyecto->fecha_inicio=$request->fecha_inicio;
            $proyecto->fecha_final=$request->fecha_final;
            $proyecto->permiso_proyecto=$request->permiso_proyecto;

            $val=$proyecto->save();

            if (isset($request->deleteinid_institucion)) {
                    
                foreach ($request->deleteinid_institucion as $prokey => $provalue) {

                    if ($muestra->proyecto()->find($provalue)) {

                        $muestra->proyecto()->detach($provalue);
                    }

                }
            }

            if (isset($request->addeninid_institucion)) {
                    
                foreach ($request->addeninid_muestra as $prokey => $provalue) {

                    if (!$proyecto->muestras()->find($provalue)) {

                        $proyecto->muestras()->attach($provalue);

                        $proyecto->save();
                    }

                }
            }


            foreach ($request->addeninid_institucion as $prokey => $provalue) {

                if (!$proyecto->institucion()->find($provalue)) {

                    $proyecto->institucion()->attach($provalue);

                    $proyecto->save();
                }

            }


            


        }else{
            $val=$validator->passes();
        }

        return array('result'=>$val,'obj'=>$proyecto->id_proyecto,'keystone'=>'id_proyecto');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($request, $id){

        $proyecto=Proyecto::find($id);

        $proyecto->estatus_proyecto='Culminado';

        $val=$proyecto->save();

        return array('result'=>$val,'obj'=>$proyecto->id_proyecto,'keystone'=>'id_proyecto');

    }


    //Funciones Extra

    public function obtenerConteoProyectosxMes(){

        return Proyecto::whereMonth('fecha_inicio','=',date('n'))->whereYear('fecha_inicio','=',date('Y'))->count();

    }


    public function reportProyect(Request $request, $id){


    	$proyecto=Proyecto::find($id);
    	$institucion=Institucion::find($request->institucion);
    	$estudiante=Estudiante::find($request->estudiante);
        if (isset($estudiante)) {
            $persona = Persona::buscarpersona($estudiante->cedula_persona)->first();
        }else{
            $persona=null;
        }
    	

    	$mstr=Archivo::first();

    	$htmlBody = View::make('pdf.report',compact('request','proyecto','institucion','estudiante','persona','mstr'))->render();

        $pdf=new mPDF();

        $pdf->WriteHTML($htmlBody);

        $pdf->Output();


    }


    public function ajaxRegularStore(Request $request){

        $val=$this->store($request);

        $retorno=array();

        if ($val['result']) {
            //Datos Validos
            $retorno['resultado']='success';
            $retorno['mensaje']='El registro de los datos fue exitoso...';
            $retorno['obj']=$val['obj'];
            $retorno['keystone']=$val['keystone'];

        }else{
            //Datos Invalidos
            $retorno['resultado']='danger';
            $retorno['mensaje']='Los datos suministrados no son validos';

        }

        echo json_encode($retorno);

    }

    public function ajaxRegularUpdate(Request $request, $id){

        $val=$this->update($request,$id);

        $retorno=array();

        if ($val['result']) {
            //Datos Validos
            $retorno['resultado']='success';
            $retorno['mensaje']='Actualizacion de registro de los datos fue exitoso...';
            $retorno['obj']=$val['obj'];
            $retorno['keystone']=$val['keystone'];

        }else{
            //Datos Invalidos
            $retorno['resultado']='danger';
            $retorno['mensaje']='Los datos suministrados no son validos';

        }

        echo json_encode($retorno);

    }

    public function ajaxRegularDestroy(Request $request,$id){

        $val=$this->destroy($request,$id);

        $retorno=array();

        if ($val['result']) {
            //Datos Validos
            $retorno['resultado']='success';
            $retorno['mensaje']='Desactivo fue exitoso...';
            $retorno['obj']=$val['obj'];
            $retorno['keystone']=$val['keystone'];

        }else{
            //Datos Invalidos
            $retorno['resultado']='danger';
            $retorno['mensaje']='Los datos suministrados no son validos.';

        }

        echo json_encode($retorno);

    }


}