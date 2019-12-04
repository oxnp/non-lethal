$(document).ready(function () {
    $("select[multiple]").chosen({});

    /*Generate pre-activation codes*/
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

    /*Checking import for one product selected*/

    $('button#show_import').click(function (e) {
        let idArray = new Array();
        $('input[name="pre_id"]:checked').each(function () {
            let val = $(this).val();
            idArray.push(val);
        });
        if(idArray.length === 0 || idArray.length > 1){
            alert('Choose one product from list!');
        }else{
            $('#import input[name="product_id"]').val($('input[name="pre_id"]:checked').val());
            $('#import').modal('show');
        }
    });
    $('#import').on('hidden.bs.modal', function () {
        $('form[name="import_codes"] input[name="product_id"]').val('');
    });


    /*Export codes*/
    $('form[name="export_codes"]').submit(function (e) {

        let idArray = new Array();
        $('input[name="precode_id"]:checked').each(function () {
            let val = $(this).val();
            idArray.push(val);
        });
        if(idArray.length === 0){
            alert('Choose one product from list!');
            e.preventDefault();
        }else{
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
        }
    });


    /*Purging and deleting codes*/

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


    /*Transfer licenses*/

    $('button#transfer_lic').click(function (e) {
        let idArray = new Array();
        $('input[name="pre_id"]:checked').each(function () {
            let val = $(this).val();
            idArray.push(val);
        });
        if(idArray.length === 0){
            alert('Choose one product from list!');
        }else{
            $('input[name="pre_id"]:checked').each(function () {
                var val = $(this).val();
                $('form[name="transfer"]').append('<input value="' + val + '" type="hidden" name="licenses_id[]"/>');
            });
            $('#transfer').modal('show');
        }
    });
    $('#transfer').on('hidden.bs.modal', function () {
        $('form[name="transfer"] input[name^="licenses_id"]').remove();
    });


    /*Chosen for one select with search*/
    $('select.chosen_one_search').chosen({
        width: "100%"
    });

    /*Icons tooltips*/
    $('.hasTooltip').tooltip({
        html:true
    });

    /*Wysiwig*/
    $('.summernote').summernote();

    /*License details scripts*/

    let val = $('select[name="license_type"]').val();
    $('div[data-type="'+val+'"]').show();
    $('select[name="license_type"]').change(function(){
       let val = $(this).val();
       $('div[data-type]').hide();
       $('div[data-type="'+val+'"]').show();
    });

    /*Feature pre-activation code settings fields check*/
    $('.item_featured input[type="text"]').keyup(function(){
        $(this).closest('.item_featured').find('button').attr('disabled','disabled');
        $(this).closest('.item_featured').find('button').text('Save data first !');
    });
    $('.item_featured input[name^="feature_prefixes"]').keyup(function(){
        let val = $(this).val();
        if(val.length<5){
            $(this).addClass('is-invalid')
        }else{
            $(this).removeClass('is-invalid')
        }
    });

    /*Add license check*/

    $('a#add_license').click(function(){
        let idArray = new Array();
        $('input[name="precode_id"]:checked').each(function () {
            let val = $(this).val();
            idArray.push(val);
        });
        if(idArray.length === 1){
            window.location.href = '/licenses/create?buyer_id='+$('input[name="precode_id"]:checked').val()+'';
        }else{
            alert('Choose one product from list!');
        }
    })

    /*Prod category select*/
    $('select[name^="category_id"]').change(function () {
        let val = $(this).find('option:selected').attr('data-group');
        $('select[name^="category_id"]').each(function () {
            $(this).val($(this).find('option[data-group="'+val+'"]').val());
        })
    })
    $('select[name^="content_flag"]').change(function () {
        let val = $(this).val();
        $('select[name^="content_flag"]').each(function () {
            $(this).val(val);
        })
    })

})
