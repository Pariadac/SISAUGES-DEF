jQuery(document).ready(function() {


    /*Vadilations*/


        /*Campos de Correo*/

        function validarEmail(inp) {
            expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            if ( !expr.test(inp) ){
                
                inp.val('');
                return inp;
            }else{
                return inp;
            }

                
        };

        /*Campos Numericos*/


        function validarNumeros(inp){
            inp.value = (inp.value + '').replace(/[^0-9]/g, '');
            return inp;

        };   


        /*Campos de Caracteres*/

        function validarCaracteres(inp){

            var aux = inp.val().toString();

            if (aux.match(/[^A-Za-z]/g)) {
                aux=aux.replace(/[^A-Za-z]/g, '')
            };

            return aux;

        };

        /*Campos con Limites de Tamaño*/


        function validarLimite(inp,max){

            if (inp.val().length>max) {
                inp.val(inp.val().substr(0,max));
            }

            return inp;

        }

        /*Campos Obligatorios*/

        function validarObligatorio(inp){

            var vl1=validarNumeros(inp);
            var vl2=validarCaracteres(inp);

            if (vl1.val().length==0 && vl2.val().length==0) {
                return 0;
            }else{
                return 1;
            }

        }

    /*Validacion General*/
    
    function validacionGeneral(form){

        $(form+" .form-control").each(function(){

                console.log($(this));

        });

    }    

	
    /*Ajax regular form section request*/

    $('.modalscript').on('click','a.click',function(event){

        event.preventDefault();

        var form=$('#principalform');

        $('#principalform> input[name=typeform]').attr('value',$(this).data('typeform'));
        $('#principalform> input[name=field_id]').attr('value',$(this).data('field-id'));

        var inform= form.serializeArray();

        var taction=form.attr('action').replace('#', $(this).data('taction'));

        var promise=$.ajax({

            url:taction,
            cache: false,
            data:inform,
            type:"POST",
            dataType: "json",
            beforeSend: function(){},
            success:    function(data){

            	if (data.result) {
            		$('#modalForm').empty();
            		$('#modalForm').append(data.html);
            		$('.openmodalbtn').click();
            	}

            },
            error:      function(){}

        });

    }).on('click','a.deleted-row',function(event){

        event.preventDefault();

        var form=$('#principalform');

        $('#modalForm').removeClass('modal-block-danger modal-block-warning modal-block-success  modal-block-primary');

        $('#modalForm').addClass('modal-block-warning');

        $('#principalform> input[name=typeform]').attr('value',$(this).data('typeform'));
        $('#principalform> input[name=field_id]').attr('value',$(this).data('field-id'));

        var inform= form.serializeArray();

        var taction=form.attr('action').replace('#', $(this).data('taction'));

        var promise=$.ajax({

            url:taction,
            cache: false,
            data:inform,
            type:"POST",
            dataType: "json",
            beforeSend: function(){},
            success:    function(data){

                if (data.result) {
                    $('#modalForm').empty();
                    $('#modalForm').append(data.html);
                    $('.openmodalbtn').click();
                }

            },
            error:      function(){}

        });

    });


    /*Ajax modal form section request*/

    $('#modalForm').on('click','button[name=finregistro]',function(event){

        event.preventDefault();

        var form=$('#modalmicroform');

        validacionGeneral('#modalmicroform');

        //

            /*var promise=$.ajax({

                url:form.attr('action'),
                cache: false,
                data:form.serializeArray(),
                type:"POST",
                dataType: "json",
                beforeSend: function(){
                	$('#mdl-truebody').slideUp('fast','swing',function(){
                		$('#modalmicroform > .waitingimg').slideDown('fast','swing');
                	});
                },
                success:    function(data){


                	$('#modalForm').removeClass('modal-block-danger modal-block-warning modal-block-success  modal-block-primary');

                	if (data.resultado=='success') {

                		$('#modalForm').addClass('modal-block-success');

                		$('#result-mdl > div > div > div.modal-icon > i').attr('class','fa fa-check');
                		$('.msn-alerta-header').text('Solicitud completa!');
                		$('.msn-alerta-body').text(data.mensaje);
                		$('#mld-dismiss-fin').attr('class','btn btn-success modal-dismiss');

                	}else{

                		if (data.resultado=='warning'){

                			$('#modalForm').addClass('modal-block-warning');

                			$('#result-mdl > div > div > div.modal-icon > i').attr('class','fa fa-warning');
    	            		$('.msn-alerta-header').text('Alerta!');
    	            		$('.msn-alerta-body').text(data.mensaje);

    	            		$('#mld-dismiss-fin').attr('class','btn btn-warning regresar');

                		}else{

                			$('#modalForm').addClass('modal-block-danger');

                			$('#result-mdl > div > div > div.modal-icon > i').attr('class','fa fa-times-circle');
    	            		$('.msn-alerta-header').text('Ocurrio un error!');
    	            		$('.msn-alerta-body').text(data.mensaje);

    	            		$('#mld-dismiss-fin').attr('class','btn btn-danger regresar');

                		}
                	}

                	setTimeout(function(){

    	            	$('#modalmicroform > .waitingimg').slideUp('fast','swing',function(){
    	            		$('#result-mdl').slideDown('fast','swing');
    	            	});

    	            },1200);
                	
                },
                error:      function(){

                	$('#modalForm').addClass('modal-block-danger');

        			$('#result-mdl > div > div > div.modal-icon > i').attr('class','fa fa-times-circle');
            		$('.msn-alerta-header').text('Ocurrio un error!');
            		$('.msn-alerta-body').text('La solicitud no se pudo completar, recargue la pagina he intente mas tarde...');

            		$('#mld-dismiss-fin').attr('class','btn btn-danger regresar');

            		$('#modalmicroform > .waitingimg').slideUp('fast','swing',function(){
                		$('#result-mdl').slideDown('fast','swing');
                	});

                }

            });*/

        //    

        //Table data update

    });

    /*Ajax table pagination request*/

    $('.paginator').on('click','a',function(event){

        event.preventDefault();

        var form=$('#principalform');

        var promise=$.ajax({

            url:form.attr('action'),
            cache: false,
            data:form.serializeArray(),
            type:"POST",
            dataType: "json",
            beforeSend: function(){},
            success:    function(){},
            error:      function(){}

        });

    });


    /*Extra Modal Functions*/


    $('#modalForm').on('click','button.regresar',function(event){

    	event.preventDefault();

		$('#result-mdl').slideUp('fast','swing',function(){

			$('#modalForm').removeClass('modal-block-danger modal-block-warning modal-block-success  modal-block-primary');

			$('#modalForm').addClass($('#result-mdl').data('tmodalorigin'));

			$('#mdl-truebody').slideDown('fast','swing');

		});   	

    });












    
});
