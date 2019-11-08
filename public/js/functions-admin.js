$(document).ready(function () {
    $("select[multiple]").chosen({});
    $('button#show_gen').click(function (e) {
        $('input[name="pre_id"]:checked').each(function () {
            var val = $(this).val();
            $('form[name="gen_precodes"]').append('<input value="' + val + '" type="hidden" name="cid[]"/>');
        });
        if($('form[name="gen_precodes"] input[name^="cid"]').length > 0){
            $('#gencodes').modal('show');
        }else{
            alert('Choose some products first!')
        }
    });
    $('#gencodes').on('hidden.bs.modal', function () {
        $('form[name="gen_precodes"] input[name^="cid"]').remove();
    });
    $('form[name="export_codes"]').submit(function (e) {
        $('input[name="precode_id"]:checked').each(function () {
            var val = $(this).val();
            $('form[name="export_codes"]').append('<input value="' + val + '" type="hidden" name="cid[]"/>');
        })
        var status = confirm("Click OK to continue?");
        if(status == false){
            $('form[name="export_codes"] input[name^="cid"]').remove();
            return false;
        }
        else{
            return true;
        }
    });
    $('form[name="purge_codes"]').submit(function (e) {
        var status = confirm("Click OK to continue?");
        if(status == false){
            return false;
        }
        else{
            return true;
        }
    })
    $('form[name="delete_codes"]').submit(function (e) {
        $('input[name="precode_id"]:checked').each(function () {
            var val = $(this).val();
            $('form[name="delete_codes"]').append('<input value="' + val + '" type="hidden" name="cid[]"/>');
        })
        var status = confirm("Click OK to continue?");
        if(status == false){
            $('form[name="export_codes"] input[name^="cid"]').remove();
            return false;
        }
        else{
            return true;
        }
    });
})
