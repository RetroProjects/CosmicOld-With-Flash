var shop = function() {

    return {
        init: function() {
            shop.initDatatable();

            $('#giveOffer').on('show.bs.modal', function(event) {
                shop.giveOffer();
            });

            $(".createOffer").click(function() {
                shop.editOffer(null);
            });

            $("#goBack").unbind().click(function() {
                shop.goBack();
            });

            $(".addVip").click(function() {
                shop.editOffer(null, 'vip');
            });

        },

        editOffer: function(id, type) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            $("#offerManage").show();
            $("#offers").hide();
            $('#jsoneditor').html("");
          
            if (id != null) {
                self.ajax_manager.post("/housekeeping/api/shop/getofferbyid", {
                    post: id
                }, function(result) {
                    result = result.data;

                    $('[name=shopId]').val(result.id);
                    $('[name=title]').val(result.title);
                    $('[name=price]').val(result.price);

                    $(".offerName").html("Modify Offer");

                    if (result.description != '') {
                        tinyMCE.activeEditor.setContent(result.description);
                    }

                    if (result.data != '') {
                        $('[name=data]').val($("#json").html());
                        $('.data').show();

                        const container = document.getElementById("jsoneditor")
                        const options = {};
                        editor = new JSONEditor(container, options)
                        editor.set(JSON.parse(result.data))

                        $(".offerName").click(function() {
                            $("[name=json]").val(JSON.stringify(editor.get()))
                        });
                    }
                });

            } else {

                const container = document.getElementById("jsoneditor")
                const options = {};
                editor = new JSONEditor(container, options)
                tinyMCE.activeEditor.setContent("");

                $('[name=json]').val("");
                $(".offerName").html("Create Offer");
                $('[name=shopId]').val("");
                $('[name=title]').val("");
                $('[name=price]').val("");
                $('[name=data]').val("");
                $('[name=private_key]').val("");
                $('[name=offer_id]').val("");

                
                $(".offerName").click(function() {
                    $("[name=json]").val(JSON.stringify(editor.get()))
                });
            }
        },

        goBack: function() {
            $("#kt_datatable_shop").KTDatatable("reload")

            $("#offerManage").hide();
            $("#offers").show();
        },

        initDatatable: function() {

            var datatableShop = function() {

                if ($('#kt_datatable_shop').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_shop").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/shop/getOffers',
                                headers: {
                                    'Authorization': 'housekeeping_shop_control'
                                }
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
                        input: $("#searchLatestPlayers")
                    },
                    columns: [{
                        field: "id",
                        title: "#",
                        type: "number",
                        width: 75,
                        sortable: "desc"
                    }, {
                        field: "title",
                        title: "Title"
                    }, {
                        field: "price",
                        title: "Price"
                    }, {
                        field: "Action",
                        title: "Action",
                        sortable: !1,
                        width: 110,
                        overflow: "visible",
                        textAlign: "left",
                        autoHide: !1,
                        template: function(data) {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="editOffer" title="Edit"><i class="flaticon2-edit"></i></a> <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm deleteOffer" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="la la-trash"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_shop_reload").on("click", function() {
                    $("#kt_datatable_faq").KTDatatable("reload")
                });
            };

            $("#kt_datatable_shop").unbind().on("click", "#editOffer, .deleteOffer", function(e) {
                e.preventDefault();

                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();

                if ($(this).attr("id") == "editOffer") {
                    shop.editOffer(id);
                } else {
                    $('#confirm-delete').on('show.bs.modal', function(e) {
                        $(".modal-title").html("Delete this offer?");
                        $(".btn-ok").unbind().click(function() {
                            shop.deleteOffer(id);
                        });
                    });
                }
            });

            datatableShop();
        },

        deleteOffer: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/shop/remove", {
                post: id
            }, function(result) {
                if (result.status == "success") {
                    $("#kt_datatable_shop").KTDatatable("reload");
                }
            });
        }
    }
}();

jQuery(document).ready(function() {
    shop.init();
    tinymce.init({
        selector: "textarea",
        width: '100%',
        height: 270,
        plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern imagetools codesample",
        statusbar: true,
        menubar: true,
        toolbar: "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });

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

});
