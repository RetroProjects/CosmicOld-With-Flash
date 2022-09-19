$('#actionModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('id')
  var modal = $(this)

  if(recipient != null) {
      modal.find('.modal-title').text('Action to ' + recipient)
      modal.find('.modal-footer').html('<button type=\"button\" class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#alertModal\" class=\"btn btn-success\" data-id=\"' + recipient + '\">Alert</button><button type=\"button\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#banModal\" data-id=\"' + recipient + '\">Ban</button><a href=\"/housekeeping/remote/user/view/' + recipient + '\" class=\"btn btn-secondary\">Manage User</a>')
      modal.find('.modal-body input').val(recipient)
  }
});

$('#alertModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('id')
  var modal = $(this)

  modal.find('.modal-body #inputUsername').val(recipient);
  
});

$('#banModal').click().on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('id')
  var modal = $(this)

  modal.find('.modal-body #inputUsername').val(recipient);
  
  $.ajax({
      url: '/housekeeping/api/search/banfields',
      type: "post",
      headers: {
          "Authorization": 'housekeeping_remote_control'
      },
      dataType: 'json',
      success: function(data) {
          for (var i = 0; i < data.banmessages.length; i++){
              var parent_page = data.banmessages[i];
              modal.find('[name=reason]').append(new Option(parent_page.message, parent_page.id));
          }  
          for (var x = 0; x < data.bantime.length; x++){
              var parentc_page = data.bantime[x];
              modal.find('[name=expire]').append(new Option(parentc_page.message, parentc_page.id));
          }  
      }
  });
  
});