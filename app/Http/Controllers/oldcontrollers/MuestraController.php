<?php 
/**
 * Copyright (c) 2016 Edward Perez
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
use Collective\Html\HtmlServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Validation\ValidationException;

use Storage;

use Imagick;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use SISAUGES\Http\Controllers\Controller;
use SISAUGES\Muestra;
use SISAUGES\Actividad;
use SISAUGES\TecnicaEstudio;
use SISAUGES\Representante;
use SISAUGES\Institucion;
use Illuminate\Pagination\LengthAwarePaginator;
use PDF;



/**
 * Class MuestraController
 *
 * Esta clase se diseño para manejar las transancciones de las Muestras en la base de
 * datos, estas pueden ser agregar,
 * modificar, eliminar o listar.
 *
 * @author Edward Perez zero_zerouno@hotmail.com
 * @copyright 2016 Edward Perez
 * @package SISAUGES\Http\Controllers
 */


class MuestraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Metodo para obtener las relaciones de una muestra a partir de esta
     *
     *
     * @param id_muestra
     *
     * @return $aux retorna la actividades relacionadas a una muestra
     */


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


    public function obtener_actividad($valor){

    	$act=DB::table('muestra_actividad')->where('id_muestra','=',$valor)->get();

    	$aux=array();

        foreach ($act as $key2) {
        	$aux1=DB::table('actividad')->where('id_actividad','=',$key2->id_actividad)->get();
        	$aux=array_merge($aux,$aux1);
        }

        return $aux;

    }


    /**
     * Metodo para obtener las relaciones de una muestra a partir de esta
     *
     *
     * @param id_muestra
     *
     * @return $aux retorna la institución relacionada a una muestra
     */

    public function obtener_institucion($valor){

    	$inst=DB::table('muestra_actividad')->where('id_muestra','=',$valor)->get();
    	$aux=array();

        foreach ($inst as $llave1) {
        
        	$rep=DB::table('representante_actividad')->where('id_actividad','=',$llave1->id_actividad)->get();

        	foreach ($rep as $llave2) {

        		$act=DB::table('institucion_departamento_representante')->where('id_representante','=',$llave2->id_representante)->get();

        		foreach ($act as $llave3) {
        			
        			$aux1=DB::table('institucion')->where('id_institucion','=',$llave3->id_institucion)->get();

        			$aux=array_merge($aux,$aux1);

        		}
        	}

        }

        return $aux; 

    }


    public function obtener_representante($valor){

        $inst=DB::table('muestra_actividad')->where('id_muestra','=',$valor)->get();
        $aux=array();

        foreach ($inst as $llave1) {
        
            $rep=DB::table('representante_actividad')->where('id_actividad','=',$llave1->id_actividad)->get();

            foreach ($rep as $llave2) {

                $act=DB::table('representante')->where('id_representante','=',$llave2->id_representante)->get();

                $aux=array_merge($aux,$act);
            }

        }

        return $aux; 

    }

    /**
     * Metodo para obtener las relaciones de una muestra a partir de esta
     *
     *
     * @param id_muestra
     *
     * @return $aux retorna la tecnica relacionada a una muestra
     */

    public function obtener_tecnica($valor){

    	$act=DB::table('muestra_tecnica_estudio')->where('id_muestra','=',$valor)->get();

    	$aux=array();

        foreach ($act as $key2) {
        	$aux1=DB::table('tecnica_estudio')->where('id_tecnica_estudio','=',$key2->id_tecnica_estudio)->get();
        	$aux=array_merge($aux,$aux1);
        }

        return $aux;

    }




    /**
     * Metodo para generar una imagen compatible con el navegador
     *
     *
     * @param string,integer
     *
     * @return retorna la ruta de una imagen auxiliar creada en el servidor
     */


    public function generar_imagen_visible($original_paht,$id){


        $ruta=$_SERVER['DOCUMENT_ROOT']."/storage/";

        $image = new Imagick($original_paht);

        $fecha=date("d_m_Y_H_i_s");

        $ruta=$ruta.$id.'aux-'.$fecha.".jpg";

        $image->setImageFormat('jpg');

        $image->writeImage($ruta);

        return $id.'aux-'.$fecha.'.jpg';

    }


    /**
     * Metodo para retornar a la pagina principal de Muestras
     *
     *
     * @param void
     *
     * @return redirige a la vista principal del Modulo Muestras
     */


    public function index()
    {

    	$datos=DB::table('muestra')->orderBy('id_muestra','desc')->get();


    	if ($datos) {
            $public_path = public_path();

            $url = $public_path.'/storage/'.$datos[0]->ruta_img_muestra;

            $newimg=$this->generar_imagen_visible($url,1);

            $url = $public_path.'/storage/'.$newimg;

            //verificamos si el archivo existe y lo retornamos

            if (Storage::exists($newimg))
            {

                $datos[0]->ruta_img_muestra=$newimg;  
            }
        }


        return view('muestra.index',compact('datos'));
    }


    public function eliminarimg_bdd(){

        $rutas=DB::table('auximg')->get();

        foreach ($rutas as $key) {

          if (strlen($key->description_img)>0) {
            
                $valores=explode('/', $key->description_img);
                $valores2=explode('/', $key->orgpage_img);

                $busqueda=DB::table('muestra')->where('ruta_img_muestra','=',$valores2[count($valores2)-1])->get();

                if (count($busqueda)==0) {
                    
                    if (Storage::exists($valores2[count($valores2)-1]))
                    {

                        $retorno=Storage::delete($valores2[count($valores2)-1]);

                        DB::table('auximg')->where('auxid', '=', $key->auxid)->delete();

                    }

                }

                if (Storage::exists($valores[count($valores)-1]))
                {

                    $retorno=Storage::delete($valores[count($valores)-1]);

                    DB::table('auximg')->where('auxid', '=', $key->auxid)->delete();

                }

            }  

        }


    }


    public function eliminarsingle(){

        $request=Input::all();

        $rutas=DB::table('auximg')->where('orgpage_img','=',$request['file'])->get();
        var_dump($rutas);

        if ($rutas) {

            if (Storage::exists($rutas[0]->orgpage_img))
            {
                $retorno=Storage::delete($rutas[0]->orgpage_img);
            }
            if (Storage::exists($rutas[0]->description_img))
            {
                $retorno=Storage::delete($rutas[0]->description_img);
            }

            DB::table('auximg')->where('auxid', '=', $rutas[0]->auxid)->delete();

        }

        return response()->json([
            'respuesta'=>'true'
        ]);


    }


    /**
     * Metodo que retorna a la pagina de agregar muestra
     *
     *
     * @param void
     *
     * @return redirige a la vista agregar del Modulo Muestras
     */


    public function create()
    {
        $this->eliminarimg_bdd();

        $valor=DB::table('muestra')->max('id_muestra');
        if (!$valor) {$valor=1;}else{$valor++;}

        $valor.=date("d_m_Y_H_i_s");

        $actividad=Actividad::all();
        $tecnica=TecnicaEstudio::all();

        return view('muestra.crear',compact('actividad','tecnica','valor'));
    }


    /**
     * Metodo que retorna una imagen visible a la vista de agregar muestra
     *
     *
     * @param void
     *
     * @return retorna una imagen visible a la vista de agregar muestra en formato json
     */

    public function ajaxvalidar(){


    	$data=Input::all();

        $file=$data['filebutton'];

        $fecha=date("d_m_Y_H_i_s");

        $nombre='imagen-'.($data['posi']+1)."-".$fecha.".".$file->getClientOriginalExtension();

        $ruta_n=$this->generar_imagen_visible($data['filebutton']->getRealPath(),$data['posi']);
             
        \Storage::disk('local')->put($nombre,  \File::get($file));

        DB::table('auximg')->insert(['description_img'=>$ruta_n,'orgpage_img'=>$nombre]);

 
       return response()->json([
            'ruta'=>'/storage/'.$ruta_n,
            'rutaori'=>$nombre,
            'rutatrue'=>$file->getClientOriginalName(),
            'pos'=>$data['posi']
       	]);


    }



    /**
     * Metodo que elimina las imagenes auxiliares compatibles con el navegador
     *
     *
     * @param void
     *
     * @return elimina las imagenes auxiliares del servidor
     */


    public function borrar_img(){

        $data=Input::all();

        $retorno=0;

        $rutas=explode(';',$data['rutamuestra']);

        var_dump($rutas);

        foreach ($rutas as $key) {

          if (strlen($key)>0) {
            
                $valores=explode('/', $key);

                if (Storage::exists($valores[count($valores)-1]))
                {

                    $retorno=Storage::delete($valores[count($valores)-1]);

                }

            }  

        }



    }

    //Funcion que almacena la informacion en la base de datos, y la imagen en el servidor


    /**
     * Metodo que almacena la informacion en la base de datos, y la imagen en el servidor
     *
     *
     * @param void
     *
     * @return almacena la informacion del formulario del modulo muestras en la base de datos.
     */



    public function store()
    {
        $data=Input::all();

        if (DB::table('muestra')->where('codigo_muestra','=',$data['truecodmues'])->count()==0):


            $valor_def=explode(';', $data['truerutas']);
            $valor_ori=explode(';', $data['trueorigis']);


            foreach ($valor_def as $key => $value) {
                

                if (strlen($value)>1):


                    if (Storage::exists($value)){


                        $muestra=new Muestra();

                        $muestra->codigo_muestra=$data['truecodmues'];
                        $muestra->nombre_original_muestra=$valor_ori[$key];
                        $muestra->ruta_img_muestra=$value;
                        $muestra->descripcion_muestra=$data['truedescri'];
                        $muestra->fecha_recepcion=$data['truefecharep'];
                        $muestra->fecha_analisis=$data['truefechaana'];

                        if ($muestra->save()) {

                            DB::table('muestra_actividad')->insert(['id_actividad'=>$data['trueactividad'],'id_muestra'=>$muestra->id_muestra]);

                            DB::table('muestra_tecnica_estudio')->insert(['id_tecnica_estudio'=>$data['truetecnic'],'id_muestra'=>$muestra->id_muestra]);

                        }

                    }


                endif;

            }


        endif;

        $this->eliminarimg_bdd();


    	$retorno=0;
        $valor=DB::table('muestra')->max('id_muestra');
        if (!$valor) {$valor=1;}else{$valor++;}
        $valor.=date("d_m_Y_H_i_s");
        $actividad=Actividad::all();
        $tecnica=TecnicaEstudio::all();

        return view('muestra.crear',compact('retorno','actividad','tecnica','valor'));

    }


     /**
     * Metodo que retorna a la Vista listar de Muestras
     *
     *
     * @param void
     *
     * @return retorna a la Vista listar de Muestras
     */




    public function listar(){

        $this->eliminarimg_bdd();

    	$totaldatos=DB::table('muestra')->get();

    	$page = Input::get('page', 1); 

        $perPage = 20;

        $offSet = ($page * $perPage) - $perPage;

        $itemsForCurrentPage = array_slice($totaldatos, $offSet, $perPage, true);


        $valores=array();

        $retorno=array();

    	$datos=$itemsForCurrentPage;

        $public_path = public_path();

        foreach ($datos as $key => $value) {


            if (Storage::exists($value->ruta_img_muestra))
            {

                $datos[$key]->ruta_img_muestra=$this->generar_imagen_visible($public_path.'/storage/'.$value->ruta_img_muestra,$key);

                DB::table('auximg')->insert(['description_img'=>$datos[$key]->ruta_img_muestra,'orgpage_img'=>$value->ruta_img_muestra]);
            }

            $valores['muestra-d']=$datos[$key];
            $valores['actividad-d']=$this->obtener_actividad($value->id_muestra);
            $valores['institucion-d']=$this->obtener_institucion($value->id_muestra);
            $valores['tecnica-d']=$this->obtener_tecnica($value->id_muestra);
            
            $retorno[]=$valores;

        }

        $itemsForCurrentPage=$retorno;


        $tecnica=TecnicaEstudio::all();
        $actividad=Actividad::all();
        $institucion=Institucion::all();


        $paginador=new LengthAwarePaginator($itemsForCurrentPage, count($totaldatos), $perPage, $page, ['path' => 'lista']);



    	return view('muestra.lista',compact('paginador','tecnica','itemsForCurrentPage','actividad','institucion'));
    }


    /**
     * Metodo que busca las relaciones de muestra usando el nombre principal de dichas relaciones como parametro
     *
     *
     * @param $valor string,$posi integer;
     *
     * @return $resultados retorna un objeto del tipo de dato solicitado.
     */

    public function resultadosbdd($valor,$posi){

        $tablas=array('actividad','tecnica_estudio','institucion');
        $campo2=array('nombre_actividad','descripcion_tecnica_estudio','nombre_institucion');

        $resultados=DB::table($tablas[$posi])->where($campo2[$posi],'ILIKE','%'.$valor.'%')->get();

        return $resultados;

    }


    /**
     * Metodo ajax utilizada para retornar una lista de las relaciones de muestra
     *
     *
     * @param void;
     *
     * @return $var retorna una cadena de valores conpatibles con listas html en formato json
     */


    public function buscarbdd(){

        $datos=Input::all();

        $campo1=array('id_actividad','id_tecnica_estudio','id_institucion');
        $campo2=array('nombre_actividad','descripcion_tecnica_estudio','nombre_institucion');

        $resultados=$this->resultadosbdd($datos['valor'],$datos['bus']-1);

        $datosdb="";

        if ($resultados) {
            foreach ($resultados as $key) {
                $datosdb.='<li data-ids="'.$datos['bus'].'" data-value="'.$key->$campo1[$datos['bus']-1].'" data-valortx="'.$key->$campo2[$datos['bus']-1].'" > <p class="miminibuscador" >'.$key->$campo2[$datos['bus']-1].'</p> </li>';
            }
        }else{
            $datosdb='<li><span>No se Encuentra ningun resultado</span></li>';
        }

        
        $var=array('resultado'=>$datosdb);

        return response()->json($var);
    }




	/**
     * Metodo que retorna la busqueda de muestras segun el filtro utilizado por el usuario
     *
     *
     * @param $reques objetos de tipo reques de laravel;
     *
     * @return $paginador lista de paginas generadas por laravel, $tecnicas arreglo con el conjunto de tecnicas existentes, $itemsForCurrentPage arreglo de objetos con los datos de la muestra
     */


    public function buscar_filtros(Request $request){
       
        $this->eliminarimg_bdd();

        $aux=array();
        $retorno=array();
        $coincidencias=0;

        $query=['actividades_mues_bus'=>$request->actividades_mues_bus,
        		'institucion_mues_bus'=>$request->institucion_mues_bus,
        		'tecnica_mues_bus'=>$request->tecnica_mues_bus,
        		'inicio_mues_bus'=>$request->inicio_mues_bus,
        		'fin_mues_bus'=>$request->fin_mues_bus

        		];


        if ($request->actividades_mues_bus!='') {

            $valor=$request->actividades_mues_bus;         
            
            $act=DB::table('muestra_actividad')->where('id_actividad','=',$valor)->get();

            foreach ($act as $key2) {
            	$aux1=DB::table('muestra')->where('id_muestra','=',$key2->id_muestra)->get();
            	$aux=array_merge($aux,$aux1);
            }

            $coincidencias++;


        }

        if ($request->institucion_mues_bus!='') {

            $valor=$request->institucion_mues_bus;

            $inst=DB::table('institucion_departamento_representante')->where('id_institucion','=',$valor)->get();

            foreach ($inst as $llave1) {
            
            	$rep=DB::table('representante_actividad')->where('id_representante','=',$llave1->id_representante)->get();

            	foreach ($rep as $llave2) {

            		$act=DB::table('muestra_actividad')->where('id_actividad','=',$llave2->id_actividad)->get();

            		foreach ($act as $llave3) {
            			
            			$aux1=DB::table('muestra')->where('id_muestra','=',$llave3->id_muestra)->get();

            			$aux=array_merge($aux,$aux1);

            		}
            	}

            }    
            
            $coincidencias++;


        }
        if ($request->tecnica_mues_bus!='') {

            $valor=$request->tecnica_mues_bus;
                

            $tect=DB::table('muestra_tecnica_estudio')->where('id_tecnica_estudio','=',$valor)->get();


            foreach ($tect as $key2) {
            	$aux1=DB::table('muestra')->where('id_muestra','=',$key2->id_muestra)->get();
            	$aux=array_merge($aux,$aux1);
            }

            $coincidencias++;


        }
        if ($request->inicio_mues_bus!=''&&$request->fin_mues_bus!='') {

            $aux1=DB::table('muestra')->whereBetween('fecha_analisis',[$request->inicio_mues_bus,$request->fin_mues_bus])->get();
            $aux=array_merge($aux,$aux1); 
            $coincidencias++;           

        }

        while (count($aux)!=0) {

        	$aux=array_values($aux);
        	$tama=count($aux);
        	$compaux=$aux[0];
        	unset($aux[0]);
        	$aux=array_values($aux);
        	$validador=1;

        	for ($i=0; $i <$tama-1 ; $i++) { 
        		
        		if ($compaux->id_muestra==$aux[$i]->id_muestra) {
        			
        			$validador++;
        			unset($aux[$i]);

        		}

        	}
        	
        	if ($validador==$coincidencias) {
        		$retorno[]=$compaux;
        	}

        }

        $total_pages=count($retorno);

        $page = Input::get('page', 1); 

        $perPage = 20;

        $offSet = ($page * $perPage) - $perPage;

        $itemsForCurrentPage = array_slice($retorno, $offSet, $perPage, true);


        $valores=array();

        $retorno=array();

    	$datos=$itemsForCurrentPage;

        $public_path = public_path();

        foreach ($datos as $key => $value) {


            if (Storage::exists($value->ruta_img_muestra))
            {

                $datos[$key]->ruta_img_muestra=$this->generar_imagen_visible($public_path.'/storage/'.$value->ruta_img_muestra,$key);

                DB::table('auximg')->insert(['description_img'=>$datos[$key]->ruta_img_muestra,'orgpage_img'=>$value->ruta_img_muestra]);
            }

            $valores['muestra-d']=$datos[$key];
            $valores['actividad-d']=$this->obtener_actividad($value->id_muestra);
            $valores['institucion-d']=$this->obtener_institucion($value->id_muestra);
            $valores['tecnica-d']=$this->obtener_tecnica($value->id_muestra);
            
            $retorno[]=$valores;

        }

        $itemsForCurrentPage=$retorno;


        $tecnica=TecnicaEstudio::all();
        $tecnica=TecnicaEstudio::all();
        $actividad=Actividad::all();
        $institucion=Institucion::all();


        $paginador=new LengthAwarePaginator($itemsForCurrentPage, $total_pages, $perPage, $page, ['path' => 'buscarfiltro','query' =>$query]);


        return view('muestra.prueba',compact('paginador','tecnica','itemsForCurrentPage','actividad','institucion')); 


    }



    /**
     * Metodo que retorna la vista para editar una muestra
     *
     *
     * @param $id integer;
     *
     * @return $muestra datos obtenidos de la muestra seleccionada, $actividad objeto con la informacion de la actividad relacionada a la muestra, $tecnica arreglo de objetos de las muestras existentes, $muestracontenido arreglo de datos relacionados a la iamgen de la muestra, $tecnica_estudio_mues objeto con la informacion de la tecnica de estudio relacionada a la muestra
     */




    public function edit($id)
    {
        $this->eliminarimg_bdd();
        
        $muestra=Muestra::find($id);

        $tecnica_estudio_mues=DB::table('muestra_tecnica_estudio')->where('id_muestra','=',$muestra->id_muestra)->get();

        $actividad_muestr=DB::table('muestra_actividad')->where('id_muestra','=',$muestra->id_muestra)->get();

        $ruta_aux="";

        if (Storage::exists($muestra->ruta_img_muestra))
        {

            $ruta_aux=$this->generar_imagen_visible(public_path().'/storage/'.$muestra->ruta_img_muestra,$muestra->id_muestra);

            DB::table('auximg')->insert(['description_img'=>$ruta_aux,'orgpage_img'=>$muestra->ruta_img_muestra]);

        }

        $valor=$muestra->codigo_muestra;
        $actividad=Actividad::all();
        $tecnica=TecnicaEstudio::all();

        return view('muestra.crear',compact('muestra','actividad','tecnica','tecnica_estudio_mues','actividad_muestr','valor','ruta_aux'));

    }



    /**
     * Metodo que actualiza los datos de una muestra
     *
     *
     * @param $id integer;
     *
     * @return $retorno estado de la solicitud de la muestra 1 para fallido, 0 para exitoso. $actividad arreglo de actividades existentes, $tecnica arreglo de actividades existentes;
     */



    public function update($id)
    {

        $data=Input::all(); 


        $valor_def=explode(';', $data['truerutas']);
        $valor_ori=explode(';', $data['trueorigis']);


        foreach ($valor_def as $key => $value) {
            

            if (strlen($value)>1):


                if (Storage::exists($value)){


                    $muestra=Muestra::find($id);

                    $muestra->nombre_original_muestra=$valor_ori[$key];
                    $muestra->ruta_img_muestra=$value;
                    $muestra->descripcion_muestra=$data['truedescri'];
                    $muestra->fecha_recepcion=$data['truefecharep'];
                    $muestra->fecha_analisis=$data['truefechaana'];

                    $val=$muestra->save();

                    if ($val) {
                        
                        DB::table('muestra_actividad')->where('id_muestra','=',$muestra->id_muestra)->update(['id_actividad'=>$data['trueactividad'],'id_muestra'=>$muestra->id_muestra]);

                        DB::table('muestra_tecnica_estudio')->where('id_muestra','=',$muestra->id_muestra)->update(['id_tecnica_estudio'=>$data['truetecnic'],'id_muestra'=>$muestra->id_muestra]);

                    }

                }


            endif;

        }

        $retorno=0;

        $valor=DB::table('muestra')->max('id_muestra');
        if (!$valor) {$valor=1;}else{$valor++;}

        $valor.=date("d_m_Y_H_i_s");

        $actividad=Actividad::all();
        $tecnica=TecnicaEstudio::all();

        return view('muestra.crear',compact('retorno','actividad','tecnica','valor'));

        
    }

    //Funcion que muestra los detalles de una muestra incluyendo las muestras relacionadas


    /**
     * Metodo que muestra los detalles de una muestra incluyendo las muestras relacionadas
     *
     *
     * @param $id integer;
     *
     * @return $muestra datos obtenidos de la muestra seleccionada, $actividad objeto con la informacion de la actividad relacionada a la muestra, $tecnica arreglo de objetos de las muestras existentes, $muestracontenido arreglo de datos relacionados a la iamgen de la muestra, $tecnica_estudio_mues objeto con la informacion de la tecnica de estudio relacionada a la muestra
     */


    public function details($id){

        $this->eliminarimg_bdd();

        $muestra=Muestra::find($id);

        $tecnica_estudio_mues=DB::table('muestra_tecnica_estudio')->where('id_muestra','=',$muestra->id_muestra)->get();

        $muestracontenido=null;

        $valores=array();

        $releated=array();

        $mymuestra=array();

        $mymuestra['muestra-d']=$muestra;
        $mymuestra['actividad-d']=$this->obtener_actividad($id);
        $mymuestra['institucion-d']=$this->obtener_institucion($id);
        $mymuestra['tecnica-d']=$this->obtener_tecnica($id);
        $mymuestra['representante-d']=$this->obtener_representante($id);


        if (Storage::exists($muestra->ruta_img_muestra))
        {
            $muestracontenido=Storage::size($muestra->ruta_img_muestra);
            $muestra_aux=$this->generar_imagen_visible(public_path().'/storage/'.$muestra->ruta_img_muestra,$muestra->id_muestra);

            DB::table('auximg')->insert(['description_img'=>$muestra_aux,'orgpage_img'=>$muestra->ruta_img_muestra]);


            $muestras_related=DB::table('muestra')->where('codigo_muestra','=',$muestra->codigo_muestra)->get();

            foreach ($muestras_related as $key => $value) {
                
                if ($value->id_muestra!=$id) {
                    $muestras_related[$key]->ruta_img_muestra=$this->generar_imagen_visible(public_path().'/storage/'.$value->ruta_img_muestra,$value->id_muestra);

                    DB::table('auximg')->insert(['description_img'=>$muestras_related[$key]->ruta_img_muestra,'orgpage_img'=>$value->ruta_img_muestra]);

                    $valores['muestra-d']=$muestras_related[$key];
                    $valores['actividad-d']=$this->obtener_actividad($value->id_muestra);
                    $valores['institucion-d']=$this->obtener_institucion($value->id_muestra);
                    $valores['tecnica-d']=$this->obtener_tecnica($value->id_muestra);
                    $valores['representante-d']=$this->obtener_representante($value->id_muestra);
                    
                    $releated[]=$valores;
                }


            }

            $muestra->ruta_img_muestra=$muestra_aux;


            if ($muestracontenido<1000000) {
                $muestracontenido=$muestracontenido / 1000;
                $ext='KB';
            }else{
                $muestracontenido=$muestracontenido / 1000000;
                $ext='MB';
            }

            $muestracontenido=number_format($muestracontenido, 2,'.','').$ext;
        }


        $actividad=Actividad::all();
        $tecnica=TecnicaEstudio::all();

        return view('muestra.detail',compact('muestra','actividad','tecnica','muestracontenido','tecnica_estudio_mues','releated','mymuestra'));

    }


    //Funcion que busca las relaciones de una actividad para una muestra


    /**
     * Metodo que busca las relaciones de una actividad para una muestra
     *
     *
     * @param void;
     *
     * @return retorna una respuesta en formato json de la relacion de las actividades
     */


    public function relacionesact(){

        $datos=Input::all();

        $act=Actividad::find($datos['bus']);
        $represent=DB::table('actividad')->join('representante_actividad',function($join){

            $datos=Input::all();

            $join->where('representante_actividad.id_actividad','=',$datos['bus']);


        })->join('representante','representante.id_representante','=','representante_actividad.id_representante')
        ->select('representante.*')->get();
        $sector=DB::table('sector_actividad')->where('id_sector_ac','=',$act->id_sector_ac)->get();


        return response()->json([
            'act'=>$act->status_actividad,
            'cirepre'=>$represent[0]->cedula,
            'nomrepre'=>$represent[0]->nombre." ".$represent[0]->apellido,
            'sect'=>$sector[0]->descripcion_sector
        ]);

    }

    public function get_data($id){

        $this->eliminarimg_bdd();
        
        $muestra=Muestra::find($id);

        $ruta_aux="";

        if (Storage::exists($muestra->ruta_img_muestra))
        {

            $ruta_aux=$this->generar_imagen_visible(public_path().'/storage/'.$muestra->ruta_img_muestra,$muestra->id_muestra);

            $muestra->tipo_muestra=$ruta_aux;

            DB::table('auximg')->insert(['description_img'=>$ruta_aux,'orgpage_img'=>$muestra->ruta_img_muestra]);

        }

        return $muestra;


    }

    public function generate_singlepdf(Request $request){

        $data1=$this->get_data($request->id_mues);
        $date = date('Y-m-d');
        $invoice = "2222";
        $view =  \View::make('pdf.invoice', compact('date', 'invoice','data1','request'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Muestra.pdf');

    }


    public function destroy($id)
    {
        
    }
}




?>