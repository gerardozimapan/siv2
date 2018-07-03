var bankAccounts = [];
$(function(){
    var bankAccountsString = $("#bankAccountsInput").val();
    if (undefined != bankAccountsString && bankAccountsString != '' ) {
        bankAccounts = JSON.parse(bankAccountsString);
        displayBankAccounts();
    }

    $( "#bankAccountForm" ).validate( {
        rules: {
            bank: {
                required: true,
                maxlength: 128
            },
            number: {
                required: true,
                maxlength: 128
            },
            clabe: {
                digits: true,
                minlength: 18,
                maxlength: 18
            },
            currency: "required"
        },
        messages: {
            bank: {
                required: "El banco es obligatorio.",
                maxlength: "La longitud del banco debe ser menor de 128 caracteres."
            },
            number: {
                required: "La cuenta en obligatoria.",
                maxlength: "La longitud de la cuenta debe ser menor de 128 caracteres."
            },
            clabe: {
                digits: "La cuenta clabe debe contener solamente números",
                minlength: "La cuenta clabe debe contener 18 dígitos",
                maxlength: "La cuenta clabe debe contener 18 dígitos"
            },
            currency: {
                required: "Debe seleccionar una moneda."
            },
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            if ( element.prop( "type" ) === "radio" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
        }
    } );


    $("#add").click(function(){
        var bankAccount = {
            "bank" : $("#bank").val(),
            "number" : $("#number").val(),
            "clabe" : $("#clabe").val(),
            "currency" : {
                'id' : $('input[name=currency]:checked').val(),
                'code' : $('input[name=currency]:checked').parent().text()
            }
        };

        if ($( "#bankAccountForm" ).valid()) {
            bankAccounts.push(bankAccount);
            updateBankAccounts();
            clearForm();
            $('#bankAccountModal').modal('hide');
        }
        return false;
    });
});

function clearForm()
{
    $("#bank").val('');
    $("#number").val('');
    $("#clabe").val('');
    $("input[name=currency]:checked").prop( "checked", false );
}

function updateBankAccounts()
{
    $("#bankAccountsInput").val(JSON.stringify(bankAccounts));
    displayBankAccounts();
}

function displayBankAccounts()
{

    var cardBegin = '<div class="col-sm-4">';
    cardBegin += '<div class="card border-danger mb-3" style="max-width: 18rem;">';
    cardBegin += '<div class="card-body">';

    var cardEnd =  '</div></div></div>';

    var buffer = '<div class="row">';
    for(var index = 0; index < bankAccounts.length; index++) {
        var data = bankAccounts[index];
        var bufferData = '<button type="button" onclick="deleteBankAccount(' + index + ');" class="close float-right" aria-label="Close">';
        bufferData += '<span aria-hidden="true">&times;</span>';
        bufferData += '</button>';
        bufferData += '<div><b>Banco: </b>' + data.bank + '</div>';
        bufferData += '<div><b>Cuenta: </b>' + data.number + '</div>';
        bufferData += '<div><b>CLABE: </b>' + data.clabe + '</div>';
        bufferData += '<div><b>Moneda: </b>' + data.currency.code + '</div>';

        buffer += cardBegin + bufferData + cardEnd;
    }
    
    buffer += '</div>';

    $("#bankAccounts").html(buffer);
}


function deleteBankAccount(index)
{
    bankAccounts.splice(index, 1);
    updateBankAccounts();
}
