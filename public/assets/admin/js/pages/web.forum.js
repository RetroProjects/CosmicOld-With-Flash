var forums = function() {

    return {
      
        init: function () {
          
            forums.viewForums();
            
            $(".addForum").unbind().click(function () {
                forums.forumManage(null);
            });
          
            $(".addCategory").unbind().click(function () { 
                forums.categoryManage(null);
            });
        },
      
        viewForums: function (){
            var datatableForums = function() {
            if ($('#kt_datatable_forum').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_forum").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/forum/getforums',
                       headers: {'Authorization': 'housekeeping_website_forum' }
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
                   width: 50
               }, {
                   field: "title",
                   title: "Title"
               }, {
                   field: "position",
                   title: "Position"
               }, {
                   field: "min_rank",
                   title: "Min rank",
                    template: function(data) {
                        if(data.min_rank == null) {
                          return '<span class="kt-font">Everyone</span>'
                        } else {
                          return '<span class="kt-font">' + data.cat_name + '</span>'
                        }
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
                      return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="editForum" title="Edit"><i class="flaticon2-edit"></i></a> <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm deleteForum" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="la la-trash"></i></a>'
                  }
              }]
            }), $("#kt_datatable_vpn_reload").on("click", function() {
               $("#kt_datatable_forum").KTDatatable("reload")
            })
            }
            
            datatableForums();
          
            $("#forumTable").unbind().on("click", "#editForum, .deleteForum", function (e) {
                e.preventDefault();
             
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let title = $(e.target).closest('.kt-datatable__row').find('[data-field="title"]').text();
        
                if ($(this).attr("id") == "editForum") {
                    forums.forumManage(id);
                } else {
                    $('#confirm-delete').on('show.bs.modal', function (e) {
                        $(".modal-title").html("Delete " + title);
                        $(".btn-ok").unbind().click(function () {
                            forums.deleteForum(id);
                        });
                    });
                }
            });
          
            $(".getCategory").unbind().click(function () {
              
                $("#kt_datatable_forum").KTDatatable("destroy");
              
                $(".getCategory").html("Forums");
                $(".getCategory").addClass("viewForums");
                $(".getCategory").removeClass("getCategory");
              
                forums.categoryList();
            });
        },
      
        forumManage: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            $("#forumTable").fadeOut();
            $("#editForum").fadeIn();
          
            self.ajax_manager.post("/housekeeping/api/forum/getforumbyid", {post: id}, function (result) {
                for (var x = 0; x < result.categories.length; x++) {
                    var category = result.categories[x];
                    $("#category").append(new Option(category.name, category.id));
                }
              
                for (var i = 0; i < result.ranks.length; i++) {
                    var ranks = result.ranks[i];
                    $("[name=min_rank]").append(new Option(ranks.rank_name, ranks.id));
                }

              
                if (id != null) {
                    $("#category option[value='" + result.cat_id + "']").prop('selected', true);
                    $("[name=min_rank] option[value='" + result.min_rank + "']").prop('selected', true);
                  
                    $("#image-preview").css("background-image", "url(" + result.image + ")");
                  
                    $('[name=forumId]').val(result.id);
                    $('[name=title]').val(result.title);
                    $('[name=description]').val(result.description);
                    $('[name=min_rank]').val(result.min_rank);

                    $('.titleEdit, .editForum').text('Edit Forum');
                } else {
                    $('[name=forumId]').val(0);
                    $('[name=title]').val("");
                    $('[name=description]').val("");
                    $('[name=imagePath]').val("");

                    $('.titleEdit, .addForum').text('Add Forum');
                    $('#image-preview').css("background-image", "none");
                }
            });
          
            $("#goBacks").unbind().click(function () {
                forums.goBack();
            });
        },
      
        goBack: function () {
            $("#editForum").fadeOut();
            $("#forumTable").fadeIn();
            $('[name=forumId]').val(0);
            $('[name=catId]').val(0);
            $("#editCategory").fadeOut();
            
            $('#category').empty();
            $('[name=min_rank]').empty();

            $("#kt_datatable_forum").KTDatatable("reload");
        },
      
        categoryManage: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            $("#forumTable").fadeOut();
            $("#editCategory").fadeIn();
          
            self.ajax_manager.post("/housekeeping/api/forum/getcategorybyid", {post: id}, function (result) {
                for (var i = 0; i < result.ranks.length; i++) {
                    var ranks = result.ranks[i];
                    $("[name=min_rank]").append(new Option(ranks.rank_name, ranks.id));
                }
                 
                if(id != null) {
                    $('[name=catId]').val(result.id);
                    $('[name=title]').val(result.name);
                    $('[name=description]').val(result.description);
                } else {
                    
                    $('[name=catId]').val(0);
                    $('[name=title]').val("");
                    $('[name=description]').val(""); 
                   
                    $('#editCategory .titleEdit').text('Add Category');
                    $("#editCategory .addCategory").text('Add Category');
                }
            });
          
            $("#goBack").unbind().click(function () {
                forums.goBack();
            });
        },
      
        categoryList: function () {
            var datatableCategory = function () {

                if ($('#kt_datatable_forum').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_forum").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/forum/getCategory',
                                headers: {
                                    'Authorization': 'housekeeping_website_forum'
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
                        width: 75
                    }, {
                        field: "name",
                        title: "Category"
                    }, {
                        field: "description",
                        title: "Description"
                    }, {
                        field: "position",
                        title: "Position"
                    }, {
                        field: "Actions",
                        title: "Actions",
                        sortable: !1,
                        overflow: "visible",
                        width: 100,
                        textAlign: "left",
                        autoHide: !1,
                        template: function(data) {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="editCategory" title="Edit"><i class="flaticon2-edit"></i></a> <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm deleteCategory" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="la la-trash"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_faq_reload").on("click", function () {
                    $("#kt_datatable_forum").KTDatatable("reload")
                });
            };

            $("#forumTable").unbind().on("click", "#editCategory, .deleteCategory", function (e) {
                e.preventDefault();
                
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let title = $(e.target).closest('.kt-datatable__row').find('[data-field="name"]').text();

                if ($(this).attr("id") == "editCategory") {
                    forums.categoryManage(id);
                } else {
                    $('#confirm-delete').on('show.bs.modal', function (e) {
                        $(".modal-title").html("Delete " + title);
                        $(".btn-ok").unbind().click(function () {
                            forums.deleteCategory(id);
                        });
                    });
                }
            });

            datatableCategory();
          
            $(".viewForums").unbind().click(function () {

                $("#kt_datatable_forum").KTDatatable("destroy");
              
                $(".viewForums").html("Categories");
                $(".viewForums").addClass("getCategory");
                $(".viewForums").removeClass("viewForums");
              
                forums.viewForums();
            });
        },
      
        deleteForum: function (id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
              
            self.ajax_manager.post("/housekeeping/api/forum/deleteforum", {post: id}, function (result) {
                if(result.status == "success") {
                     $("#kt_datatable_forum").KTDatatable("reload");
                     forums.goBack();
                }
            });
        },
      
        deleteCategory: function (id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
              
            self.ajax_manager.post("/housekeeping/api/forum/deletecategory", {post: id}, function (result) {
                if(result.status == "success") {
                     $("#kt_datatable_forum").KTDatatable("reload");
                     forums.goBack();
                }
            });
        }
      
    }
}();

jQuery(document).ready(function() {
  
    $.uploadPreview({
        input_field: "#image-upload",
        preview_box: "#image-preview",
        label_field: "#image-label"
    });
  
    forums.init();
});