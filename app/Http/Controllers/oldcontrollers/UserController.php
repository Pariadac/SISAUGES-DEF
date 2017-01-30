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

use SISAUGES\Http\Requests;
use SISAUGES\Http\Controllers\Controller;
use SISAUGES\RolUsuario;
use SISAUGES\User;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\View;

/**
 * Class UserController
 *
 * Esta clase se diseño para manejar las transancciones del usuario en la base de
 * datos, estas pueden ser agregar,
 * modificar, eliminar o listar.
 *
 * @author Ely Colmenarez ElyJColmenarez@Gmail.com
 * @copyright 2016 Ely Colmenarez
 * @package SISAUGES\Http\Controllers
 */

class UserController extends Controller
{

    protected $nivel;
    public function __construct()
    {
        $this->middleware('auth');
        $this->nivel = NivelUsuario::all()->pluck('descripcion_nivel_usuario','id_nivel_de_usuario');
    }
    /**
     * Metodo diseñado para direccionar a la pantalla principal del modulo
     *
     * Este metodo redirige a la pantalla principal del modulo usuario
     * esta pantalla mostrara un listado de los usuarios agregados y un boton para modificar
     * y eliminar
     *
     * @param void
     *
     * @return $usuario devuelve objeto de tipo usuario
     */
    public function index()
    {
        //$usuario = User::all();
        //return view('users.index')->with('usuario',$usuario);
        return view('users.index');
    }


    public function renderform(Request $request){

        if ($request->typeform=='add') {
            $action="/crear";
        }elseif($request->typeform=='modify'){
            $action="/modificar";
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
     * Metodo diseñado para direccionar a la pantalla de agregar un usuario
     *
     * Este metodo redirige a la pantalla agregar usuario
     * la cual mostrara un formulario con los campos necesarios para almacenar
     * en la base de datos
     *
     * @param void
     *
     * @return $usuario devuelve objeto de tipo usuario
     */
    public function create()
    {
        return view('users.crear')->with('nivel',$this->nivel);
    }
    /**
     * Metodo diseñado para almacenar el usuario en la base de datos
     *
     * @param void
     *
     * @return $menssage retorna el resultado de la operación
     */
    public function store()
    {
        $usuario = new User();
        $usuario->cedula =\Crypt::encrypt(\Request::Input('cedula'));
        $usuario->nombre=\Request::Input('nombre');
        $usuario->apellido=\Request::Input('apellido');
        $usuario->email=\Request::Input('email');
        $usuario->telefono=\Request::Input('telefono');
        $usuario->username=\Request::Input('nombreUsuario');
        $usuario->password=\Hash::make(\Request::Input('password'));
        $usuario->save();

        $nivel=\Request::Input('nivelUsuario');

        foreach($nivel as $niveles)
        {
            $usuario->nivelUsuarios()->attach([$niveles]);
        }


        return redirect('usuario')->with('message','Se ha agregado a un Usuario con Exito');
    }

    /**
     * Metodo diseñado para direccionar a la pantalla de editar un usuario
     *
     * Este metodo redirige a la pantalla editar usuario
     * la cual mostrara un formulario con los datos del representate seleccionado,
     * con los campos necesarios para almacenar en la base de datos
     *
     * @param $id codigo de asociación del usuario en la base de datos
     *
     * @return $array un arreglo de objetos de los datos del usuario
     */

    public function edit($id)
    {
        $usuario = User::find($id);
        $usuarioSeleccionado=User::find($id)->nivelUsuarios()->pluck('usuarios_niveles.id_nivel_de_usuario')->toArray();

        return view('users.editar')->with(['usuario'=>$usuario,'nivel'=>$this->nivel,
                                        'usuarioSeleccionado'=>$usuarioSeleccionado]);
    }

    /**
     * Metodo diseñado para actualizar los datos de un usuario en la base de datos
     *
     * @param $id codigo de asociación del usuario en la base de datos, $request datos enviados atravez del formulario
     *
     * @return $menssage retorna el resultado de la operación.
     */

    public function update($id, Request $request)
    {
        $usuario = User::find($id);
        $usuario->cedula=Crypt::encrypt(\Request::Input('cedula'));
        $usuario->nombre=\Request::Input('nombre');
        $usuario->apellido=\Request::Input('apellido');
        $usuario->email=\Request::Input('email');
        $usuario->telefono=\Request::Input('telefono');
        $usuario->password = \Hash::make(\Request::Input('password'));
        $usuario->save();
        $nivel = $request->input('nivelUsuario');

        $usuario->nivelUsuarios()->sync($nivel);

        return redirect('usuario')->with('message','El usuario N°'.$id.' ha sido editado');
    }
    /**
     * Metodo diseñado para eliminar los datos de un usuario en la base de datos
     *
     * @param $id codigo de asociación del usuario en la base de datos
     *
     * @return $menssage retorna el resultado de la operación.
     */

    public function destroy($id)
    {
        $usuario = User::find($id);
        $usuario->nivelUsuarios()->detach();
        $usuario->delete();

        return redirect('usuario')->with('message','El usuario'.$id.' ha sido eliminado con exito');
    }
}
