$(document).ready(function() 
{ 
    if($('form#form table td select#items').length)
    {
        // seleccionamos el item que vamos a manipular
        var selectItem      = $('form#form table td select#items');
        
        // creamos una capa que nos servirá como container del select
        var itemContainer   = $('<div id="orderSel">');
        
        // metemos el select dentro del contenedor
        selectItem.wrap(itemContainer);

        // creamos la estructura de botones
        var buttonContainer = $('<div class="buttons">');
        var orderUp         = $('<input type="button" value="Up" class="up" />'); 
        var orderDpwn       = $('<input type="button" value="Down" class="down" />');

        // metemos los botones dentro de la capa contenedora del select
        orderUp.appendTo(buttonContainer);
        orderDpwn.appendTo(buttonContainer);
        buttonContainer.appendTo($('#orderSel'));

        // solo lo usamos como test y comprobar que se realiza el orden correctamente
        //$('#itemsInput').parent().parent().show();

        // inicializamos el evento click para cada uno de los botones
        $('form#form table td .buttons input').click(function()
        {
            // metemos en una variable el/los elemento/s seleccionado/s
            var $op     = $('#items option:selected'),
                $this   = $(this);
        
            // en funcion del valor de cada boton, subimos o bajamos el puesto dentro del select
            if($op.length)
            {
                ($this.val() == 'Up') ? $op.first().prev().before($op) : $op.last().next().after($op);
            }

            // metemos el nuevo orden del select en una variable
            var selectedOptions = $('form#form table td select#items option');
            var selectedValues  = $.map(selectedOptions ,function(option) {return option.value;}).join(',');

            // rellenamos el campo que guarda el orden del select
            $('#itemsInput').val(selectedValues);
        });
    }
});