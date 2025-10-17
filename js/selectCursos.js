$(document).ready(function() {


    $('#radioTPM').click(function () { 
        
        $('#selectTPC').attr("disabled", "");
        $('#TPC').attr("disabled", "");
        $('#horasTPC').attr("disabled", "");

        $('#selectTPCMADERA').attr("disabled", "");
        $('#TPCMADERA').attr("disabled", "");
        $('#horasTPCMADERA').attr("disabled", "");

        $('#selectTPCVIDREO').attr("disabled", "");
        $('#TPCVIDREO').attr("disabled", "");
        $('#horasTPCVIDREO').attr("disabled", "");

        $('#selectOTROS').attr("disabled", "");
        $('#OTROS').attr("disabled", "");
        $('#horasOTROS').attr("disabled", "");

        $('#TPM').removeAttr("disabled");
        $('#selectTPM').removeAttr("disabled");
        $('#horasTPM').removeAttr("disabled");


        
    });

    $('#radioTPC').click(function () {

        $('#selectTPM').attr("disabled", "");
        $('#TPM').attr("disabled", "");
        $('#horasTPM').attr("disabled", "");

        $('#selectTPCMADERA').attr("disabled", "");
        $('#TPCMADERA').attr("disabled", "");
        $('#horasTPCMADERA').attr("disabled", "");

        $('#selectTPCVIDREO').attr("disabled", "");
        $('#TPCVIDREO').attr("disabled", "");
        $('#horasTPCVIDREO').attr("disabled", "");

        $('#selectOTROS').attr("disabled", "");
        $('#OTROS').attr("disabled", "");
        $('#horasOTROS').attr("disabled", "");

        $('#TPC').removeAttr("disabled");
        $('#selectTPC').removeAttr("disabled");
        $('#horasTPC').removeAttr("disabled");

        
    });

    $('#radioTPCMADERA').click(function () { 

        $('#selectTPM').attr("disabled", "");
        $('#TPM').attr("disabled", "");
        $('#horasTPM').attr("disabled", "");

        $('#selectTPC').attr("disabled", "");
        $('#TPC').attr("disabled", "");
        $('#horasTPC').attr("disabled", "");

        $('#selectTPCVIDREO').attr("disabled", "");
        $('#TPCVIDREO').attr("disabled", "");
        $('#horasTPCVIDREO').attr("disabled", "");

        $('#selectOTROS').attr("disabled", "");
        $('#OTROS').attr("disabled", "");
        $('#horasOTROS').attr("disabled", "");

        $('#TPCMADERA').removeAttr("disabled");
        $('#selectTPCMADERA').removeAttr("disabled");
        $('#horasTPCMADERA').removeAttr("disabled");
        
    });

    $('#radioTPCVIDREO').click(function () { 

        $('#selectTPM').attr("disabled", "");
        $('#TPM').attr("disabled", "");
        $('#horasTPM').attr("disabled", "");

        $('#selectTPC').attr("disabled", "");
        $('#TPC').attr("disabled", "");
        $('#horasTPC').attr("disabled", "");

        $('#selectTPCMADERA').attr("disabled", "");
        $('#TPCMADERA').attr("disabled", "");
        $('#horasTPCMADERA').attr("disabled", "");

        $('#selectOTROS').attr("disabled", "");
        $('#OTROS').attr("disabled", "");
        $('#horasOTROS').attr("disabled", "");

        $('#TPCVIDREO').removeAttr("disabled");
        $('#selectTPCVIDREO').removeAttr("disabled");
        $('#horasTPCVIDREO').removeAttr("disabled");

        
    });

    $('#radioOTROS').click(function () { 

        $('#selectTPM').attr("disabled", "");
        $('#TPM').attr("disabled", "");
        $('#horasTPM').attr("disabled", "");

        $('#selectTPC').attr("disabled", "");
        $('#TPC').attr("disabled", "");
        $('#horasTPC').attr("disabled", "");

        $('#selectTPCMADERA').attr("disabled", "");
        $('#TPCMADERA').attr("disabled", "");
        $('#horasTPCMADERA').attr("disabled", "");

        $('#selectTPCVIDREO').attr("disabled", "");
        $('#TPCVIDREO').attr("disabled", "");
        $('#horasTPCVIDREO').attr("disabled", "");

        $('#OTROS').removeAttr("disabled");
        $('#selectOTROS').removeAttr("disabled");
        $('#horasOTROS').removeAttr("disabled");

        
    });


});