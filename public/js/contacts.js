var contacts = [];
$(function(){
    var contactsString = $("#contactsInput").val();
    if (undefined != contactsString && contactsString != '' ) {
        contacts = JSON.parse(contactsString);
        displayContacts();
    }

    $( "#contactForm" ).validate( {
        rules: {
            name: {
                required: true,
                maxlength: 256
            },
            phoneNumber: {
                maxlength: 32
            },
            email: {
                email: true,
                maxlength: 256
            },
            job: {
                maxlength: 256
            }
        },
        messages: {
            name: {
                required: "El nombre es obligatorio.",
                maxlength: "La longitud del nombre debe ser menor de 256 caracteres."
            },
            phoneNumber: {
                maxlength: "La longitud del número telefónico debe ser menor de 32 caracteres."
            },
            email: {
                email: "Formato de correo electrónico incorrecto.",
                maxlength: "La longitud del correo electrónico debe ser menor de 256 caracteres."
            },
            job: {
                maxlength: "La longitud del puesto debe ser menor de 256 caracteres."
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


    $("#addContact").click(function(){
        var contact = {
            "name" : $("#name").val(),
            "phoneNumber" : $("#phoneNumber").val(),
            "email" : $("#email").val(),
            "job" : $("#job").val(),
        };

        if ($( "#contactForm" ).valid()) {
            contacts.push(contact);
            updateContacts();
            clearForm();
            $('#contactModal').modal('hide');
        }
        return false;
    });
});

function clearForm()
{
    $("#name").val('');
    $("#phoneNumber").val('');
    $("#email").val('');
    $("#job").val('');
}

function updateContacts()
{
    $("#contactsInput").val(JSON.stringify(contacts));
    displayContacts();
}

function displayContacts()
{

    var cardBegin = '<div class="col-sm-12">';
    cardBegin += '<div class="card border-danger mb-3" style="max-width: 18rem;">';
    cardBegin += '<div class="card-body">';

    var cardEnd =  '</div></div></div>';

    var buffer = '<div class="row">';
    for(var index = 0; index < contacts.length; index++) {
        var data = contacts[index];
        var bufferData = '<button type="button" onclick="deleteContact(' + index + ');" class="close float-right" aria-label="Close">';
        bufferData += '<span aria-hidden="true">&times;</span>';
        bufferData += '</button>';
        bufferData += '<div><b>Nombre: </b>' + data.name + '</div>';
        bufferData += '<div><b>Teléfono: </b>' + data.phoneNumber + '</div>';
        bufferData += '<div><b>Email: </b>' + data.email + '</div>';
        bufferData += '<div><b>Puesto: </b>' + data.job + '</div>';

        buffer += cardBegin + bufferData + cardEnd;
    }
    
    buffer += '</div>';

    $("#contacts").html(buffer);
}


function deleteContact(index)
{
    contacts.splice(index, 1);
    updateContacts();
}
