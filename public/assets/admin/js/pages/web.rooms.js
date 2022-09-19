var roomSearch = function() {

    return {
        init: function() {
            $(document).unbind().on('click', '.editRoom', (e) => {
                e.preventDefault();
              
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                roomSearch.roomRequest(id);
            });    
          
            $(".searchRoom").unbind().click(function() {
                var id = $('.roomControl').select2('data');
                roomSearch.roomRequest(id[0].id);
            });  
        },
      
        roomRequest: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            self.ajax_manager.post("/housekeeping/api/rooms/get", {post: id}, function (result) {
                $("#editRoom").show();
                $("#roomBans").show();
                $("#roomList").hide();

                roomSearch.roomEdit(result);
            });
        },
      
        roomEdit: function(data) {
            var self = this;
          
            this.ajax_manager = new WebPostInterface.init();
          
            $('[name=roomId]').val(data.id);
            $('[name=roomName]').val(data.name);
            $('[name=roomDesc]').val(data.description);
            $('[name=roomOwner]').val(data.owner_name);
            $('[name=accessType]').val(data.state);
            $('[name=maxUsers]').val(data.users_max);
          
            $(".updateRoom").unbind().click(function() {
                var formData = new FormData(document.getElementById("updateRoom"));
              
                self.ajax_manager.post("/housekeeping/api/rooms/update", formData, function (result) {
                    if(result.status == "success") {
                        $("#kt_datatable_roombans").KTDatatable("destroy");
                        $("#kt_datatable_rooms").KTDatatable("reload");
                        roomSearch.loadRooms();
                        roomSearch.goBack();
                    }
                });
            });
          
            $(".goBack").unbind().click(function() {
                roomSearch.goBack();
            });

            var datatableRoomBans = function() {
            if ($('#kt_datatable_roombans').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_roombans").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/rooms/getroombans',
                       params: {
                           "roomId": data.id
                       },
                       headers: {'Authorization': 'housekeeping_room_control' }
                     }
                   },
                   pageSize: 10
               },
               layout: {
                   scroll: !1,
                   footer: !1
               },
               sortable: !0,
               pagination: !0,
               search: {
                   input: $("#generalSearch")
               },
               columns: [{
                   field: "id",
                   title: "#",
                   type: "number",
                   width: 75
               }, {
                   field: "username",
                   title: "Username"
               }, {
                   field: "ends",
                   title: "Expire"
               }, {
                  field: "Action",
                  title: "Action",
                  sortable: !1,
                  width: 110,  
                  overflow: "visible",
                  textAlign: "left",
                  autoHide: !1,
                  template: function(data) {
                      return '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm deleteRoom" title="Delete"><i class="la la-trash"></i></a>'
                  }
              }]
            });

            $("#kt_datatable_roombans_reload").on("click", function() {
               $("#kt_datatable_roombans").KTDatatable("reload")
            })};
            
            datatableRoomBans();
          
            $(document).unbind().on('click', '.deleteRoom', (e) => {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                if (confirm('Are you sure to delete ' + id)) {
                    roomSearch.deleteBan(id);
                }
            }); 
        },
      
        deleteBan: function(rowid) {
            var self = this;
            
            this.ajax_manager = new WebPostInterface.init();
            self.ajax_manager.post("/housekeeping/api/rooms/delete", {id: rowid}, function (result) {
                if(result.status == "success") {
                     $("#kt_datatable_roombans").KTDatatable("reload");
                }
            });
        },
      
        goBack: function () {
            $("#editRoom").hide();
            $("#roomBans").hide();
            $("#roomList").show();
          
            $("#kt_datatable_roombans").KTDatatable("destroy");
          
            roomSearch.init();
        },
      
        loadRooms: function() {
            var self = this;
            
            var datatableRooms = function() {
            if ($('#kt_datatable_rooms').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_rooms").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/rooms/getpopularrooms',
                       headers: {'Authorization': 'housekeeping_room_control' }
                     }
                   },
                   pageSize: 10
               },
               layout: {
                   scroll: !1,
                   footer: !1
               },
               sortable: !0,
               pagination: !0,
               search: {
                   input: $("#generalSearch")
               },
               columns: [{
                   field: "id",
                   title: "#",
                   type: "number",
                   width: 75,
                   template: function(data) {
                       return '<span class="kt-font">' + data.id + '</span>';
                   }
               }, {
                   field: "name",
                   title: "Name",
                   width: 100
               }, {
                   field: "description",
                   title: "Description"
               }, {
                   field: "owner_name",
                   title: "Owner"
               }, {
                   field: "users",
                   title: "Users",
                   sortable: "desc"
               }, {
                  field: "Actions",
                  title: "Actions",
                  sortable: !1,
                  width: 110,  
                  overflow: "visible",
                  textAlign: "left",
                  autoHide: !1,
                  template: function(data) {
                      return '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm editRoom" title="Delete"><i class="la la-edit"></i></a>'
                  }
              }]
            }), $("#kt_datatable_rooms_reload").on("click", function() {
               $("#kt_datatable_rooms").KTDatatable("reload")
            })
            }
            
            datatableRooms();
        }
    }
}();

jQuery(document).ready(function() {
  
    //initialize rooms
    roomSearch.init();
    roomSearch.loadRooms();
  
    $('.roomControl').select2({
          placeholder: 'Select a room',
          width: "90%",
          ajax: {
              url: '/housekeeping/search/get/rooms',
              dataType: 'json',
              headers: {
                  "Authorization": "housekeeping_room_control"
              },
              delay: 250,
              data: function (params) {
                  return {
                      searchTerm: params.term
                  };
              },
              processResults: function (data) {
                  return {
                      results: data
                  };
              },
              cache: true
          }
      });
});