var wordFilter = function() {

    return {
        init: function() {        
            $(".addWord").unbind().click(function() {
                wordFilter.addWord($("#word").val());
            });
          
            $(".deleteWord").unbind().click(function() {
                var word = $('.removeWord').select2('data');
                wordFilter.deleteWord(word[0].id);
            });
        },
      
        addWord: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            self.ajax_manager.post("/housekeeping/api/wordfilter/add", {post: id}, function (result) {
                if(result.status == "success") {
                    $("#kt_datatable_wordfilter").KTDatatable("reload")
                }
            });
        },
      
        deleteWord: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/wordfilter/remove", {post: id}, function (result) {
                if(result.status == "success") {
                    $("#kt_datatable_wordfilter").KTDatatable("reload")
                }
            });
        },
      
        loadwordFilter: function(roleid) {

            var datatableWordfilter = function() {
            if ($('#kt_datatable_wordfilter').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_wordfilter").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/wordfilter/getwordfilters',
                       headers: {'Authorization': 'housekeeping_wordfilter_control' }
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
                   field: "key",
                   title: "Word"
               }, {
                   field: "hide",
                   title: "Hidden"
               }, {
                   field: "report",
                   title: "Send report",
               }, {
                   field: "mute",
                   title: "Give mute",
               }, {
                  field: "Actions",
                  title: "Actions",
                  sortable: !1,
                  width: 110,
                  overflow: "visible",
                  textAlign: "left",
                  autoHide: !1,
                  template: function(data) {
                      return '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm fitlerDelete" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-delete" data-value="' + data.word + '"></i></a>'
                  }
              }]
            }), $("#kt_datatable_wordfilter_reload").on("click", function() {
               $("#kt_datatable_wordfilter").KTDatatable("reload")
            })
            }
            
            datatableWordfilter();
          
            $("body").unbind().on('click', '.fitlerDelete', (e) => {
                e.preventDefault();
                var word = $(e.target).closest('.kt-datatable__row').find('[data-field="key"]').text();

                $('#confirm-delete').on('show.bs.modal', function(e) {
                    $(".modal-title").html("Delete Word");
                    $(".btn-ok").unbind().click(function () {
                        wordFilter.deleteWord(word);
                    });
                });
            });
        }
      
    }
}();

jQuery(document).ready(function() {
    wordFilter.init();
    wordFilter.loadwordFilter();
  
    $('.removeWord').select2({
        placeholder: 'Select a word',
        width: '80%',
        ajax: {
            url: '/housekeeping/search/get/wordfilter',
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