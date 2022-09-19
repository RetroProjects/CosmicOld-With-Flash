var value = function() {
    return {
        init: function () {
          
            $(".catEdit").unbind().click(function() {
                value.catDatatable();
            });
          
            $(".addCat").unbind().click(function() {
                value.addCategory();
            });
          
            $(".editItem").unbind().click(function() {
                value.editItem();
            });
          
            $('.categoryControl').select2({
                placeholder: 'Choose an catalogue page',
                width: "100%",
                ajax: {
                    url: '/housekeeping/search/get/catalogueitem',
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
                    }
                }
            });
          
            value.initDatatable();
        },
      
        addCategory: function () {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            $(".addCategory").unbind().click(function() {
              
                var catalogue_ids = $(".categoryControl").val();
                var category = $("[name=categoryname]").val();
                var is_hidden = $("[name=hidden]").val();
              
                self.ajax_manager.post("/housekeeping/api/value/addCategory", {name: category, cat_ids: catalogue_ids, hidden: is_hidden}, function (result) {
                    if(result.status == "success"){
                        $("#kt_datatable_value").KTDatatable("reload");
                    }
                });
            });
        },

        initDatatable: function () {

            var datatableValue = function () {

                if ($('#kt_datatable_value').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_value").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/value/getCategorys',
                                headers: {
                                    'Authorization': 'housekeeping_website_rarevalue'
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
                        input: $("#generalSearch")
                    },
                    columns: [{
                        field: "id",
                        title: "#",
                        type: "number",
                        width: 40
                    }, {
                        field: "cat_name",
                        title: "Category",
                        width: 120
                    }, {
                        field: "pages",
                        title: "Pages",
                        width: 250
                    }, {
                        field: "discount",
                        title: "Discount",
                        width: 75,
                        template: function(data) {
                            return data.discount + '%';
                        }
                    }, {
                        field: "is_hidden",
                        title: "Is hidden",
                        width: 75,
                        template: function(data) {
                            return (data.is_hidden == 1) ? 'Yes' : 'No';
                        }
                    }, {
                        field: "Actions",
                        title: "Actions",
                        sortable: !1,
                        width: 100,
                        overflow: "visible",
                        textAlign: "left",
                        autoHide: !1,
                        template: function() {
                            return '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm viewRareValue" data-target="viewCategory"><i class="flaticon-edit"></i></a> <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm delteCategory preventDoubleRequest" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_value_reload").on("click", function () {
                    $("#kt_datatable_value").KTDatatable("reload")
                }); 
            };

            $("#kt_datatable_value").unbind().on("click", ".viewRareValue, .delteCategory", function (e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let title = $(e.target).closest('.kt-datatable__row').find('[data-field="cat_name"]').text();
                
                if($(this).data('target') == "viewCategory") {
                    value.getRareValue(id);
                } else {
                    value.deleteCategory(id, title);
                }
            });

            datatableValue();
        },
      
        catDatatable: function() {
            var datatableCategory = function () {
                if ($('#categoryDatatable').length === 0) {
                    return;
                }

                var t;
                $("#categoryDatatable").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/value/getcategorys',
                                headers: {
                                    'Authorization': 'housekeeping_website_rarevalue'
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
                        input: $("#generalSearch")
                    },
                    columns: [{
                        field: "id",
                        title: "#",
                        type: "number",
                        width: 40
                    }, {
                        field: "cat_name",
                        title: "Name",
                    }, {
                        field: "is_hidden",
                        title: "Hidden",
                        template: function (data) {
                            return (data.is_hidden) ? 'No' : 'Yes';
                        }
                    }, {
                        field: "Actions",
                        title: "Actions",
                        sortable: !1,
                        overflow: "visible",
                        textAlign: "left",
                        autoHide: !1,
                        template: function() {
                            return '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm deleteCat preventDoubleRequest" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash"></i></a>'
                        }
                    }]
                });
            };
            
            $("#categoryDatatable").unbind().on("click", ".deleteCat", function (e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let name = $(e.target).closest('.kt-datatable__row').find('[data-field="cat_name"]').text();
              
                value.deleteValueCat(id, name);
            });

            datatableCategory();
          

            if($.trim( $('#categoryDatatable').html() ).length) {
              $("#categoryDatatable").KTDatatable("reload");
            }
        },
      
        deleteCategory: function(id, name) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            $('#categoryEdit').modal('hide');
          
            $('#confirm-delete').on('show.bs.modal', function (e) {
                $("#confirm-delete .modal-title").html("Delete " + name);
                $(".btn-ok").unbind().click(function () {
                    self.ajax_manager.post("/housekeeping/api/value/deleteCategory", {post: id}, function (result) {
                        if(result.status == "success"){
                            $("#kt_datatable_value").KTDatatable("reload");
                        }
                    });
                });
            });
        },
      
        deleteRareValue: function(id, title) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            $('#confirm-delete').on('show.bs.modal', function (e) {
                $("#confirm-delete .modal-title").html("Delete " + title);
                $(".btn-ok").unbind().click(function () {
                    self.ajax_manager.post("/housekeeping/api/value/removeValue", {post: id}, function (result) {
                        if(result.status == "success"){
                            $("#kt_datatable_value").KTDatatable("reload");
                        }
                    });
                });
            });
        },
      
        addRareValue: function() {
            $("#viewRareValue .modal-title").html("Add Rare Value");
            $("#viewRareValue .submitRareValue").html("Add rare");
            $('#rareValueManage').trigger("reset"); 
        },
      
        getRareValue: function(itemId) {     
          
            $("#kt_datatable_value").KTDatatable("destroy");
          
            var datatableValue = function () {

                if ($('#kt_datatable_value').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_value").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/value/getCatalogItems',
                                data: {
                                    id: itemId
                                },
                                headers: {
                                    'Authorization': 'housekeeping_website_rarevalue'
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
                        input: $("#generalSearch")
                    },
                    columns: [{
                        field: "id",
                        title: "#",
                        type: "number",
                        width: 40
                    }, {
                        field: "catalog_name",
                        title: "Item",
                        width: 150
                    }, {
                        field: "cost_credits",
                        title: "Credits",
                    }, {
                        field: "cost_points",
                        title: "Points"
                    }, {
                        field: "Actions",
                        title: "Actions",
                        sortable: !1,
                        width: 110,
                        overflow: "visible",
                        textAlign: "left",
                        autoHide: !1,
                        template: function() {
                            return '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm viewRareValue" data-toggle="modal" data-target="#viewRareValue"><i class="flaticon-edit"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_value_reload").on("click", function () {
                    $("#kt_datatable_value").KTDatatable("reload")
                }); 
            };

            $("#kt_datatable_value").unbind().on("click", ".viewRareValue, .deleteRareValue", function (e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let title = $(e.target).closest('.kt-datatable__row').find('[data-field="swf"]').text();
                
                if($(this).data('target') == "#viewRareValue") {
                    value.getItem(id);
                }
            });

            datatableValue();
        },
      
        getItem: function(id) {
          
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            self.ajax_manager.post("/housekeeping/api/value/getValueById", {post: id}, function (result) {
                $("#viewRareValue .modal-title").html(result.value.catalog_name);
                $("#viewRareValue [name=editItemId]").val(result.value.id);
                $("#viewRareValue [name=cost_credits]").val(result.value.cost_credits);
                $("#viewRareValue [name=cost_points]").val(result.value.cost_points);
                $("#viewRareValue [name=club_only]").val(result.value.club_only);
              
                for (var key in result.currencys){
                    var value = result.currencys[key];
                    $("[name=points_type]").append(new Option(key, value));
                }
                
                $("[name=points_type] option[value='" + result.value.points_type + "']").prop('selected', true);
            });
          
        },
      
        editItem: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
            
            var post_id = $("#viewRareValue [name=editItemId]").val();
            var cost_credits = $("#viewRareValue [name=cost_credits]").val();
            var cost_points = $("#viewRareValue [name=cost_points]").val();
            var club_only = $("#viewRareValue [name=club_only]").val();
            var points_type = $("#viewRareValue [name=points_type]").val();

            self.ajax_manager.post("/housekeeping/api/value/editItem", {id: post_id, cost_credits: cost_credits, cost_points: cost_points, club_only: club_only, points_type: points_type}, function (result) {
                if(result.status == 'success') {
                    $("#kt_datatable_value").KTDatatable("reload");
                  
                    $("#reload-catalog").modal();
                    
                    $('#reload-catalog').on('show.bs.modal', function(e) {
                        $(".reload").unbind().click(function () {
                            self.ajax_manager.post("/housekeeping/api/value/reloadcatalog");
                        });
                    });
                }
            });
        }
    }
}();

jQuery(document).ready(function() {
    value.init();
});