jQuery(document).ready(function() {


    var datatableCompare = function() {

        if ($('#kt_datatable_chatlogs').length === 0) {
            return;
        } else {
            if ($.trim($('#kt_datatable_chatlogs').html()).length) {
                $("#kt_datatable_chatlogs").KTDatatable("destroy")
            }
        }

        var t;
        $("#kt_datatable_chatlogs").KTDatatable({
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/housekeeping/api/settings/getcurrencys',
                        headers: {
                            'Authorization': 'housekeeping_config'
                        }
                    }
                },
                pageSize: 10
            },
            layout: {
                scroll: !1,
                footer: !1
            },
            pagination: !0,
            search: {
                input: $("#generalSearch")
            },
            columns: [{
                field: "currency",
                title: "Currency",
                width: 75
            }, {
                field: "type",
                title: "Currency Type"
            }, {
                field: "amount",
                title: "Amount"
            }, {
                field: "Action",
                title: "Action",
                overflow: "visible",
                autoHide: !1,
                template: function() {
                    return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="deleteCurrency" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash"></i></a>'
                }
            }]
        });

        $("#kt_datatable_reload").on("click", function() {
            $("#kt_datatable_chatlogs").KTDatatable("reload")
        });
    };

    datatableCompare();

    $("body").unbind().on("click", "#deleteCurrency", function(e) {
        e.preventDefault();
        let type = $(e.target).closest('.kt-datatable__row').find('[data-field="type"]').text();
        let currency = $(e.target).closest('.kt-datatable__row').find('[data-field="currency"]').text();

        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(".modal-title").html("Delete " + currency);

            $(".btn-ok").click(function() {
                var self = this;
                this.ajax_manager = new WebPostInterface.init();
                self.ajax_manager.post("/housekeeping/api/settings/deletecurrency", {
                    type: type,
                    currency: currency
                }, function(result) {
                    if (result.status == "success") {
                        $("#kt_datatable_chatlogs").KTDatatable("reload");
                    }
                });
            });
        });
    });

    $(".saveCurrency").unbind().click(function() {
        var self = this;
        this.ajax_manager = new WebPostInterface.init();
        self.ajax_manager.post("/housekeeping/api/settings/addcurrency", {
            currency: $("#currency_name").val(),
            type: $("#currency_type").val(),
            amount: $("#currency_amount").val()
        }, function(result) {
            if (result.status == "success") {
                $('#addCategoryModal').modal('toggle');
                $("#kt_datatable_chatlogs").KTDatatable("reload");
            }
        });
    });

    $('.targetRole').select2({
        placeholder: 'Select a role',
        width: '85%',
        ajax: {
            url: '/housekeeping/search/get/role',
            headers: {
                "Authorization": "housekeeping_permissions"
            },
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    // The DOM element you wish to replace with Tagify
    var input = document.querySelector('input[name=vip_badges]');

    // init Tagify script on the above inputs
    new Tagify(input);

    $('.targetCurrency').select2({
        placeholder: 'Select a currency',
        width: '85%',
        ajax: {
            url: '/housekeeping/search/get/currencys',
            headers: {
                "Authorization": "housekeeping_permissions"
            },
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('.targetItems').select2({
        placeholder: 'Select a item',
        width: '85%',
        ajax: {
            url: '/housekeeping/search/get/items',
            headers: {
                "Authorization": "housekeeping_permissions"
            },
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
  
    var self = this;
    this.ajax_manager = new WebPostInterface.init();

var Values = new Array();
Values.push("value1");
Values.push("value2");
Values.push("value3");

$(".targetItems").val(Values).trigger('change');

    tinymce.init({
        selector: "textarea",
        width: '100%',
        height: 270,
        plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern imagetools codesample",
        statusbar: true,
        menubar: true,
        toolbar: "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
});