var faq = function() {

    return {
        init: function () {
            faq.initDatatable();
        },

        initDatatable: function () {

            var datatableFaqs = function () {

                if ($('#kt_datatable_faq').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_faq").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/faq/getfaqs',
                                headers: {
                                    'Authorization': 'housekeeping_website_faq'
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
                        input: $("#faq_search")
                    },
                    columns: [{
                        field: "id",
                        title: "#",
                        type: "number",
                        width: 40,
                        template: function (data) {
                            return '<span class="kt-font">' + data.id + '</span>';
                        }
                    }, {
                        field: "title",
                        title: "Title",
                        width: 200,
                        template: function (data) {
                            return '<span class="kt-font">' + data.title + '</span>';
                        }
                    }, {
                        field: "cat_name",
                        title: "Category",
                        width: 200,
                        template: function (data) {
                            return '<span class="kt-font">' + data.cat_name + '</span>'
                        }
                    }, {
                        field: "author",
                        title: "Author",
                        width: 75,
                        template: function (data) {
                            return '<span class="kt-font">' + data.author + '</span>';
                        }
                    }, {
                        field: "timestamp",
                        title: "Timestsamp",
                        width: 130,
                        template: function (data) {
                            return '<span class="kt-font">' + data.timestamp + '</span>';
                        }
                    }, {
                        field: "Actions",
                        title: "Actions",
                        sortable: !1,
                        width: 110,
                        overflow: "visible",
                        textAlign: "left",
                        autoHide: !1,
                        template: function () {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="editFaq" title="Edit"><i class="flaticon2-edit"></i></a> <a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="deleteFaq" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_faq_reload").on("click", function () {
                    $("#kt_datatable_faq").KTDatatable("reload")
                });
            };

            $("#kt_datatable_faq").unbind().on("click", "#editFaq, #deleteFaq", function (e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let title = $(e.target).closest('.kt-datatable__row').find('[data-field="title"]').text();

                if ($(this).attr("id") == "editFaq") {
                    faq.faqRequest(id);
                } else {
                    $('#confirm-delete').on('show.bs.modal', function (e) {
                        $(".modal-title").html("Delete " + title);
                        $(".btn-ok").unbind().click(function () {
                            faq.deleteFaq(id);
                        });
                    });
                }
            });

            $(".addFaqPage").unbind().click(function () {
                faq.faqRequest(null);
            });

            $(".getCategory").unbind().click(function () {
                faq.categoryList();
                $("#faqTable").hide();
                $("#categoryList").show();
            });

            datatableFaqs();

        },

        categoryList: function () {
            var datatableCategory = function () {

                if ($('#kt_datatable_faq_category').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_faq_category").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/faq/getcategorys',
                                headers: {
                                    'Authorization': 'housekeeping_website_faq'
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
                        template: function (data) {
                            return '<span class="kt-font">' + data.id + '</span>';
                        }
                    }, {
                        field: "category",
                        title: "Category",
                        template: function (data) {
                            return '<span class="kt-font">' + data.category + '</span>';
                        }
                    }, {
                        field: "Actions",
                        title: "Actions",
                        sortable: !1,
                        overflow: "visible",
                        width: 100,
                        textAlign: "left",
                        autoHide: !1,
                        template: function () {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#editCategoryModal" id="editCat" title="Edit"><i class="flaticon2-edit"></i></a> <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#confirm-delete" id="deleteCat" title="Delete"><i class="flaticon2-trash"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_faq_reload").on("click", function () {
                    $("#kt_datatable_faq_category").KTDatatable("reload")
                });
            };

            $("#kt_datatable_faq_category").unbind().on("click", "#editCat, #deleteCat", function (e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let cat = $(e.target).closest('.kt-datatable__row').find('[data-field="category"]').text();

                if ($(this).attr("id") == "editCat") {
                    $("[name=editCategoryName]").val(cat);

                    $(".editCategory").unbind().click(function () {
                        faq.editCategory(id, $("[name=editCategoryName]").val());
                    });
                } else {
                    $('#confirm-delete').on('show.bs.modal', function (e) {
                        $(".modal-title").html("Delete " + cat);
                        $(".btn-ok").unbind().click(function () {
                            faq.deleteCat(id);
                        });
                    });
                }
            });

            $(".addCategory").unbind().click(function () {
                faq.addCategory($("[name=categoryName]").val());
            });

            $("#goBackCat").unbind().click(function () {
                $("#kt_datatable_faq_category").KTDatatable("destroy");
                faq.goBack();
            });

            datatableCategory();
        },

        addCategory: function (category) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/faq/addcategory", {
                post: category
            }, function (result) {
                if (result.status == "success") {
                    $('#addCategoryModal').modal('toggle');
                    $("#kt_datatable_faq_category").KTDatatable("reload");
                }
            });
        },

        editCategory: function (id, category) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/faq/editcategory", {
                category: id,
                value: category
            }, function (result) {
                if (result.status == "success") {
                    $('#editCategoryModal').modal('toggle');
                    $("#kt_datatable_faq_category").KTDatatable("reload");
                }
            });
        },

        deleteFaq: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/faq/remove", {
                post: id
            }, function(result) {
                if (result.status == "success") {
                    $("#kt_datatable_faq").KTDatatable("reload");
                }
            });
        },

        deleteCat: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/faq/removecategory", {
                post: id
            }, function(result) {
                if (result.status == "success") {
                    $("#kt_datatable_faq_category").KTDatatable("reload");
                }
            });
        },

        goBack: function () {
            $("#editFaq").fadeOut();
            $("#faqTable").fadeIn();
            $("#categoryList").fadeOut();

            $('#category').empty()
            $('[name=faqId]').val(0);
            $('[name=title]').removeAttr('value')
            tinyMCE.activeEditor.setContent("");

            $("#kt_datatable_faq").KTDatatable("reload");
        },

        // Add and edit FAQ
        faqRequest: function (id = null) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            $("#editFaq").fadeIn();
            $("#faqTable").fadeOut();

            self.ajax_manager.post("/housekeeping/api/faq/edit", {
                post: id
            }, function (result) {

                for (var x = 0; x < result.category.length; x++) {
                    var category = result.category[x];
                    $("#category").append(new Option(category.category, category.id));
                }

                if (id != null) {
                    $("#category option[value='" + result.faq.category + "']").prop('selected', true);
                    $('[name=faqId]').val(result.faq.id);
                    $('[name=title]').val(result.faq.title);
                    tinyMCE.activeEditor.setContent(result.faq.desc);

                    $('.titleFaq, .addFaq').text('Edit FAQ');
                } else {
                    $('[name=faqId]').val(0);
                    $('[name=title]').val("");
                    tinyMCE.activeEditor.setContent("");

                    $('.titleFaq, .addFaq').text('Add FAQ');
                }

            });

            $("#goBack").unbind().click(function () {
                faq.goBack();
            });
        }
    }
}();

jQuery(document).ready(function() {
    faq.init();

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