$(function() {
    var id = 0;
    var src = '';
    var url = '';

    $('#confirm').on('show.bs.modal', function(e) {
        id = $(e.relatedTarget).data('id');
        src = $(e.relatedTarget).data('source');
        if (src == 'image') {
            url = '/components/deleteImage/' + id;
        } else if(src == 'datasheet') {
            url = '/components/deleteDatasheet/' + id;
        }
    });

    $("#delete").click(function(event) {
        $.get(url, function(data) {
            if (data.result == true) {
                if (src == 'image') {
                    $("#img-thumb").remove();
                }
                if (src == 'datasheet') {
                    $('#ds-thumb').remove();
                }
            }

        })
    });
});