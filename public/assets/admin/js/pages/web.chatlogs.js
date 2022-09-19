var chatlogs = function() {
  
    return {  
        init: function() {
            chatlogs.initDatatable();
          
            $(".compareUsers").unbind().click(function() {
                  chatlogs.compareUsers($('.chatControl').val());
            });
          
            $(".getAllLogs").unbind().click(function() {
                  chatlogs.getAllLogs($('.elm').val());
            });
        },
      
        getAllLogs: function(id) {
            $("#chatlogs .kt-portlet__head-title").html("All logs");

            var datatableCompare = function () {

                if ($('#kt_datatable_chatlogs').length === 0) {
                    return;
                } else {
                    if($.trim( $('#kt_datatable_chatlogs').html() ).length) {
                      $("#kt_datatable_chatlogs").KTDatatable("destroy")
                    }
                }

                var t;
                $("#kt_datatable_chatlogs").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                          read: {
                            url: '/housekeeping/api/remote/getallchatslogs',
                            params: {
                               "user_id": id
                            },
                            headers: {'Authorization': 'housekeeping_remote_control' }
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
                        field: "room_id",
                        title: "Room Id",
                        type: "number",
                        width: 75
                    }, {
                        field: "user_from_id",
                        title: "Username",
                        width: 75,
                        template: function(data) {
                            return '<span class="kt-font"><a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal"  data-id="' + data.user_from_id + '">' + data.user_from_id + '</a></span>';
                        }
                    }, {
                        field: "message",
                        title: "Message",
                        width: 350
                    }, {
                        field: "timestamp",
                        title: "Timestamp"
                    }]
                });

                $("#kt_datatable_reload").on("click", function () {
                    $("#kt_datatable_chatlogs").KTDatatable("reload")
                });
            };

            datatableCompare();
        },
      
        compareUsers: function (dataArray) {
            $("#chatlogs .kt-portlet__head-title").html("Compare Logs by " +dataArray);

            var datatableCompare = function () {

                if ($('#kt_datatable_chatlogs').length === 0) {
                    return;
                } else {
                    if($.trim( $('#kt_datatable_chatlogs').html() ).length) {
                      $("#kt_datatable_chatlogs").KTDatatable("destroy")
                    }
                }

                var t;
                $("#kt_datatable_chatlogs").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                          read: {
                            url: '/housekeeping/api/logs/getCompareUsersLogs',
                            params: {
                               "element": dataArray
                            },
                            headers: {'Authorization': 'housekeeping_remote_control' }
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
                        field: "name",
                        title: "Type",
                        width: 75
                    }, {
                       field: "player",
                       title: "Player",
                       width: 125,
                       template: function(data) {
                          return '<a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal" data-id="' + data.player + '">' + data.player + '</a>';
                       }
                   }, {
                       field: "message",
                       title: "Message",
                       width: 350
                   }, {
                       field: "timestamp",
                       title: "timestamp",
                   }]
                });

                $("#kt_datatable_reload").on("click", function () {
                    $("#kt_datatable_chatlogs").KTDatatable("reload")
                });
            };

            datatableCompare();
        },

        initDatatable: function () {
            $("#chatlogs .kt-portlet__head-title").html("All chatlogs");
          
            var datatableChatLogs = function() {
                if ($('#kt_datatable_chatlogs').length === 0) {
                    return;
                }

                var t;
                t = $("#kt_datatable_chatlogs").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                          read: {
                            url: '/housekeeping/api/logs/getchatlogs',
                            headers: {'Authorization': 'housekeeping_chat_logs' }
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
                        field: "type",
                        title: "Type",
                        width: 75
                    }, {
                        field: "user_from_id",
                        title: "Username",
                        width: 125,
                        template: function(data) {
                            return '<span class="kt-font"><a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal"  data-id="' + data.user_from_id + '">' + data.user_from_id + '</a></span>';
                        }
                    }, {
                        field: "message",
                        title: "Message",
                        width: 350
                    }, {
                        field: "timestamp",
                        title: "Timestamp"
                    }]
                }), $("#kt_datatable_reload").on("click", function() {
                    $("#kt_datatable_chatlogs").KTDatatable("reload")
                })
                }
            datatableChatLogs();
        },
      
    }
}();

jQuery(document).ready(function() {
    chatlogs.init();
});