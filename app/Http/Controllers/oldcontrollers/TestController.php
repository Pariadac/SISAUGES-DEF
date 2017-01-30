<?php

namespace SISAUGES\Http\Controllers;


use Illuminate\Http\Request;

use SISAUGES\Http\Requests;
use SISAUGES\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;


/**
* 
*/
class TestController extends Controller
{
	
	function __construct()
	{}

	public function index()
    {
        return view('test.index');
    }

    public function renderform(Request $request){

		if ($request->typeform=='add') {
			$action="test/crear";
		}elseif($request->typeform=='modify'){
			$action="test/modificar";
		}

		$fields=array(

			'nombre' => array(
				'type'	=> 'text',
				'value'	=> '',
				'id'	=> 'nombre',
				'label'	=> 'Campo',
				'placeholder'	=>	'',
				'validaciones'=>array(

                	'solonumero',
                	'obligatorio'

                )
			),
			'apellido' => array(
				'type'	=> 'text',
				'value'	=> '',
				'id'	=> 'apellido',
				'label'	=> 'Campo',
				'placeholder'	=>	''
			),
			'cedula' => array(
				'type'	=> 'text',
				'value'	=> '',
				'id'	=> 'cedula',
				'label'	=> 'Campo',
				'placeholder'	=>	''
			),
			'telefono' => array(
				'type'		=> 'select',
				'value'		=> '',
				'id'		=> 'telefono',
				'label'		=> 'Campo',
				'options'	=> array(
					'Codigo',
					'212'=>'212',
					'412'=>'412',
					'414'=>'414',
					'424'=>'424',
					'416'=>'415',
					'426'=>'426'
				),
				'placeholder'	=>	'',
				'extrafields'=>array(

					array('name'	=> 'telefono_complement','value'	=>	'', 'placeholder'	=>	'XXX-XXXX')

				),
			),
			'rol' => array(
				'type'		=> 'select',
				'value'		=> '',
				'id'		=> 'telefono',
				'label'		=> 'Campo',
				'options'	=> array(
					'Seleccione rol del usuario',
					'XXX'=>'XXX',
					'YYY'=>'YYY',
					'ZZZ'=>'ZZZ',
				),
				'placeholder'	=>	''
			),
			'email' => array(
				'type'	=> 'text',
				'value'	=> '',
				'id'	=> 'email',
				'label'	=> 'Campo',
				'placeholder'	=>	''
			),
			'username' => array(
				'type'	=> 'text',
				'value'	=> '',
				'id'	=> 'username',
				'label'	=> 'Campo',
				'placeholder'	=>	''
			),
			'password' => array(
				'type'	=> 'text',
				'value'	=> '',
				'id'	=> 'password',
				'label'	=> 'Campo',
				'placeholder'	=>	''
			),
		);

    	$htmlbody=View::make('layouts.regularform',compact('action','fields'))->render();

    	if ($htmlbody) {
    		$retorno=array(
    			'result'=>true,
    			'html'	=>$htmlbody
    		);
    	}else{
    		$retorno=array(
    			'result'=>false,
    		);
    	}

    	echo json_encode($retorno);

    }

    public function crear(Request $request){

    	//Validar Datos

    	$val=false;

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
}


?>