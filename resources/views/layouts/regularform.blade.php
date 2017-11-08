	<section class="panel">

		<style type="text/css">
			.select2-drop-mask {
			      position: absolute;
			      bottom: 0;
			      left: 0;
			      right: 0;
			      top: 0;
			      z-index: 99999998;
			}

			.select2-drop-superindex{
				z-index: 999999999;
			}


			.datepicker{
				z-index: 99999999!important;
			}

			.datepicker.dropdown-menu{
				z-index: 99999999!important;
			}
		</style>

		<script type="text/javascript">
			jQuery(document).ready(function() {

				$('#modalForm').on('click', '.datepkr', function() {

			        $(this).datepicker({
			            format:'dd/mm/yyyy',
			            autoclose: true,
			        });
			        $(this).datepicker('show');
			    });
			})
		</script>
									

		{!!Form::open(['url'=>$action, 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal form-bordered modalmicroform', 'method' => 'post', 'target'=>'_blank'])!!}

	        <header class="panel-heading">
				<h2 class="panel-title">Formulario de {!! $modulo !!}</h2>
			</header>

			<div class="waitingimg" style="display: none;">
		    	<div class="waitingback"></div>
		    	<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button class="btn btn-default modal-dismiss">Cancelar</button>
						</div>
					</div>
				</footer>
		    </div>

		    <div class="imgpreview" style="display: none;">
		    	<div class="panel-body">
			    	<div class="waitingprev">
			    		<img src="{{asset('assets/images/!logged-user.jpg')}}">
			    	</div>
			    </div>
		    	<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button class="btn btn-primary">Atras</button>
						</div>
					</div>
				</footer>
		    </div>

		    <div class="result-mdl" style="display: none;" data-tmodalorigin="{{(!$fields)?'modal-block-warning':'modal-block-primary'}}">
		    	<div class="panel-body ">
					<div class="modal-wrapper">
						<div class="modal-icon">
							<i class=""></i>
						</div>
						<div class="modal-text">
							<h4 class="msn-alerta-header"></h4>
							<p class="msn-alerta-body"></p>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right truebtndissmis">
							<button class="mld-dismiss-fin">OK</button>
						</div>
					</div>
				</footer>
		    </div>

		    @if($fields)
			    <div class="mdl-truebody">
					<div class="panel-body">


					<?php $datos=array_chunk($fields, 2,true); ?>

						<div class="formcontent">


							@foreach( $datos as $key2 => $value2 )

								<div class="form-group">

							        @foreach( $value2 as $key => $value )

							        	@if(isset($value['validaciones']))
								                
						        			<?php $validaciones=""; ?>

					                    	@foreach($value['validaciones'] as $k => $val)

					                    		@if($k !== 'limites')
					                    			<?php $validaciones.= 'data-'.$val.'="true"'; ?> 
					                    		@else
					                    			<?php $validaciones.= 'data-limites="'.$val[0].','.$val[1].'"'; ?>
					                    		@endif

					                    	@endforeach

					                    @endif

					                    @if(Auth::user()->id_rol != 4)


								        	@if( $value['type']=='text' || $value['type']=='password' || $value['type']=='email')

								        		<div class="col-md-6">
									                <label class="col-md-4 control-label" for="{!! $key !!}">{!! $value['label'] !!}</label>
									                <div class="col-md-8">
									                    <input type="{!! $value['type'] !!}" class="form-control" id="{!! $value['id'] !!}" name="{!! $key !!}" value="{!! $value['value'] !!}" @if (isset($value['validaciones'])) {!! $validaciones !!}  @endif>
									                </div>
									            </div>

									        @elseif( $value['type']=='textarea' )

									        	<div class="col-md-12">
									                <label class="col-md-2 control-label" for="{!! $key !!}">{!! $value['label'] !!}</label>
									                <div class="col-md-10">
									                	<textarea class="form-control" rows="3"  id="{!! $value['id'] !!}" name="{!! $key !!}" @if (isset($value['validaciones'])) {!! $validaciones !!}  @endif>{!! $value['value'] !!}</textarea>
									                </div>
									            </div>


									        @elseif( $value['type']=='label' )

									        	@if(is_array($value['valshow']))


										        	<div class="col-md-12">
										                <label class="col-md-2 control-label" for="{!! $key !!}">{!! $value['label'] !!}: </label>
										                <label class="col-md-10 control-label" style="text-align: left!important;" for="{!! $key !!}"> 
										                		
										                		@foreach($value['valshow'] as $showkey => $showval)

										                			<?php echo '(<strong>Campo</strong>:'.$showkey.' <strong>Valor</strong>:'.$showval.')<br><br>'; ?>

										                		@endforeach

										                </label>
										            </div>

									        	@else

										        	<input type="hidden" id="{!! $value['id'] !!}" name="{!! $value['valkey'] !!}" value="{!! $value['value'] !!}" class="form-control">

										        	<div class="col-md-6">
										                <label class="col-md-4 control-label" for="{!! $key !!}">{!! $value['label'] !!}: </label>
										                <label class="col-md-8 control-label" style="text-align: left!important;" for="{!! $key !!}"> <strong>{{ $value['valshow'] }}</strong> </label>
										            </div>

										        @endif

									        @elseif( $value['type']=='separador' )


								        	@elseif( $value['type']=='report_relacion' )


								        		<div class="col-md-6">
									                <label class="col-md-4 control-label" for="{!! $key !!}">{!! $value['label'] !!}</label>
									                <div class="col-md-8">
									                    <select data-plugin-selectTwo name="{!! $key !!}" id="{!! $value['id'] !!}" class="form-control populate" value="{!! $value['value'] !!}" @if (isset($value['validaciones'])) {!! $validaciones !!}  @endif>
									                        
									                    	<?php $aux1=$value['objkeys'][0]; $aux2=$value['objkeys'][1] ?>

								                        	@foreach( $value['options'] as $key2 => $value2 )

								                        		<option value="{!! $value2->$aux1 !!}" {{ ($value['value']==$value2->$aux1)? 'selected' : '' }}> {!! $value2->$aux2 !!} </option>

								                        	@endforeach

									                    </select>
									                </div>
									            </div>


								        	@elseif( $value['type']=='report_muestra' )

									        		<div class="col-md-12 muestra-seccion mtrsrpt">

									        			<div id="imgresorces"  data-imagennodisponible="{{asset('assets/images/nodisponible.svg')}}"></div>

									        			<?php $aux1=$value['objkeys'][0]; $aux2=$value['objkeys'][1]; $objaux='' ?>

									        			@foreach($value['options'] as $pro_muestrakey => $pro_muestravalue)

									        				<div class="panel">
																<header class="panel-heading">
																	<div class="panel-actions">
																		<a href="#" class="fa fa-caret-down pnless" data-target="{{ $pro_muestrakey }}"  @if($pro_muestrakey!=0) data-open="false" @else data-open="true" @endif></a>
																	</div>
													
																	<h2 class="panel-title">{{ $pro_muestravalue->$aux2 }}</h2>
																</header>
																<div class="panel-body">

																	<?php $archi=$pro_muestravalue->archivo()->get() ?>
																	
																	@if(count($archi)>0)

														        		<div  @if($pro_muestrakey!=0) class="panelocultos"  @endif id="pnl{{ $pro_muestrakey }}">
														        			<div class="col-md-12">

														        				<div class="tablecontainer">
															        				<table class="table table-bordered table-striped mb-none">

															        					<thead>
															        						<tr>
															        							<th>Miniatura</th>
															        							<th>Archivo</th>
															        							<th>Tipo de Archivo</th>
															        							<th>Tamaño</th>
															        							<th>Tecnica de Estudio</th>
															        							<th>Fecha de Registro</th>
															        							<th></th>
															        							<th>Incluir</th>
															        						</tr>
															        					</thead>
															        					<tbody id="mstr{{ $pro_muestravalue->id_muestra }}">
															        						<?php

															        							if (count($archi)>0) {
															        								
															        							
															        								foreach ($archi as $mkey => $muestra) {
															        								
															        									$ruta=$muestra->ruta_img_muestra.$muestra->nombre_temporal_muestra;

															    

															        									if (file_exists($ruta)) {
															        										$finfo = new finfo(FILEINFO_MIME);

																	        								$type = explode(';', $finfo->file($ruta));


																	        								if (explode('/', $type[0])[0]=='image'){

																	        									$extension=explode('.', $muestra->nombre_temporal_muestra);

															        											$rutaweb=$muestra->ruta_img_muestra.'visibles/'.str_replace($extension[1], 'jpg', $muestra->nombre_temporal_muestra);

																        										$putimg=url($rutaweb);

																        									}else{ 

																        										$rutaweb=$ruta;

																        										$putimg=asset('assets/images/nodisponible.svg'); 
																        									}

																	        								echo "<tr id='tableregd".$mkey."' data-regid='d".$mkey."' data-trueid='".$muestra->id_archivo."'>";
																	        									echo    "<td><div class='tbl-imgcontainer'><img src='".$putimg."'></div></td>";
																		        								echo "<td>".$muestra->nombre_original_muestra."</td>";
																		        								echo "<td>".$type[0]."</td>";
																		        								echo "<td>".(filesize($ruta)/1000)."KB</td>";
																		        								echo "<td>".$muestra->tecnicaEstudio()->first()->descripcion_tecnica_estudio."</td>";
																		        								echo "<td>".$muestra->fecha_analisis."</td>";
																		        								echo '<td>
																		        									<a href="#" class="btn btn-primary verimg" data-visible="true" data-field-url="'.url($rutaweb).'" data-typefile="'.explode('/', $type[0])[0].'"><i class="fa fa-eye"></i></a>
																		        								</td>';
																			        							echo "<td>
																			        									<div class='switch switch-sm switch-primary'>
																															<input type='checkbox' name='archivosincluidos[]' data-plugin-ios-switch value=".$muestra->id_archivo." />
																														</div>
																			        							</td>";
																		        							echo "</tr>";
															        									}

																        								

																        							}


																        						}
															        							


															        						?>
															        					</tbody>

															        				</table>
															        			</div>
														        			</div>
														        		</div>


													        		@endif

																</div>
															</div>

									        			@endforeach


										        		
										        		
										        	</div>


									        @elseif( $value['type']=='titulo' )

									        	<div class="col-md-12 formsptitels"><h2>{{ $value['value'] }}</h2></div>


									        @elseif( $value['type']=='relacion' )

									        	<div  id="relacion-{!! $value['relacion_campo'] !!}">

										        	<div class="col-md-12">
										                <label class="col-md-2 control-label" for="{!! $key !!}">{!! $value['label'] !!}</label>
										                <div class="col-md-10" style="padding: 0px">
										                    
										                    <div class="col-md-4">
										                    
										                        <select data-plugin-selectTwo name="{!! $key !!}" id="{!! $value['id'] !!}" class="form-control populate relacionselect" data-btid="{!! $value['id'] !!}" value="{!! $value['value'] !!}" @if (isset($value['validaciones'])) {!! $validaciones !!}  @endif>

										                        	<option value="">Seleccione...</option>
										                        
											                    	<?php $aux1=$value['objkeys'][0]; $aux2=$value['objkeys'][1]; $objaux=''; ?>

											                    	@if(isset($value['options']))

											                        	@foreach( $value['options'] as $key2 => $value2 )

											                        		<?php

											                        			if ($key2==0) {
											        								
											        								$objaux=$value2;
											        							}

											                        		?>


											                        		<option value="{!! $value2->$aux1 !!}" {{ ($value['value']==$value2->$aux1)? 'selected' : '' }}> {!! $value2->$aux2 !!} </option>

											                        	@endforeach

											                        @endif

										                    	</select>

										                    </div>

										                    <div class="col-md-8">

										                    	<button class="btn btn-success" style="display: none;" id="relacionpointer-{!! $value['id'] !!}" name="relationadd" value="{!! $value['selectadd']['url'] !!}" data-relation="{!! $value['relacion_campo'] !!}">{!! $value['selectadd']['btnadd'] !!}</button>

										                        <button class="btn btn-primary" name="nextstep" value="{!! $value['selectadd']['url'] !!}"  data-idpointer="{!! $value['id'] !!}" data-finlabel="{!! $value['selectadd']['btnfinlavel'] !!}">{!! $value['selectadd']['btnlabel'] !!}</button>
										                    </div>


										                </div>
										            </div>



										        	<div class="col-md-12" id="relation-{{ $value['relacion_campo'] }}">

										        		<div class="deleted"></div>
										        		<div class="added">
										        			
										        			@if($value['relation_table']['table_obj']!=null)

								        						@foreach($value['relation_table']['table_obj'] as $relacionesp)

								        							<input type="hidden" name="addenin{{ $value['relacion_campo'] }}[]" value="{{ $relacionesp->$aux1 }}" id="addin{{ $relacionesp->$aux1 }}-{{ $value['relacion_campo'] }}" class="form-control">


								        						@endforeach

								        					@endif

										        		</div>
										        		
										        		<div class="">
										        			<div class="col-md-12">

										        				<h3>{{ $value['relation_table']['title'] }}</h3>

										        				<div class="tablecontainer">
										        					<table class="table table-bordered table-striped mb-none newrecords">

											        					<thead>
											        						<tr>
											        							@foreach($value['relation_table']['table_fields'] as $tablef)

											        								<th>{{$tablef}}</th>

											        							@endforeach

											        							<th></th>
											        						</tr>
											        					</thead>
											        					<tbody>

											        						<?php $auxstr=$value['relacion_campo']; ?>

											        						@if($value['relation_table']['table_obj']!=null)

												        						@foreach($value['relation_table']['table_obj'] as $relacionesp)

												        							<tr  id="tablereg{{ $relacionesp->$aux1 }}"  data-trueid="{{ $relacionesp->$aux1 }}">
												        								
												        								<td>{{ $relacionesp->$aux2 }}</td>
												        								<td class="tableregularbtns">
												        									<a href="#" class="btn btn-warning remove-row deleted-row editbtn"  data-typeform="modify" data-taction="registerform" data-visible="false" data-trueid="{{ $relacionesp->$aux1 }}" data-relation="{!! $value['relacion_campo'] !!}" data-urlval="{!! $value['selectadd']['url'] !!}" data-idpointer="{!! $value['id'] !!}" data-finlabel="{!! $value['selectadd']['btnfinlavel'] !!}"><i class="fa fa-pencil"></i></a>

												        									<a href="#" class="btn btn-danger remove-row deleted-row btnborrar" data-field-id="{{ $relacionesp->$aux1 }}"  data-trueid="{{ $relacionesp->$aux1 }}" data-relation="{!! $value['relacion_campo'] !!}"><i class="fa fa-trash-o"></i></a>
												        								</td>

												        							</tr>


												        						@endforeach

												        					@endif

											        					</tbody>

											        				</table>
										        				</div>
										        			</div>
										        		</div>


										        	</div>


										        </div>


									        @elseif( $value['type']=='muestra' )

									        	<div class="col-md-12 muestra-seccion">

								        			<input type="hidden" name="imgcont" value="0">

								        			<div class="ocultos"></div>
								        			<div class="tecnicasmstrs"></div>
								        			<div class="borrados"></div>

								        			<div id="imgresorces"  data-imagennodisponible="{{asset('assets/images/nodisponible.svg')}}"></div>

									        		<div class="">
									        			<div class="col-md-12">

									        				<div class="col-md-6">
									        					<h3>Registros Nuevos <button class="btn btn-default click" name="cargaimg">Agregar Archivos</button></h3>
									        				</div>

									        				<div class="col-md-6">


									        					<?php  $tecnica=$value['relaciones']['tecnica']; ?>

												                <h3>
												                    
												                    <div class="col-md-7">
												                    
												                        <select data-plugin-selectTwo name="tecnica" id="{!! $tecnica['id'] !!}" class="form-control populate" value="{!! $tecnica['value'] !!}">

												                        	<option value="">Seleccione {!! $tecnica['label'] !!}</option>
												                        
													                    	<?php $aux1=$tecnica['objkeys'][0]; $aux2=$tecnica['objkeys'][1] ?>

													                    	@if(isset($tecnica['options']))

													                        	@foreach( $tecnica['options'] as $key2 => $value2 )

													                        		<option value="{!! $value2->$aux1 !!}" {{ ($tecnica['value']==$value2->$aux1)? 'selected' : '' }}> {!! $value2->$aux2 !!} </option>

													                        	@endforeach

													                        @endif

												                    	</select>

												                    </div>

												                    <div class="col-md-5">
												                        <button class="btn btn-primary" name="nextstep" value="{!! $tecnica['selectadd']['url'] !!}" data-idpointer="{!! $tecnica['id'] !!}" data-finlabel="{!! $tecnica['selectadd']['btnfinlavel'] !!}">{!! $tecnica['selectadd']['btnlabel'] !!}</button>
												                    </div>

												                </h3>


									        				</div>

									        				<div class="tablecontainer">
									        					<table class="table table-bordered table-striped mb-none newrecords">

										        					<thead>
										        						<tr>
										        							<th>Miniatura</th>
										        							<th>Archivo</th>
										        							<th>Tipo de Archivo</th>
										        							<th>Tamaño</th>
										        							<th>Tecnica de Estudio</th>
										        							<th>Fecha de Registro</th>
										        							<th></th>
										        						</tr>
										        					</thead>
										        					<tbody>
										        					</tbody>

										        				</table>
									        				</div>
									        			</div>
									        		</div>


									        		@if(count($value['data'])>0)

										        		<div class="">
										        			<div class="col-md-12">

										        				<h3>Registros Existentes</h3>

										        				<div class="tablecontainer">
											        				<table class="table table-bordered table-striped mb-none">

											        					<thead>
											        						<tr>
											        							<th>Miniatura</th>
											        							<th>Archivo</th>
											        							<th>Tipo de Archivo</th>
											        							<th>Tamaño</th>
											        							<th>Tecnica de Estudio</th>
											        							<th>Fecha de Registro</th>
											        							<th></th>
											        						</tr>
											        					</thead>
											        					<tbody>
											        						<?php

											        							$archi=$value['data']->archivo()->get();

											        							if (count($archi)>0) {
											        								
											        							
											        								foreach ($archi as $mkey => $muestra) {
											        								
											        									$ruta=$muestra->ruta_img_muestra.$muestra->nombre_temporal_muestra;

											    

											        									if (file_exists($ruta)) {
											        										$finfo = new finfo(FILEINFO_MIME);

													        								$type = explode(';', $finfo->file($ruta));


													        								if (explode('/', $type[0])[0]=='image'){

													        									$extension=explode('.', $muestra->nombre_temporal_muestra);

											        											$rutaweb=$muestra->ruta_img_muestra.'visibles/'.str_replace($extension[1], 'jpg', $muestra->nombre_temporal_muestra);

												        										$putimg=url($rutaweb);

												        									}else{ 

												        										$rutaweb=$ruta;

												        										$putimg=asset('assets/images/nodisponible.svg'); 
												        									}

													        								echo "<tr id='tableregd".$mkey."' data-regid='d".$mkey."' data-trueid='".$muestra->id_archivo."'>";
													        									echo    "<td><div class='tbl-imgcontainer'><img src='".$putimg."'></div></td>";
														        								echo "<td>".$muestra->nombre_original_muestra."</td>";
														        								echo "<td>".$type[0]."</td>";
														        								echo "<td>".(filesize($ruta)/1000)."KB</td>";
														        								echo "<td>".$muestra->tecnicaEstudio()->first()->descripcion_tecnica_estudio."</td>";
														        								echo "<td>".$muestra->fecha_analisis."</td>";
														        								echo '<td>
														        									<a href="#" class="btn btn-primary verimg" data-visible="true" data-field-url="'.url($rutaweb).'" data-typefile="'.explode('/', $type[0])[0].'"><i class="fa fa-eye"></i></a>
														        									<a href="#" class="btn btn-danger remove-row deleted-row eliminarimg" data-existfile="'.$mkey.'" data-field-id="d'.$mkey.'"><i class="fa fa-trash-o"></i></a>
														        								</td>';
														        							echo "</tr>";
											        									}

												        								

												        							}


												        						}
											        							


											        						?>
											        					</tbody>

											        				</table>
											        			</div>
										        			</div>
										        		</div>


									        		@endif
									        		
									        	</div>


									        @elseif( $value['type']=='muestrareport' )


									        @elseif( $value['type']=='date' )


									        	<div class="col-md-6">
													<label class="col-md-4 control-label" for="{!! $key !!}">{!! $value['label'] !!}</label>
													<div class="col-md-8">
														<div class="input-group">
															<span class="input-group-addon">
																<i class="fa fa-calendar"></i>
															</span>
															<input type="text" data-plugin-datepicker class="form-control datepkr" id="{!! $value['id'] !!}" name="{!! $key !!}" value="{!! $value['value'] !!}">
														</div>
													</div>
												</div>

								        	@elseif( $value['type']=='select' )

								        		@if(isset($value['extrafields']))

								        			<div class="col-md-6">
										                <label class="col-md-4 control-label" for="{!! $key !!}">{!! $value['label'] !!}</label>
										                <div class="col-md-8" style="padding: 0px">
										                    
										                    <div class="col-md-4">
										                        <select data-plugin-selectTwo name="{!! $key !!}" id="{!! $value['id'] !!}" class="form-control populate" value="{!! $value['value'] !!}" @if (isset($value['validaciones'])) {!! $validaciones !!}  @endif>
										                            
										                        	@foreach( $value['options'] as $key2 => $value2 )

										                        		<option value="{!! $key2 !!}" {{ ($value['value']==$key2)? 'selected' : '' }}> {!! $value2 !!} </option>

										                        	@endforeach

										                        </select>
										                    </div>
										                    @foreach( $value['extrafields'] as $key2 => $value2 )

										                    	@if(isset($value['extrafields']['validaciones']))
									                
												        			<?php $validaciones=""; ?>

											                    	@foreach($value['extrafields']['validaciones'] as $k => $val)

											                    		@if($k!='limites')
											                    			<?php $validaciones.= 'data-'.$val.'="true"'; ?> 
											                    		@else
											                    			<?php $validaciones.= 'data-limites="'.$val[0].','.$val[1].'"'; ?>
											                    		@endif

											                    	@endforeach

											                    @endif

											                    <div class="col-md-8">
											                        <input type="text" class="form-control" id="{!! $value2['name'] !!}" name="{!! $value2['name'] !!}" value="{!! $value2['value'] !!}" placeholder="{!! $value2['placeholder'] !!}" @if (isset($value['extrafields']['validaciones'])) {!! $validaciones !!}  @endif>
											                    </div>

											                @endforeach

										                </div>
										            </div>


										        @elseif(isset($value['selecttype']))


										        	@if(isset($value['selectadd']))


									        			<div class="col-md-12">
											                <label class="col-md-2 control-label" for="{!! $key !!}">{!! $value['label'] !!}</label>
											                <div class="col-md-8" style="padding: 0px">
											                    
											                    <div class="col-md-7">
											                    
											                        <select data-plugin-selectTwo name="{!! $key !!}" id="{!! $value['id'] !!}" class="form-control populate" value="{!! $value['value'] !!}" @if (isset($value['validaciones'])) {!! $validaciones !!}  @endif>

											                        	<option value=" ">Seleccione...</option>
											                        
												                    	<?php $aux1=$value['objkeys'][0]; $aux2=$value['objkeys'][1] ?>

												                    	@if(isset($value['options']))

												                        	@foreach( $value['options'] as $key2 => $value2 )

												                        		<option value="{!! $value2->$aux1 !!}" {{ ($value['value']==$value2->$aux1)? 'selected' : '' }}> {!! $value2->$aux2 !!} </option>

												                        	@endforeach

												                        @endif

											                    	</select>

											                    </div>

											                    <div class="col-md-5">
											                        <button class="btn btn-primary" name="nextstep" value="{!! $value['selectadd']['url'] !!}" data-idpointer="{!! $value['id'] !!}" data-finlabel="{!! $value['selectadd']['btnfinlavel'] !!}">{!! $value['selectadd']['btnlabel'] !!}</button>
											                    </div>


											                </div>
											            </div>

											        @else

											        	<div class="col-md-6">
											                <label class="col-md-4 control-label" for="{!! $key !!}">{!! $value['label'] !!}</label>
											                <div class="col-md-8">
											                    <select data-plugin-selectTwo name="{!! $key !!}" id="{!! $value['id'] !!}" class="form-control populate" value="{!! $value['value'] !!}" @if (isset($value['validaciones'])) {!! $validaciones !!}  @endif>
											                        
											                    	<?php $aux1=$value['objkeys'][0]; $aux2=$value['objkeys'][1] ?>

										                        	@foreach( $value['options'] as $key2 => $value2 )

										                        		<option value="{!! $value2->$aux1 !!}" {{ ($value['value']==$value2->$aux1)? 'selected' : '' }}> {!! $value2->$aux2 !!} </option>

										                        	@endforeach

											                    </select>
											                </div>
											            </div>

											        @endif

								        		@else


								        			<div class="col-md-6">
										                <label class="col-md-4 control-label" for="{!! $key !!}">{!! $value['label'] !!}</label>
										                <div class="col-md-8">
										                    <select data-plugin-selectTwo name="{!! $key !!}" id="{!! $value['id'] !!}" class="form-control populate" value="{!! $value['value'] !!}" @if (isset($value['validaciones'])) {!! $validaciones !!}  @endif>
										                            
									                        	@foreach( $value['options'] as $key2 => $value2 )

									                        		<option value="{!! $key2 !!}" {{ ($value['value']==$key2  && strlen($value['value'])==strlen($key2) )? 'selected' : '' }}> {!! $value2 !!} </option>

									                        	@endforeach

										                    </select>
										                </div>
										            </div>


								        		@endif

								        	@else

								        	@endif

								       	@else


								       		@if( $value['type']=='relacion' )

									        	<div  id="relacion-{!! $value['relacion_campo'] !!}">

									        		<?php $aux1=$value['objkeys'][0]; $aux2=$value['objkeys'][1]; $objaux='' ?>

										        	<div class="col-md-12" id="relation-{{ $value['relacion_campo'] }}">

										        		<div class="deleted"></div>
										        		<div class="added">
										        			
										        			@if($value['relation_table']['table_obj']!=null)

								        						@foreach($value['relation_table']['table_obj'] as $relacionesp)

								        							<input type="hidden" name="addenin{{ $value['relacion_campo'] }}[]" value="{{ $relacionesp->$aux1 }}" id="addin{{ $relacionesp->$aux1 }}-{{ $value['relacion_campo'] }}" class="form-control">


								        						@endforeach

								        					@endif

										        		</div>
										        		
										        		<div class="">
										        			<div class="col-md-12">

										        				<h3>{{ $value['relation_table']['title'] }}</h3>

										        				<div class="tablecontainer">
										        					<table class="table table-bordered table-striped mb-none newrecords">

											        					<thead>
											        						<tr>
											        							@foreach($value['relation_table']['table_fields'] as $tablef)

											        								<th>{{$tablef}}</th>

											        							@endforeach
											        						</tr>
											        					</thead>
											        					<tbody>

											        						<?php $auxstr=$value['relacion_campo']; ?>

											        						@if($value['relation_table']['table_obj']!=null)

												        						@foreach($value['relation_table']['table_obj'] as $relacionesp)

												        							<tr  id="tablereg{{ $relacionesp->$aux1 }}"  data-trueid="{{ $relacionesp->$aux1 }}">
												        								
												        								<td>{{ $relacionesp->$aux2 }}</td>

												        							</tr>


												        						@endforeach

												        					@endif

											        					</tbody>

											        				</table>
										        				</div>
										        			</div>
										        		</div>


										        	</div>


										        </div>


									        @elseif( $value['type']=='muestra' )

									        	<div class="col-md-12 muestra-seccion">

								        			<input type="hidden" name="imgcont" value="0">

								        			<div class="ocultos"></div>
								        			<div class="tecnicasmstrs"></div>
								        			<div class="borrados"></div>

								        			<div id="imgresorces"  data-imagennodisponible="{{asset('assets/images/nodisponible.svg')}}"></div>


									        		@if(count($value['data'])>0)

										        		<div class="">
										        			<div class="col-md-12">

										        				<h3>Registros Existentes</h3>

										        				<div class="tablecontainer">
											        				<table class="table table-bordered table-striped mb-none">

											        					<thead>
											        						<tr>
											        							<th>Miniatura</th>
											        							<th>Archivo</th>
											        							<th>Tipo de Archivo</th>
											        							<th>Tamaño</th>
											        							<th>Tecnica de Estudio</th>
											        							<th>Fecha de Registro</th>
											        							<th></th>
											        						</tr>
											        					</thead>
											        					<tbody>
											        						<?php

											        							$archi=$value['data']->archivo()->get();

											        							if (count($archi)>0) {
											        								
											        							
											        								foreach ($archi as $mkey => $muestra) {
											        								
											        									$ruta=$muestra->ruta_img_muestra.$muestra->nombre_temporal_muestra;

											    

											        									if (file_exists($ruta)) {
											        										$finfo = new finfo(FILEINFO_MIME);

													        								$type = explode(';', $finfo->file($ruta));


													        								if (explode('/', $type[0])[0]=='image'){

													        									$extension=explode('.', $muestra->nombre_temporal_muestra);

											        											$rutaweb=$muestra->ruta_img_muestra.'visibles/'.str_replace($extension[1], 'jpg', $muestra->nombre_temporal_muestra);

												        										$putimg=url($rutaweb);

												        									}else{ 

												        										$rutaweb=$ruta;

												        										$putimg=asset('assets/images/nodisponible.svg'); 
												        									}

													        								echo "<tr id='tableregd".$mkey."' data-regid='d".$mkey."' data-trueid='".$muestra->id_archivo."'>";
													        									echo    "<td><div class='tbl-imgcontainer'><img src='".$putimg."'></div></td>";
														        								echo "<td>".$muestra->nombre_original_muestra."</td>";
														        								echo "<td>".$type[0]."</td>";
														        								echo "<td>".(filesize($ruta)/1000)."KB</td>";
														        								echo "<td>".$muestra->tecnicaEstudio()->first()->descripcion_tecnica_estudio."</td>";
														        								echo "<td>".$muestra->fecha_analisis."</td>";
														        								echo '<td>
														        									<a href="#" class="btn btn-primary verimg" data-visible="true" data-field-url="'.url($rutaweb).'" data-typefile="'.explode('/', $type[0])[0].'"><i class="fa fa-eye"></i></a>
														        								</td>';
														        							echo "</tr>";
											        									}

												        								

												        							}


												        						}
											        							


											        						?>
											        					</tbody>

											        				</table>
											        			</div>
										        			</div>
										        		</div>


									        		@endif
									        		
									        	</div>


									        @elseif( $value['type']=='separador' )


									        @elseif( $value['type']=='titulo' )

									        	<div class="col-md-12 formsptitels"><h2>{{ $value['value'] }}</h2></div>


									        @else

									        	@if(strpos($key, 'estatus')===false)

										        	<?php 

										        		$show=$value['value'];


										        		if (isset($value['options'])) {
										        			
										        			foreach( $value['options'] as $key2 => $value2 ){

										        				if (isset($value['objkeys'])) {

										        					$aux1=$value['objkeys'][0]; 
										        					$aux2=$value['objkeys'][1];

																	if($value['value']==$value2->$aux1){ $show=$value2->$aux2; break 1; }
										        					
										        				}else{
										        					if($value['value']==$key2  && strlen($value['value'])==strlen($key2) ){ $show=$value2; break 1; }
										        				}

										        			}

										        		}

										        	?>

										        	<div class="col-md-6">
										                <label class="col-md-4 control-label" for="{!! $key !!}">{!! $value['label'] !!}: </label>
										                <label class="col-md-8 control-label" style="text-align: left!important;" for="{!! $key !!}"> <strong>{!! $show !!}</strong> </label>
										            </div>

										        @endif

								        	@endif


								       	@endif

							        @endforeach
							    
							    </div>


							@endforeach

							@foreach( $hiddenfields as $key => $value )
								<input type="{!! $value['type'] !!}" id="{!! $value['id'] !!}" name="{!! $key !!}" value="{!! $value['value'] !!}" class="form-control">
							@endforeach

						</div>

			        </div>

					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">

								@if(Auth::user()->id_rol === 2 || Auth::user()->id_rol === 3)

									@if(isset($request->stepform))

										<button class="btn btn-primary" name="lastcallmodal">{!! $request->finlabel !!}</button>
										<button class="btn btn-default dismisslastmodal">Regresar</button>

									@elseif(isset($hiddenfields['report']))

										<button class="btn btn-primary">Generar Reporte</button>
										<button class="btn btn-default modal-dismiss">Cancelar</button>

									@else

										<button class="btn btn-primary" name="finregistro">Finalizar Registro</button>
										<button class="btn btn-default modal-dismiss">Cancelar</button>

									@endif

								@else

									<button class="btn btn-primary modal-dismiss">Regresar</button>

								@endif

							</div>
						</div>
					</footer>

				</div>

			@else

				<div class="mdl-truebody">
					<div class="panel-body ">
						<div class="modal-wrapper">
							<div class="modal-icon">
								<i class="fa fa-warning"></i>
							</div>
							<div class="modal-text">
								<h4 >Alerta!</h4>
								<p>¿Esta seguro que desea eliminar este dato?</p>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button class="btn btn-warning " name="finregistro">SI</button>
								<button class="btn btn-default modal-dismiss">NO</button>
							</div>
						</div>
					</footer>
				</div>

			@endif

	    {!! Form::close() !!}

	    <script type="text/javascript">
	    	
	    	jQuery(document).ready(function() {

	    		$('[data-plugin-selectTwo]').each(function() {
							
					$(this).select2({
						allowClear: true,
						container:'#modalForm .mdl-truebody'
					});
				});


				$('.select2-drop').each(function(){

					$(this).addClass('select2-drop-superindex');

				});

				$('#modalForm .mdl-truebody').on('click' , function() { 
					$('[data-plugin-selectTwo]').each(function() {});
				} );

	    	})

	    </script>

    </section>

