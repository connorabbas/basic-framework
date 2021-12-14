
// Send data as JSON string
function ajaxGetJSON(postCMD, jsonString, mvcLocation){
    var result = false;
    $.ajax({
        type: "POST",
        url: "ajax",
        data:{cmd:postCMD, mvc:mvcLocation, json:jsonString},
        async: false,
        dataType: "JSON",
        success: function(returnData){
            result = returnData;
        }
    });
    if(result == 'Ivalid Session' || result == false){
        location.reload();
    } else{
        return result;
    }
}

function ajaxGetHTML(postCMD, jsonString, mvcLocation, landingID){
    $.ajax({
        type: "POST",
        url: "ajax",
        data:{cmd:postCMD, mvc:mvcLocation, json:jsonString},
        async: false,
        success: function(returnData){
            if(returnData == 'Ivalid Session'){
                location.reload();
            } else{
                $('#'+landingID).html(returnData);
            }
        }
    });
}