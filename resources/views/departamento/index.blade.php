@extends('layouts.app')
@section('title', 'Departamentos')
@section('content')

    
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Tutores</h2>
                </header>
                <div class="panel-body">

                    <div class="form-group col-md-3 modalscript">
                        <a class="btn btn-default click" href="#" data-typeform="add" data-taction="{{url('departamento/register-form')}}" data-field-id="0">Agregar <i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                    </div>

                    <div class="form-group col-md-12" id="principal_muestras_table">
                        
                        <table class="table table-bordered table-striped mb-none" id="datatable-editable">
                            <thead>
                                <tr>
                                    <th>Miniatura</th>
                                    <th>Codigo</th>
                                    <th>Archivo</th>
                                    <th>Descripción</th>
                                    <th>Tecnica de Estudio</th>
                                    <th>Operaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="gradeX">
                                    <td>img</td>
                                    <td>ms-0001-iutfrp</td>
                                    <td>kjx9872.TIF</td>
                                    <td>Muestra Tomada de la quilla de un buque de carga.</td>
                                    <td>XYZ</td>
                                    <td class="actions">
                                        <a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
                                        <a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
                                        <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                        <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
                {!!Form::open(['url'=>'#', 'class'=>'hiddenform' , 'method' => 'post' , 'id'=>'principalform'])!!}

                    <a class="mb-xs mt-xs mr-xs modal-with-zoom-anim btn btn-default openmodalbtn" href="#modalForm"></a>
                    <input type="hidden" name="typeform" value="">
                    <input type="hidden" name="field_id" value="">

                {!! Form::close() !!}

            <!-- Modals -->
                <div id="modalForm" class="zoom-anim-dialog modal-block-lg modal-block-primary mfp-hide">
                </div>
            </section>
        </div>
    </div>

@endsection