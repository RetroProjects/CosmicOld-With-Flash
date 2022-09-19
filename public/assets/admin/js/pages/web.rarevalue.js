var rarevalue = function() {

    return {
        init: function() {        
            $(".addPage").unbind().click(function() {
				$('[name=pageId]').val("");
                    $('[name=name]').val("");
                    $('[name=description]').val("");
                    $('[name=thumbnail]').val("");
				$(".modal-title").html("Add page ");
					$(".editPage").unbind().click(function() {
                        rarevalue.addPage($("#epageId").val(), $("#ePageName").val(), $("#ePageDesc").val(), $("#ePageThumb").val());
                    });
            });
			
			$(".addItem").unbind().click(function() {
				$('[name=itemparentid]').val("");
                    $('[name=itemname]').val("");
                    $('[name=itembase]').val(0);
                    $('[name=itemcredits]').val(0);
                    $('[name=itempoints]').val(0);
                    $('[name=itemtype]').val(5);
                    $('[name=itemimage]').val("");
				$(".modal-titles").html("Add Item ");
					$(".editItem").unbind().click(function() {
                        rarevalue.addItem($("#eparentId").val(), $("#eItemName").val(), $("#eItemId").val(), $("#eItemCredits").val(), $("#eItemPoints").val(), $("#eItemType").val(), $("#eImageItem").val());
                    
                    });
            });
        },
      
        addPage: function(newid, name, desc, thumb) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            self.ajax_manager.post("/housekeeping/api/RareValue/addpage", {newid: newid, name: name, desc: desc, thumb: thumb}, function (result) {
                if(result.status == "success") {
					$('#editPageModal').modal('toggle');
					
                    $("#kt_datatable_rarevalue").KTDatatable("reload")
                }
            });
        },
		
      editPage: function (id, name, desc, thumb, newid) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
            self.ajax_manager.post("/housekeeping/api/RareValue/addpage", {
                pageid: id,
                name: name,
                desc: desc,
                thumb: thumb,
				newid: newid
            }, function (result) {
                if (result.status == "success") {
                    $('#editPageModal').modal('toggle');
                    $("#kt_datatable_rarevalue").KTDatatable("reload");
                }
            });
        },
		addItem: function(parent_id, name, item_id, cost_credits, cost_points, points_type, image) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            self.ajax_manager.post("/housekeeping/api/RareValue/additem", {
				parent_id: parent_id,
                name: name,
                item_id: item_id,
                cost_credits: cost_credits,
                cost_points: cost_points,
                points_type: points_type,
				image: image}, function (result) {
                if(result.status == "success") {
                    $("#kt_datatable_rarevalueitems").KTDatatable("reload");
					$('#editItemModal').modal('toggle');
                }
            });
        },
		editItem: function (id, parent_id, name, item_id, cost_credits, cost_points, points_type, image) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
            self.ajax_manager.post("/housekeeping/api/RareValue/additem", {
                id: id,
                parent_id: parent_id,
                name: name,
                item_id: item_id,
                cost_credits: cost_credits,
                cost_points: cost_points,
                points_type: points_type,
				image: image
            }, function (result) {
                if (result.status == "success") {
                    $('#editItemModal').modal('toggle');
                    $("#kt_datatable_rarevalueitems").KTDatatable("reload");
                }
            });
        },
		
        deletePage: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/RareValue/removepage", {post: id}, function (result) {
                if(result.status == "success") {
                    $("#kt_datatable_rarevalue").KTDatatable("reload");
                }
            });
        }, 
		deleteItem: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/RareValue/removeitem", {post: id}, function (result) {
                if(result.status == "success") {
					$("#kt_datatable_rarevalueitems").KTDatatable("reload");
                }
            });
        },
		itemRequest: function(id = null) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/RareValue/edititem", {
                post: id
            }, function(result) {
                if (id != null) {
                    $('[name=itemparentid]').val(result.rarevalueitem.parent_id);
                    $('[name=itemname]').val(result.rarevalueitem.name);
                    $('[name=itembase]').val(result.rarevalueitem.item_id);
                    $('[name=itemcredits]').val(result.rarevalueitem.cost_credits);
                    $('[name=itempoints]').val(result.rarevalueitem.cost_points);
                    $('[name=itemtype]').val(result.rarevalueitem.points_type);
                    $('[name=itemimage]').val(result.rarevalueitem.image);
                } else {
                   $('[name=itemparentid]').val("");
                    $('[name=itemname]').val("");
                    $('[name=itembase]').val(0);
                    $('[name=itemcredits]').val(0);
                    $('[name=itempoints]').val(0);
                    $('[name=itemtype]').val(5);
                    $('[name=itemimage]').val("");
                }
            });
        },
		
		  pageRequest: function(id = null) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/RareValue/editpage", {
                post: id
            }, function(result) {
                if (id != null) {
                    $('[name=pageId]').val(result.rarevalue.id);
                    $('[name=name]').val(result.rarevalue.name);
                    $('[name=description]').val(result.rarevalue.description);
                    $('[name=thumbnail]').val(result.rarevalue.thumbnail);
                } else {
                    $('[name=pageId]').val(0);
                    $('[name=name]').val("");
                    $('[name=description]').val("");
                    $('[name=thumbnail]').val("");
                }
            });
        },
      
        loadrarevaluepages: function(roleid) {

            var datatableRarevaluepages = function() {
            if ($('#kt_datatable_rarevalue').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_rarevalue").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/RareValue/getpages',
                       headers: {'Authorization': 'housekeeping_rarevalue_control' }
                     }
                   },
                   pageSize: 5
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
                   title: "Id"
               }, {
                   field: "name",
                   title: "Name"
               }, {
                   field: "description",
                   title: "Description",
               }, {
                   field: "thumbnail",
                   title: "Thumbnail",
               },
			   {
                  field: "Actions",
                  title: "Actions",
                  sortable: !1,
                 
                  overflow: "visible",
                  textAlign: "left",
                  autoHide: !1,
                  template: function() {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#editPageModal" id="editPage" title="Edit"><i class="flaticon2-edit"></i></a> <a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="viewPage"  title="Manage"><i class="flaticon2-menu-4"></i></a> <a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="deletePage" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash"></i></a>'
                        }
              }]
            }), $("#kt_datatable_rarevalue_reload").on("click", function() {
               $("#kt_datatable_rarevalue").KTDatatable("reload")
            })
            };
			   $("#kt_datatable_rarevalue").unbind().on("click", "#editPage, #deletePage, #viewPage", function(e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let title = $(e.target).closest('.kt-datatable__row').find('[data-field="name"]').text();
              
                if ($(this).attr("id") == "editPage") {
                    rarevalue.pageRequest(id);
					$(".modal-title").html("Edit " + title);
					$(".editPage").unbind().click(function() {
                        rarevalue.editPage(id, $("#ePageName").val(), $("#ePageDesc").val(), $("#ePageThumb").val(), $("#epageId").val());
                    });
                } else {
					if ($(this).attr("id") == "viewPage") {
					$("#items-title").html(title + " items");
					
                        $("#kt_datatable_rarevalueitems").KTDatatable().search(id, 'parent_id');
                }else{
					 $('#confirm-delete').on('show.bs.modal', function(e) {
                    $(".modal-title").html("Delete " + title);
					
                  $(".btn-ok").unbind().click(function() {
                        rarevalue.deletePage(id);
                    });
                }); 
				}                
			}
            });
            datatableRarevaluepages();
        
        },
		
		loadrarevalueitems: function(roleid) {

            var datatableRarevalueitems = function() {
            if ($('#kt_datatable_rarevalueitems').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_rarevalueitems").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/RareValue/getitems',
                       headers: {'Authorization': 'housekeeping_rarevalue_control' }
                     }
                   },
                   pageSize: 5
               },
               layout: {
                   scroll: !1,
                   footer: !1
               },
               sortable: !0,
               pagination: !0,
               search: {
                   input: $("#SearchItems")
               },
               columns: [{
                   field: "id",
                   title: "Id"
               }, {
                   field: "parent_id",
                   title: "Page Id"
               }, {
                   field: "name",
                   title: "Name",
               }, {
                   field: "item_id",
                   title: "Item Base",
               }, {
                   field: "cost_credits",
                   title: "Cost Credits",
               },
			   {
                   field: "cost_points",
                   title: "Cost Points",
               },
			   {
                   field: "points_type",
                   title: "Points Type",
               },{
                   field: "image",
                   title: "Image",
               },
			   {
                  field: "Actions",
                  title: "Actions",
                  sortable: !1,
                 
                  overflow: "visible",
                  textAlign: "left",
                  autoHide: !1,
                  template: function() {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#editItemModal" id="editItem" title="Edit"><i class="flaticon2-edit"></i></a>  <a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="deleteItem" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash"></i></a>'
                        }
              }]
            }), $("#kt_datatable_rarevalueitems_reload").on("click", function() {
				$("#kt_datatable_rarevalueitems").KTDatatable().search('','');
               $("#kt_datatable_rarevalueitems").KTDatatable("reload");
            })
            };
			   $("#kt_datatable_rarevalueitems").unbind().on("click", "#editItem, #deleteItem", function(e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let title = $(e.target).closest('.kt-datatable__row').find('[data-field="name"]').text();
              
                if ($(this).attr("id") == "editItem") {
                    rarevalue.itemRequest(id);
					$(".modal-title").html("Edit " + title);
					$(".editItem").unbind().click(function() {
                        rarevalue.editItem(id, $("#eparentId").val(), $("#eItemName").val(), $("#eItemId").val(), $("#eItemCredits").val(), $("#eItemPoints").val(), $("#eItemType").val(), $("#eImageItem").val());
                    });
                } else {
                    $('#confirm-delete').on('show.bs.modal', function(e) {
                    $(".modal-title").html("Delete " + title);
					
                  $(".btn-ok").unbind().click(function() {
                        rarevalue.deleteItem(id);
                    });
                });                  
                }
            });
            datatableRarevalueitems();
        
        }
      
    }
}();

jQuery(document).ready(function() {
    rarevalue.init();
    rarevalue.loadrarevaluepages();
    rarevalue.loadrarevalueitems();
  
});
