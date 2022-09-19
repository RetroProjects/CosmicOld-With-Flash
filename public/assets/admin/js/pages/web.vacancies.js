var vacanies = function() {

    return {
        init: function() {        
            $(".createVacancie").unbind().click(function() {
                vacanies.edit(null);
            });
          
            $("#goBack").unbind().click(function() {
                vacanies.back();
            });
        },
      
        back: function () {
            $("#kt_datatable_vacancies").KTDatatable("destroy")
            vacanies.loadVacanies();
            $(".goback").hide();
        },

        loadVacanies: function() {

            var datatableVacancies = function() {
            if ($('#kt_datatable_vacancies').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_vacancies").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/vacancies/getvacanies',
                       headers: {'Authorization': 'housekeeping_permissions' }
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
                   title: "id",
                   width: 50
               }, {
                   field: "job",
                   title: "Job",
                  template: function(data) {
                      return '<a href="#" data-toggle="modal" id="viewvacancies" data-target="#view-vacancies" data-value="' + data.id + '">' + data.job + '</a>'
                  }
               }, {
                    field: "applys",
                    title: "Applys",
                   width: 50
               }, {
                  field: "Actions",
                  title: "Actions",
                  sortable: !1,
                  width: 75,
                  overflow: "visible",
                  textAlign: "left",
                  autoHide: !1,
                  template: function(data) {
                      return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="viewApply" title="view"><i class="flaticon-eye"></i></a> <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm delete" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash" data-value="' + data.id + '"></i></a>'
                  }
              }]
            }), $("#kt_datatable_vacancies_reload").on("click", function() {
               $("#kt_datatable_vacancies").KTDatatable("reload")
            })
            }
            
            datatableVacancies();
          
            $("body").unbind().on("click", "#viewvacancies, #viewApply, .delete", function(e) {
              
                var jobtitle = $(e.target).closest('.kt-datatable__row').find('[data-field="job"]').text();
                var jobid = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();

                if ($(this).attr("id") == "viewApply") {
                    vacanies.loadApplications(jobid, jobtitle);
                }
              
                $('#view-vacancies').unbind().on('show.bs.modal', function(e) {
                    $(".modal-title").html("Edit " + jobtitle);
                    vacanies.edit(jobid);
                });
              
                $('#confirm-delete').on('show.bs.modal', function(e) {
                    $(".modal-title").html("Delete " + jobtitle);
                    $(".btn-ok").html("Delete");
                  
                    $(".btn-ok").click(function () {
                        vacanies.delete(jobid);
                    });
                });
            });
        },
      
        loadApplications: function(id, jobtitle) {
          
            $("#kt_datatable_vacancies").KTDatatable("destroy")
            $(".kt-portlet__head-title").html("All vacancies for " + jobtitle);
          
            var datatableVacancies = function() {
            if ($('#kt_datatable_vacancies').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_vacancies").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/vacancies/getApplications',
                        params: {
                           "jobid": id
                        },
                       headers: {'Authorization': 'housekeeping_permissions' }
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
                   title: "id",
                   width: 50
               }, {
                   field: "user_id",
                   title: "Username",
              }, {
                    field: "firstname",
                    title: "Firstname",
              }, {
                    field: "status",
                    title: "Status",
                    sortable: "desc",
                   width: 50
               }, {
                  field: "Actions",
                  title: "Actions",
                  sortable: !1,
                  width: 75,
                  overflow: "visible",
                  textAlign: "left",
                  autoHide: !1,
                  template: function(data) {
                      return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="view" data-toggle="modal" data-target="#view-application" title="view"><i class="flaticon-eye"></i></a>'
                  }
              }]
            }), $("#kt_datatable_vacancies_reload").on("click", function() {
               $("#kt_datatable_vacancies").KTDatatable("reload")
            })
            }
            
            datatableVacancies();
          
            $(".goback").show();
            
            $("body").unbind().on("click", "#view", function(e) {
                var id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                var firstname = $(e.target).closest('.kt-datatable__row').find('[data-field="firstname"]').text();
                
                $(".modal-title").html("Vacancie from " + firstname);
              
                vacanies.view(id);
            });
        },
      
        view: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init(); 
          
            self.ajax_manager.post("/housekeeping/api/vacancies/seejob", {
                id: id
            }, function(result) {
                $(".job-body").html("<b>My message</b><br /><br />" + result.job.message + "<br /><br />");
              
                $(".monday").html(result.job.available_monday);
                $(".tuesday").html(result.job.available_tuesday);
                $(".wednesday").html(result.job.available_wednesday);
                $(".thursday").html(result.job.available_thursday);
                $(".friday").html(result.job.available_friday);
                $(".saturday").html(result.job.available_saturday);
                $(".sunday").html(result.job.available_sunday);
              
                $(".to-accept").unbind().click(function () {
                    self.ajax_manager.post("/housekeeping/api/vacancies/accept", {
                        id: id
                    }, function(result) {
                        $("#kt_datatable_vacancies").KTDatatable("reload")
                    });
                });
            });
        },
      
        delete: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init(); 
          
            self.ajax_manager.post("/housekeeping/api/vacancies/delete", {
                id: id
            }, function(result) {
                $("#kt_datatable_vacancies").KTDatatable("reload")
            });
        },
        
        edit: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            var jobid = id;
          
            if(id == null) {
                $(".modal-title").html("Add an vacancie");
                $(".btn-ok").html("Add");
              
                $('[name=job]').val("");
                $('[name=small_description]').val("");
                tinyMCE.activeEditor.setContent("");
            } else {
                self.ajax_manager.post("/housekeeping/api/vacancies/getjob", {
                    id: jobid
                }, function(result) {
                    $('[name=job]').val(result.job);
                    $('[name=small_description]').val(result.small_description);
                    tinyMCE.activeEditor.setContent(result.full_description);
                });
            }
          
            $(".btn-ok").unbind().click(function () {

                var job_title = $('[name=job]').val();
                var small_description = $('[name=small_description]').val();
                var full_description = tinyMCE.get("full_description").getContent();

                self.ajax_manager.post("/housekeeping/api/vacancies/editadd", {
                    jobid: jobid,
                    job_title: job_title,
                    small_description: small_description,
                    full_description: full_description
                }, function(result) {
                    $("#kt_datatable_vacancies").KTDatatable("reload")
                });
            });

        }
    }
}();

jQuery(document).ready(function() {
    vacanies.init();
    vacanies.loadVacanies();
  
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