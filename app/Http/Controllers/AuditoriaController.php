<?php

namespace SISAUGES\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use SISAUGES\Http\Requests;
use SISAUGES\Http\Controllers\Controller;

use SISAUGES\Models\Auditoria;
use SISAUGES\Models\User;

use Illuminate\Support\Facades\View;

class AuditoriaController extends Controller
{



    public function fieldsRegisterCall($request){

        $fields=array(

            'evento' => array(
                'type'  => 'label',
                'value' => (isset($request->event))? $request->event:'',
                'id'    => 'evento',
                'label' => 'Evento de la auditoria',
                'valshow'=>(isset($request->event))? $request->event:'',
                'valkey'=>'evento'
            ),
            'modulo' => array(
                'type'  => 'label',
                'value' => (isset($request->auditable_type))? $request->auditable_type:'',
                'id'    => 'modulo',
                'label' => 'Módulo',
                'valshow'=>(isset($request->auditable_type))? $request->auditable_type:'',
                'valkey'=>'modulo'
            ),
            'oldvalues' => array(
                'type'  => 'label',
                'value' => (isset($request->old_values))? $request->old_values:'',
                'id'    => 'oldvalues',
                'label' => 'Antiguos Valores',
                'valshow'=>(isset($request->old_values))? $request->old_values:'',
                'valkey'=>'oldvalues'
            ),
            'newvalues' => array(
                'type'  => 'label',
                'value' => (isset($request->new_values))? $request->new_values:'',
                'id'    => 'newValues',
                'label' => 'Nuevos Valores',
                'valshow'=>(isset($request->new_values))? $request->new_values:'',
                'valkey'=>'newvalues'
            ),

            'date' => array(
                'type'  => 'label',
                'value' => (isset($request->created_at))? $request->created_at:'',
                'id'    => 'date',
                'label' => 'Fecha',
                'valshow'=>(isset($request->created_at))? $request->created_at:'',
                'valkey'=>'date'
            )
        );

        return $fields;
    }

    public function fieldsSearchCall($request){

        $fields=array(

            'evento' => array(
                'type'  => 'text',
                'value' => (isset($request->evento))? $request->evento:'',
                'id'    => 'evento',
                'label' => 'Evento de la auditoria'
            ),
            'modulo' => array(
                'type'  => 'text',
                'value' => (isset($request->modulo))? $request->modulo:'',
                'id'    => 'modulo',
                'label' => 'Módulo'
            )
        );

        return $fields;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $auditoria=Auditoria::eventoAuditoria($request->evento)
                                                    ->join('usuario','usuario.id_usuario','=','audits.user_id')
                                                    ->select('audits.*','usuario.username')
                                                    ->moduloAuditoria($request->modulo)
                                                    ->oldValuesAuditoria($request->oldValues)
                                                    ->newValuesAuditoria($request->newValues)
                                                    ->orderBy('id','asc')->paginate(20);


        $action="auditoria/listar";

        $fields=$this->fieldsSearchCall($request);

        $data=array(

            'title'=>'Auditoria',
            'principal_search'=>'evento',
            'registros'=>$auditoria,
            'carpeta'=>'auditoria'

        );

        return view('layouts.indexAut',compact('data','action','fields','request'));

    }



    public function renderForm(Request $request)
    {
        $action = "usuario/listar/";

        $registro = Auditoria::find($request->field_id);
        

        $hiddenfields = array(
            'field_id'=>array(
                'type'  => 'hidden',
                'value' => $request->field_id,
                'id'    => 'field_id',
            )
        );

        $fields = $this->fieldsRegisterCall($registro);

        $modulo='Auditoria';

        $htmlBody = View::make('layouts.regularform',compact('action','fields','hiddenfields','request','modulo'))->render();

        if ($htmlBody)
        {
            $retorno = array(
                'result'    => true,
                'html'      => $htmlBody
            );
        }
        else
        {
            $retorno = array('result'   => false);
        }

        echo json_encode($retorno);
    }


}
