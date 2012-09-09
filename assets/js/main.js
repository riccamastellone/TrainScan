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
    $('.datapicker').datepicker({format: 'yyyy-mm-dd'});
});
   
function getQuotazioni() {
    $('#submitBtn').button('loading');
    $('#loader').show();
    $.post("/main/ajaxQuotazioni", $('#formPost').serialize() ,
        function(data){
            $('#results').html(data);
            $('#submitBtn').button('reset');
            $('#loader').hide();
            $('.dettagliClasse').each(function(){
                $(this).popover({
                    trigger : 'hover',
                    title : $( '.' + $(this).attr('dettagli') + ' .title').html(),
                    content : $( '.' + $(this).attr('dettagli') + ' .content').html()
                });
            })
        });
    
}

