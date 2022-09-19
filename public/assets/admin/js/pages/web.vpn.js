var vpnControl = function() {

    return {
        init: function() {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            $(".banByPlayer").unbind().click(function() {
                var id = $('.chatControl').select2('data');
              
                self.ajax_manager.post("/housekeeping/api/vpn/ban", {id: id[0].id}, function (result) {
                    if(result.status == "success"){
                        $("#kt_datatable_vpn").KTDatatable("reload")
                    }
                });
            });
        },
        loadASNBans: function (){
            var datatableASN = function() {
            if ($('#kt_datatable_vpn').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_vpn").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/vpn/getasnbans',
                       headers: {'Authorization': 'housekeeping_vpn_control' }
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
                   width: 50,
                   template: function(data) {
                       return '<span class="kt-font">' + data.id + '</span>';
                   }
               }, {
                   field: "asn",
                   title: "ASN",
                   // callback function support for column rendering
                   template: function(data) {
                       return '<span class="kt-font">' + data.asn + '</span>';
                   }
               }, {
                   field: "host",
                   title: "Host",
                   template: function(data) {
                       return '<span class="kt-font">' + data.host + '</span>';
                   }
               }, {
                   field: "added_by",
                   title: "Added By",
                   template: function(data) {
                       return '<span class="kt-font">' + data.added_by + '</span>';
                   }
              }, {
                  field: "Action",
                  title: "Action",
                  sortable: !1,
                  width: 110,  
                  overflow: "visible",
                  textAlign: "left",
                  autoHide: !1,
                  template: function(data) {
                      return '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm deleteASN" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="la la-trash"></i></a>'
                  }
              }]
            }), $("#kt_datatable_vpn_reload").on("click", function() {
               $("#kt_datatable_vpn").KTDatatable("reload")
            })
            }
            
            datatableASN();
          
            $("body").unbind().on('click', '.deleteASN', (e) => {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();

                $('#confirm-delete').on('show.bs.modal', function(e) {
                    $(".modal-title").html("Delete Vpn");
                    $(".btn-ok").unbind().click(function () {
                        vpnControl.deleteASN(id);
                    });
                });
            });
        },
      
        deleteASN: function (id) {
            var self = this;
            
            this.ajax_manager = new WebPostInterface.init();
            self.ajax_manager.post("/housekeeping/api/vpn/delete", {asn: id}, function (result) {
                if(result.status == "success") {
                     $("#kt_datatable_vpn").KTDatatable("reload");
                }
            });
        }
      
    }
}();

jQuery(document).ready(function() {
  
    //initialize Vpn
    vpnControl.loadASNBans();
    vpnControl.init();

});