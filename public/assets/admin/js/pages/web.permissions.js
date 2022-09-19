var permissions = function() {
    var hPermissions = [];
    var sCommands;
  
    var aPush = function(t) {
        if(!hPermissions.includes(t)) {
            hPermissions.push(t);
        }
    };
  
    var aDelete = function(t) {
        for( var i = 0; i < hPermissions.length; i++){ 
           if ( hPermissions[i] === t) {
             hPermissions.splice(i, 1); 
           }
        }
    }
    
    return {
        roles: function () {
          
            $("#editPermissions").show();
            $("#goBackWizard").show();
            $("#rankManagement").show();
            permissions.getRanks();

            $(".roleSearch").unbind().click(function() {
                var self = this;
                this.ajax_manager = new WebPostInterface.init();
                var role = $('.targetRole').select2('data');
                
                self.ajax_manager.post("/housekeeping/api/permissions/search", {post: role[0].id}, function (result) {
                    if(result.status == "success") {
                        $("#roleSearch, #permissionsTable").show();
                        permissions.searchObject(role[0].id);
                    }
                });
              
            });
        },  
      
        getRanks: function() {
          
            var datatableCompare = function() {

            if ($('#kt_datatable_teams').length === 0) {
                return;
            } else {
                if ($.trim($('#kt_datatable_teams').html()).length) {
                    $("#kt_datatable_teams").KTDatatable("destroy")
                }
            }

            var t;
            $("#kt_datatable_teams").KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '/housekeeping/api/permissions/getteams',
                            headers: {
                                'Authorization': 'housekeeping_permissions'
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
                    field: "id",
                    title: "Rank Id",
                    width: 75
                }, {
                    field: "rank_name",
                    title: "Rank",
                    width: 75
                }, {
                    field: "rank_description",
                    title: "Description"
                }, {
                    field: "Action",
                    title: "Action",
                    overflow: "visible",
                    autoHide: !1,
                    template: function() {
                        return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="deleteRank" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash"></i></a>'
                    }
                }]
            });

            $("#kt_datatable_reload").on("click", function() {
                $("#kt_datatable_teams").KTDatatable("reload")
            });
        };

        datatableCompare();
   
        $("body").unbind().on("click", "#deleteRank", function(e) {
            e.preventDefault();
            let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
            let rank_name = $(e.target).closest('.kt-datatable__row').find('[data-field="rank_name"]').text();

            $('#confirm-delete').on('show.bs.modal', function(e) {
                $(".modal-title").html("Delete " + rank_name);

                $(".btn-ok").click(function() {
                    var self = this;
                    this.ajax_manager = new WebPostInterface.init();
                    self.ajax_manager.post("/housekeeping/api/permissions/deleteteam", {
                        id:id
                    }, function(result) {
                        if (result.status == "success") {
                            $("#kt_datatable_teams").KTDatatable("reload");
                        }
                    });
                });
            });
        });

        $(".saveTeam").unbind().click(function() {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
            self.ajax_manager.post("/housekeeping/api/permissions/addteam", {
                rank_name: $("#rank_name").val(),
                rank_desciption: $("#rank_description").val()
            }, function(result) {
                if (result.status == "success") {
                    $('#addCategoryModal').modal('toggle');
                    $("#kt_datatable_teams").KTDatatable("reload");
                }
            });
        });
          
        },
      
        rankRequest: function() {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            self.ajax_manager.post("/housekeeping/api/permissions/getranks", {post: true}, function (result) {
                  $("#manageCommands").show();
                  $("#rankWizard").hide();
                  permissions.permissionTree();
            });    
        },
      
        permissionTree: function () {
                $("#kt_tree_6").jstree({
                core: {
                    themes: {
                        responsive: !1
                    },
                    multiple: false,
                    check_callback: true,
                    data: {
                        url: function(e) {
                            return "/housekeeping/permissions/get/commands"
                        },
                        headers: {
                            'Authorization': 'housekeeping_permissions'
                        },
                        data: function(e) {
                            return {
                                parent: e.id
                            }
                        }
                    }
                },
                types: {
                    default: {
                        icon: "fa fa-folder kt-font-brand"
                    },
                    file: {
                        icon: "fa fa-file  kt-font-brand"
                    },
                    '#': {
                        "valid_children" : []
                    },
                    branch: {
                        "valid_children" : ["leaf"]
                    },
                    leaf : {
                        "valid_children" : []
                    }
                },
                state: {
                    key: "demo3"
                },
                plugins: ["dnd", "changed", "types"]
            }), $("#kt_tree_6").bind("move_node.jstree", function (e, data) {
                    permissions.changePermissionRank(data.node.text, data.parent);
            });
        },
      
        changePermissionRank(command, min_rank) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
            self.ajax_manager.post("/housekeeping/api/permissions/changepermissionrank", {command_id: command, minimum_rank: min_rank}, function (result) {
                if(result.status == "success") {
                     console.log(1)
                }
            });
        },
      
        searchObject: function(roleid) {
            
            //load Permmissions
            permissions.loadPermissions(roleid);
          
            //load permissions that they didnt have
            $('.targetPermission').select2({
                placeholder: 'Select a permission',
                width: '85%',
                ajax: {
                    url: '/housekeeping/search/get/permission',
                    headers: {
                        "Authorization": "housekeeping_permissions"
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            roleid: roleid
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
          
            $(".permissionSearch").unbind().click(function() {
                var role = $('.targetPermission').select2('data');
                permissions.addPermission(role[0].id, roleid);
            });
        },
      
        addPermission: function(permission, role) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
            self.ajax_manager.post("/housekeeping/api/permissions/addpermission", {roleid: role, permissionid: permission}, function (result) {
                if(result.status == "success") {
                     $("#kt_datatable_permissions").KTDatatable("reload");
                }
            });
        },
      
        loadPermissions: function(roleid) {

            var datatablePermissions = function() {
            if ($('#kt_datatable_permissions').length === 0) {
                return;
            } else if($('#kt_datatable_permissions').length === 1) {
                $("#kt_datatable_permissions").KTDatatable("destroy")
            }

            var t;
            t = $("#kt_datatable_permissions").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/permissions/getpermissions',
                       params: {
                           "roleid": roleid
                       },
                       headers: {'Authorization': 'housekeeping_permissions' }
                     }
                   },
                   pageSize: 50
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
                   field: "idp",
                   title: "#",
                   type: "number",
                   width: 25,
                   template: function(data) {
                       return '<span class="kt-font">' + data.idp + '</span>';
                   }
               }, {
                   field: "permission",
                   title: "Permission",
                   // callback function support for column rendering
                   template: function(data) {
                       return '<span class="kt-font">' + data.permission + '</span>';
                   }
               }, {
                   field: "description",
                   title: "Description",
                   width: 500,
                   template: function(data) {
                       return '<span class="kt-font">' + data.description + '</span>';
                   }
               }, {
                  field: "Actions",
                  title: "Actions",
                  sortable: !1,
                  width: 110,
                  overflow: "visible",
                  textAlign: "left",
                  autoHide: !1,
                  template: function(data) {
                      return '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm deletePermission" data-toggle="modal" data-target="#confirm-delete"  title="Delete"><i class="flaticon2-delete" data-value="' + data.id + '"></i></a>'
                  }
              }]
            }), $("#kt_datatable_permission_reload").on("click", function() {
               $("#kt_datatable_permissions").KTDatatable("reload")
            })
            }
            
            datatablePermissions();
          
            $("body").unbind().on('click', '.deletePermission', (e) => {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="idp"]').text();

                $('#confirm-delete').on('show.bs.modal', function(e) {
                    $(".modal-title").html("Delete permission");
                    $(".btn-ok").unbind().click(function () {
                        permissions.deletePermission(id);
                    });
                });
            });
        },
      
        deletePermission: function(rowid) {
            var self = this;
            
            this.ajax_manager = new WebPostInterface.init();
            self.ajax_manager.post("/housekeeping/api/permissions/delete", {id: rowid}, function (result) {
                if(result.status == "success") {
                    $("#kt_datatable_permissions").KTDatatable("reload");
                }
            });
        },
      
        init: function() {
            if ($('#kt_datatable_namechange').length === 0) {
                return;
            }
          
            var a;
            a = $("#kt_datatable_namechange").KTDatatable({
                data: {
                    type: "remote",
                      source: {
                        read: {
                            url: '/housekeeping/api/permissions/getwebsiteranks',
                            headers: {
                                'Authorization': 'housekeeping_permissions'
                            }
                        }
                    },
                    pageSize: 5,
                    serverPaging: !0,
                    serverFiltering: !0,
                    serverSorting: !0
                },
                layout: {
                    scroll: !0,
                    height: "auto",
                    footer: !1
                },
                sortable: !0,
                toolbar: {
                    placement: ["bottom"],
                    items: {
                        pagination: {
                            pageSizeSelect: [5, 10, 20, 30, 50]
                        }
                    }
                },
                search: {
                    input: $("#generalSearch")
                },
                columns: [{
                    field: "id",
                    title: "#",
                    sortable: !1,
                    width: 30,
                    type: "number",
                    selector: {
                        class: "kt-checkbox--solid"
                    },
                    textAlign: "center"
                }, {
                    field: "permission",
                    title: "Permission",
                }, {
                    field: "description",
                    title: "Description"
                }]
            }), $("#kt_datatable_clear").on("click", function() {
                $("#kt_datatable_console").html("")
            }), $("#kt_datatable_reload").on("click", function() {
                a.reload()
            }), $("#kt_datatable_check_all").on("click", function() {
                $(".kt-datatable").KTDatatable("setActiveAll", !0)
            }), $("#kt_datatable_uncheck_all").on("click", function() {
                $(".kt-datatable").KTDatatable("setActiveAll", !1)
            }), $("#kt_form_status,#kt_form_type").selectpicker(), $(".kt-datatable").on("kt-datatable--on-init", function() {
            }).on("kt-datatable--on-check", function(a, e) {
                aPush(e.toString())
            }).on("kt-datatable--on-uncheck", function(a, e) {
                aDelete(e.toString())
            })
        },
      
        wizard: function () {
            var e, r, t, i;
            KTUtil.get("kt_wizard_v4"), e = $("#kt_form"), (t = new KTWizard("kt_wizard_v4", {
                startStep: 1
            })).on("beforeNext", function(e) {
                !0 !== r.form() && e.stop()
            }), t.on("beforePrev", function(e) {
                !0 !== r.form() && e.stop()
            }), t.on("change", function(e) {
                if (e.currentStep === 3) {
                    $(".kt-form").css('width', '90%');
                    permissions.init();
                } else {
                    $(".kt-form").css('width', '60%');
                }
                if(e.currentStep === 4) {
                    var value;
                    var self = this;
                    this.ajax_manager = new WebPostInterface.init();
                    
                    $("#websitePermissions").empty();
                    self.ajax_manager.post("/housekeeping/api/permissions/wizard", {post: hPermissions}, function (result) {
                        var permissionTable = [
                            '<tr>\n' +
                               '<th scope="row">{id}</th>\n' +
                               '<td>{description}</td>\n' +
                            '</tr>\n'  
                        ].join("");      
                      
                        for (var x = 0; x < result.length; x++)
                        {
                          var permission = result[x];
                          var overview_template  = $(permissionTable.replace(/{description}/g, permission.description).replace(/{id}/g, x));
                          $("#websitePermissions").append(overview_template);
                        }
                    });
                  
                    $("#serverPermissions").empty();
                    sCommands = $(".kt-form__section input").map(function(){
                        if(this.checked) {
                          return {id: this.name, value: this.checked}
                        } else {
                          return {id: this.name, value: this.value} 
                        }
                    }).toArray();
                      
                    var overviewTable = [
                        '<tr>\n' +
                           '<th scope="row">{id}</th>\n' +
                           '<td>{permission}</td>\n' +
                           '<td>{checked}</td>\n' +
                        '</tr>\n'  
                    ].join("");
         
                    for (var i = 0; i < sCommands.length; i++) {
                        var commands = sCommands[i];
                        
                        if(commands.value == "on") {
                            value = "false";
                        } else {
                            value = commands.value;
                        }
                        var overview_template  = $(overviewTable.replace(/{id}/g, i).replace(/{permission}/g, commands.id).replace(/{checked}/g, value));
                        $("#serverPermissions").append(overview_template);
                    }
                  
                    $("#rankName").html($("[name=rank_name]").val() + ' has almost been created!');
                 
                }
                KTUtil.scrollTop()
            }), r = e.validate({
                ignore: ":hidden",
                rules: {
                    name: {
                        required: !0
                    },
                    flood_time: {
                        required: !0,
                        maxlength: 2
                    },
                    messenger_max_friends: {
                        required: !0,
                        maxlength: 4
                    }
                },
                invalidHandler: function(e, r) {
                    KTUtil.scrollTop(), swal.fire({
                        title: "",
                        text: "There are some errors in your submission. Please correct them.",
                        type: "error",
                        confirmButtonClass: "btn btn-secondary"
                    })
                },
                submitHandler: function(e) {}
            }), (i = e.find('[data-ktwizard-type="action-submit"]')).on("click", function(t) {
                t.preventDefault(), r.form() && (KTApp.progress(i), e.ajaxSubmit({
                    success: function() {
                        var self = this;
                        this.ajax_manager = new WebPostInterface.init();
                        $("#websitePermissions").empty();
                        self.ajax_manager.post("/housekeeping/api/permissions/createrank", {post: JSON.stringify(hPermissions), value: JSON.stringify(sCommands)}, function (result) {
                            if(result.status == "success") {
                                KTApp.unprogress(i), swal.fire({
                                    title: "",
                                    text: sCommands[0].value + " rank has been created! You will now be directed to the commands page where you can manage ranks",
                                    type: "success",
                                    confirmButtonClass: "btn btn-secondary"
                                });
                            }
                        });
                    }
                }))
            });
        }
    }
}();
jQuery(document).ready(function() {
  permissions.roles();
  permissions.wizard();
  
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