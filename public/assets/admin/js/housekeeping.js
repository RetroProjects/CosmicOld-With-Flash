$('#uploadModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var image = button.data('image')
    var path = button.data('path')
    var route = button.data('route')

    var modal = $(this)
    
    if(recipient != null) {
      
        $("#uploadImage").attr("src", image)
        $("#path").attr("value", path)

        modal.find('.modal-title').text('Upload forum image')
        modal.find('.modal-footer').html('<a onclick="uploadFile(' + recipient + ', \'' + path + '\', \'' + route + '\');" class=\"btn btn-success\">Save</a>')
    }
});

$('#deletePermissionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var value = button.data('value')
    var role = button.data('role')
    var modal = $(this)

    if(id != null) {
        modal.find('.modal-title').text('Action to ' + value)
        modal.find('.modal-footer').html('<a href=\"/housekeeping/manage/role/' + role + '/users\" class=\"btn btn-success\">See all users</a><a href=\"/housekeeping/manage/permissions/' + id + '/rank/' + role + '/delete\" class=\"btn btn-danger\">Delete</a>');
        modal.find('.modal-body input').val(id)
    }

});

$('#websiteAlertModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    $("#inputUsername").val(id);
});

$('#reportModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var id = button.data('value')
    var type = button.data('type')
    var item = button.data('item')
    var status = button.data('status');
    var callback = button.data('callback');

    var modal = $(this)
    
    if(recipient != null) {
        $(".showimagediv").empty();
        
        if(callback == "photo") {
            var img = $('<img />', {
                src     : Site.story_path + "/photos/" + item + ".png",
                'class' : 'fullImage'
            });
          $('.showimagediv').html(img).show();
        } else {
            if(type == "photo") {
                modal.find('.modal-title').text('Action to ' + recipient)
                if(status == "open") {
                    modal.find('.modal-footer').html('<a href=\"/housekeeping/reports/photo/' + id + '/type/' + type + '/itemid/' + item + '\" class=\"btn btn-danger\"\">Delete Photo</a><a href=\"/housekeeping/reports/close/' + id + '/type/' + type + '/itemid/' + item + '\" class=\"btn btn-secondary\">Close report</a>')
                }  
            } else {
                modal.find('.modal-title').text('Action to ' + recipient)
                if(status == "open") {
                    modal.find('.modal-footer').html('<a href=\"/housekeeping/reports/hide/' + id + '/type/' + type + '/itemid/' + item + '\" class=\"btn btn-danger\"\">Disable Reaction</a><a href=\"/housekeeping/reports/close/' + id + '/type/' + type + '/itemid/' + item + '\" class=\"btn btn-secondary\">Close report</a>')
                } else if(status == "hidden") {
                    modal.find('.modal-footer').html('<a href=\"/housekeeping/reports/enable/' + id + '/type/' + type + '/itemid/' + item + '\" class=\"btn btn-danger\"\">Enable Reaction</a><a href=\"/housekeeping/reports/close/' + id + '/type/' + type + '/itemid/' + item + '\" class=\"btn btn-secondary\">Close report</a>')
                } else if(status == "closed") {
                    modal.find('.modal-footer').html('<a href=\"/housekeeping/reports/open/' + id + '/type/' + type + '/itemid/' + item + '\" class=\"btn btn-secondary\">Open report</a>')
                }
                modal.find('.modal-body input').val(recipient)        
            }
        }
    }
});

$('#actionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    if(recipient != null) {
        modal.find('.modal-title').text('Action to ' + recipient)
        modal.find('.modal-footer').html('<a href=\"#\" data-toggle=\"modal\" data-target=\"#alertModal\" class=\"btn btn-success\" data-id=\"' + recipient + '\">Alert</a><a href=\"#\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#banModal\" data-id=\"' + recipient + '\">Ban</a><a href=\"/housekeeping/remote/user/' + recipient + '\" class=\"btn btn-secondary\">Manage User</a>')
        modal.find('.modal-body input').val(recipient)
    }
});

$('#actionModalNamechange').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var bodytext = button.data('value')
    var modal = $(this)

    if(recipient != null) {
        modal.find('.modal-title').text('Action to ' + recipient)
        modal.find('.modal-body').html(bodytext)
        modal.find('.modal-footer').html('<a href=\"#\" data-toggle=\"modal\" data-target=\"#alertModal\" class=\"btn btn-success\" data-id=\"' + recipient + '\">Alert</a><a href=\"#\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#banModal\" data-id=\"' + recipient + '\">Ban</a><a href=\"/housekeeping/remote/user/' + recipient + '\" class=\"btn btn-secondary\">Manage User</a>')
        modal.find('.modal-body input').val(recipient)
    }
});

$('#unbanModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var player = button.data('value')
    var modal = $(this)

    if(recipient != null) {
        modal.find('.modal-title').text('Are you sure to unban ' + player + '?')
        modal.find('.modal-footer').html('<a href=\"/housekeeping/remote/user/' + player + '\" class=\"btn btn-secondary\">Manage User</a> <a href=\"/housekeeping/remote/user/' + recipient + '/unban"\" class=\"btn btn-success\">Unban</a>')
        modal.find('.modal-body input').val(recipient)
    }
});

$('#alertModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #inputUsername').val(recipient)
});

$('#banModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #inputUsername').val(recipient)
});

$('#manageModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #inputUsername').val(recipient)
});

$('#resetModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #inputUsername').val(recipient)
});