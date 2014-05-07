jQuery(document).ready(function($) {
	

	/**
	 * Tabs
	 */
	$('#myTabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});


	/**
	 * Other Tabs New
	 */
	
	var tabContainerssup = $('.rm-tab-wrap > div');
	// Convertimos una ruta en una variable, así la llamada a esa ruta será más fácil

	$('.rm-tab-menu li a').click(function () {
	// ahora le estamos diciendo que active la siguiente 
	// función cada vez que clickamos dentro de los a situados dentro del div tab
	
	$(this).parent().parent().find('li').removeClass('active');

	tabContainerssup.hide().filter(this.hash).show();

	$(this).parent().addClass('active');
	// con la variable que hemos creado antes, le decimos que oculte todo su contenido, y que sólo muestre el contenido del anchor que hemos clickado

        	return false;
	// ponemos esta linia para que no se nos desplace al hacer click arriba de la página

	}).filter(':first').click();

	/*********************************
	 * INPUTS 
	 */

	/**
	 * select-bs
	 */

	//$('.selectpicker').selectpicker();


	/**
     * Radio
     *
     * Hacer que los botones funcionen como radio
     *
     * @url http://dan.doezema.com/2012/03/twitter-bootstrap-radio-button-form-inputs/
     */
    $('div.btn-group[data-toggle=buttons-radio]').each(function(){

		var group   = $(this);
		var name    = group.attr('data-toggle-name');
		var hidden  = $('input[name="' + name + '"]');

		$('button', group).each(function(){

			var button = $(this);

			button.live('click', function(){

				hidden.val($(this).val());

			});


			if(button.val() == hidden.val()) {

				button.addClass('active');

			}

		});

	});

	/**
	 * Data Range Picker
	 *
	 * Seleccion para rangos de fechas
	 *
	 * @url
	 */

	if( $('.datarangepicker').length > 0 ){


		    $('.datarangepicker').daterangepicker({ 
		        format: 'YYYY-MM-DD',
		        startDate: moment().format('YYYY-MM-DD'),
		        endDate: moment().add('days', 30).format('YYYY-MM-DD'),
		        minDate: moment().format('YYYY-MM-DD'),
		        locale: {
		            applyLabel: 'Aplicar',
		            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
		            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		            firstDay: 1
		        },
		        buttonClasses: ['btn-danger'],
		        opens: function(){return 'right';},
		        separator: ' a '
		    });

	}


	/**
	 * Data Picker Jquery ui
	 *
	 * Seleccionar rangos de fecha pero individualmente
	 *
	 * @url http://jqueryui.com/datepicker/#date-range
	 */

	//Conseguir fecha inicial
	function set_date(){

	}

	/**
	 * [diff_date description]
	 * @param  {[type]} start [description]
	 * @param  {[type]} end   [description]
	 * @return {[type]}       [description]
	 */
	function diff_date(start, end){
		start_date = new Date(start);
		end_date = new Date(end);

		diff = new Date(end_date - start_date);
		return dias_diferencia =  days = diff/1000/60/60/24;
	}
	 
	$( ".datapicker-from" ).datepicker({
    	dateFormat: "dd-mm-yy",
		altFormat: "yy-mm-dd",
		altField: ".datapickeralt-from",
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 2,
      minDate: '0',
      onClose: function( selectedDate ) {
      	val_diferencia_dias = diff_date($( ".datapickeralt-from" ).val(), $( ".datapickeralt-to" ).val());
      	$('#nota_noches').val(val_diferencia_dias);
        $( ".datapicker-to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( ".datapicker-to" ).datepicker({
    	dateFormat: "dd-mm-yy",
		altFormat: "yy-mm-dd",
		altField: ".datapickeralt-to",
      defaultDate: "+3w",
      changeMonth: true,
      numberOfMonths: 2,
      onClose: function( selectedDate ) {
      	val_diferencia_dias = diff_date($( ".datapickeralt-from" ).val(), $( ".datapickeralt-to" ).val());
      	$('#nota_noches').val(val_diferencia_dias);
        $( ".datapicker-from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });



	 /**
     * @name Sorteable de elementos repetitivos
     *
     * Acomodar los elementos dependiendo la importancia.
     */
     var info;
    $(".content-repeat-elements").sortable({
        items: ".repeat-element",
        handle: ".top-repeat-element",
        update: function(event, ui) {
        	console.log("hol");
            var info = $(".content-repeat-elements").sortable("serialize");
            console.log(info);   
        }
    });


    


    /**
	 * ddSlick Selector
	 *
	 * Select - Option Moderno
	 *
	 * @url http://designwithpc.com/Plugins/ddSlick
	 */

	$('.ddSlick').ddslick({
	    onSelected: function(selectedData){
	        //callback function: do something with selectedData;
	    }   
	});


});