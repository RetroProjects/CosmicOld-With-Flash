var news = function() {

    return {
        init: function() {
            news.initDatatable();
        },

        initDatatable: function() {
            var datatableNews = function() {
                if ($('#kt_datatable_news').length === 0) {
                    return;
                }

                $("#kt_datatable_news").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/news/getnews',
                                headers: {
                                    'Authorization': 'housekeeping_website_news'
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
                        width: 45,
                        template: function(data) {
                            return '<span class="kt-font">' + data.id + '</span>';
                        }
                    }, {
                        field: "title",
                        title: "Title",
                        width: 200,
                        template: function(data) {
                            return '<span class="kt-font">' + data.title + '</span>';
                        }
                    }, {
                        field: "cat_name",
                        title: "Category",
                        width: 75,
                        template: function(data) {
                            return '<span class="kt-font">' + data.cat_name + '</span>'
                        }
                    }, {
                        field: "author",
                        title: "Author",
                        width: 50,
                        template: function(data) {
                            return '<span class="kt-font">' + data.author + '</span>';
                        }
                    }, {
                        field: "timestamp",
                        title: "Timestsamp",
                        width: 130,
                        template: function(data) {
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
                        template: function() {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="editNews" title="Edit"><i class="flaticon2-edit"></i></a> <a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="deleteNews" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_news_reload").on("click", function() {
                    $("#kt_datatable_news").KTDatatable("reload")
                });
            };

            $("#kt_datatable_news").unbind().on("click", "#editNews, #deleteNews", function(e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let title = $(e.target).closest('.kt-datatable__row').find('[data-field="title"]').text();
              
                if ($(this).attr("id") == "editNews") {
                    news.newsRequest(id);
                } else {
                    $('#confirm-delete').on('show.bs.modal', function(e) {
                    $(".modal-title").html("Delete " + title);
                  
                    $(".btn-ok").click(function () {
                        news.deleteNews(id);
                    });
                });                  
                }
            });

            $(".addNewsPage").unbind().click(function() {
                news.newsRequest(null);
            });

            $(".getCategory").unbind().click(function() {
                news.categoryList();
                $("#newsTable").hide();
                $("#categoryList").show();
            });

            datatableNews();
        },

        categoryList: function() {
            var datatableCategory = function() {

                if ($('#kt_datatable_news_category').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_news_category").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/news/getcategorys',
                                headers: {
                                    'Authorization': 'housekeeping_website_news'
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
                        template: function(data) {
                            return '<span class="kt-font">' + data.id + '</span>';
                        }
                    }, {
                        field: "category",
                        title: "Category",
                        template: function(data) {
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
                        template: function() {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#editCategoryModal" id="editCat" title="Edit"><i class="flaticon2-edit"></i></a> <a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="deleteCat" title="Delete"><i class="flaticon2-trash"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_news_reload").on("click", function() {
                    $("#kt_datatable_news_category").KTDatatable("reload")
                });
            };

            $("#kt_datatable_news_category").unbind().on("click", "#editCat, #deleteCat", function(e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let cat = $(e.target).closest('.kt-datatable__row').find('[data-field="category"]').text();

                if ($(this).attr("id") == "editCat") {
                    $("#ecategoryName").val(cat);
                    
                    $(".editCategory").unbind().click(function() {
                        news.editCategory(id, $("#ecategoryName").val());
                    });
                } else {
                    news.deleteCat(id);
                }
            });

            $(".addCategory").unbind().click(function () {
                news.addCategory($("#categoryName").val());
            });

            $("#goBackCat").unbind().click(function() {
                $("#kt_datatable_news_category").KTDatatable("destroy");
                news.goBack();
            });

            datatableCategory();
        },

        addCategory: function (category) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/news/addcategory", {
                post: category
            }, function (result) {
                if (result.status == "success") {
                    $('#addCategoryModal').modal('toggle');
                    $("#kt_datatable_news_category").KTDatatable("reload");
                }
            });
        },

        editCategory: function (id, category) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
            self.ajax_manager.post("/housekeeping/api/news/editcategory", {
                post: id,
                value: category
            }, function (result) {
                if (result.status == "success") {
                    $('#editCategoryModal').modal('toggle');
                    $("#kt_datatable_news_category").KTDatatable("reload");
                }
            });
        },

        deleteNews: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/news/remove", {
                post: id
            }, function(result) {
                if (result.status == "success") {
                    $("#kt_datatable_news").KTDatatable("reload");
                }
            });
        },

        deleteCat: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/news/removecategory", {
                post: id
            }, function(result) {
                if (result.status == "success") {
                    $("#kt_datatable_news_category").KTDatatable("reload");
                }
            });
        },

        // Add and edit News
        newsRequest: function(id = null) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            $("#editNews").fadeIn();
            $("#newsTable").fadeOut();

            self.ajax_manager.post("/housekeeping/api/news/edit", {
                post: id
            }, function(result) {

                for (var x = 0; x < result.category.length; x++) {
                    var category = result.category[x];
                    $("#category").append(new Option(category.category, category.id));
                }

                if (id != null) {
                    $("#category option[value='" + result.news.category + "']").prop('selected', true);
                    $('[name=newsId]').val(result.news.id);
                    $('[name=title]').val(result.news.title);
                    $('[name=images]').val(result.news.images);
                    $("#image-preview").css("background-image", "url(" + result.news.header + ")");
                    $('[name=short_story]').val(result.news.short_story);
                    tinyMCE.activeEditor.setContent(result.news.full_story);

                    $('.titleNews, .addNews').text('Edit News');
                } else {
                    $('[name=newsId]').val(0);
                    $('[name=title]').val("");
                    $('[name=images]').val("");
                    $('[name=imagePath]').val("");
                    $('[name=short_story]').val("");
                    tinyMCE.activeEditor.setContent("");

                    $('.titleNews, .addNews').text('Add News');
                    $('#image-preview').css("background-image", "none");
                }
            });

            $("#goBack").unbind().click(function() {
                news.goBack();
            });
        },

        goBack: function() {
            $("#editNews").fadeOut();
            $("#newsTable").fadeIn();
            $("#categoryList").fadeOut();

            $('#category').empty()
            $('[name=newsId]').val(0);
            $('[name=title]').removeAttr('value')
            tinyMCE.activeEditor.setContent("");

            $("#kt_datatable_news").KTDatatable("reload");
        }
    }
}();

jQuery(document).ready(function() {
    news.init();

    tinymce.init({
        selector: "textarea",
        width: '100%',
        height: 270,
        plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern imagetools codesample",
        statusbar: true,
        menubar: true,
        toolbar: "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
  
    $.uploadPreview({
        input_field: "#image-upload",
        preview_box: "#image-preview",
        label_field: "#image-label"
    });
});