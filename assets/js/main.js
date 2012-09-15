$('.stazioni').typeahead();
$(function() {
    if($('.datapicker').val() == '') {
        var startDate = new Date();
        var month = startDate.getMonth()+1;
        if(month <= '9') 
            var month = '0' + month;
        if(startDate.getDate() <= '9') 
            var day = '0' + startDate.getDate();
        else
            var day = startDate.getDate();
        $('.datapicker').val(startDate.getFullYear() + '-' + month + '-' + day); 
    }
    $('.datapicker').datepicker({format: 'yyyy-mm-dd'}) .on('changeDate', function(){blockRisultati();});
    $('#formPost input').change(function(){blockRisultati();});
    $('.stazioni').change(function(){checkStazioni();})
    
    
});
function blockRisultati() {
    $('#resultsTable').css('opacity',0.5);
    
}
function unBlockRisultati() {
    $('#resultsTable').css('opacity',1);
    $('#loader').hide();
    $('#submitBtn').button('reset');
    
}
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
function checkStazioni() {
    var error = 0;
    var stazioniArray = new Array("Milano", "Bologna", "Firenze", "Roma", "Napoli", "Salerno");
    $('.stazioni').each(function(){
       var stazione = $(this).val();
       if(inArray(stazione,stazioniArray) == false) {
           unBlockRisultati();
           $(this).parent().addClass('error');
           error = 1;
       } else {
           $(this).parent().removeClass('error');
       }
    })
    
    return error;
}
function checkData() {
    var selectedDate = $('.datapicker').val();
    var now = new Date();
    var month = now.getMonth()+1;
    var day = now.getDate();
    var year = now.getFullYear();
    selectedDate = selectedDate.split('-');
    if(selectedDate[0] < year || selectedDate[1] < month || selectedDate[2] < day)  {
      $('.datapicker').parent().addClass('error');
      unBlockRisultati();
      return false;
    } else {
        $('.datapicker').parent().removeClass('error');
        return true;
    }
    
}
function getQuotazioni(deleteCache) {
    blockRisultati();
    if(deleteCache == '1') {
        $('input[name=cache]').val(0);
        $('#newLoader').show();
        $('#newLoaderCall').hide();
    } else {
        $('#submitBtn').button('loading');
        $('#loader').show();
        $('input[name=cache]').val(1);
    }
    
    var error = checkStazioni();
    
    if(checkData() == false)
        error == 1;
    
    
    if(error == 0)
    $.post("/main/ajaxQuotazioni", $('#formPost').serialize() ,
        function(data){
            $('#results').html(data);
            
            $('#newLoader').hide();
            $('#newLoaderCall').show();
            unBlockRisultati();
            $('.dettagliClasse').each(function(){
                $(this).popover({
                    trigger : 'hover',
                    title : $( '.' + $(this).attr('dettagli') + ' .title').html(),
                    content : $( '.' + $(this).attr('dettagli') + ' .content').html()
                });
                
                
                triggerSliders();
            })
        });
    
}
function updateSlider(val1,val2) {
    $( "#amount" ).html( "€" + val1 + " - €" + val2 );
    $('#results tr').each(function(){
        if($(this).attr('costo') > val2 || $(this).attr('costo') < val1) {
            $(this).hide();
        } else $(this).show();
        if ( $("#results tr:visible").length === 1) {
            $('#noresults').show();
        } else {
            $('#noresults').hide();
        }
    })
    
}
function triggerSliders() {
    var valore1 = 0;
    var valore2 = 100;
    $( "#slider" ).slider({
			range: true,
			min: 0,
			max: 200,
			values: [ valore1, valore2 ],
			slide: function( event, ui ) {
				updateSlider(ui.values[ 0 ],ui.values[ 1 ]);
			}
		});
    $( "#amount" ).html( "€" + $( "#slider" ).slider( "values", 0 ) +
                     " - €" + $( "#slider" ).slider( "values", 1 ) );
    updateSlider(valore1,valore2);
}
function showDettagli(idPreventivo) {
    $.get("/main/dettagliPreventivo", {'idPreventivo' : idPreventivo} ,
        function(data){
            $('#modalRiepilogo .modal-body').html(data);
            $('.modal').modal('show');
            $(".accordion").collapse()
        });
    
}
