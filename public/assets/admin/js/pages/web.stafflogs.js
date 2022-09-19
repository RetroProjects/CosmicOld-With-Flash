var datatableBanLogs = function() {
if ($('#kt_datatable_staff_logs').length === 0) {
    return;
}

var t;
t = $("#kt_datatable_staff_logs").KTDatatable({
    data: {
        type: 'remote',
        source: {
          read: {
            url: '/housekeeping/api/logs/getstafflogs',
            headers: {'Authorization':'housekeeping_staff_logs' }
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
        width: 75
    }, {
        field: "username",
        title: "Username",
        width: 100,
        template: function(data) {
            return '<span class="kt-font"><a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal"  data-id="' + data.username + '">' + data.username +  '</a>';
        }
    }, {
        field: "type",
        title: "Type", 
        width: 85
    }, {
        field: "value",
        title: "Data",
        width: 350
    }, {
        field: "target",
        title: "Target",
        width: 100,
        template: function(data) {
            if(data.target !== null)
                return '<span class="kt-font"><a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal"  data-id="' + data.target + '">' + data.target +  '</a>';
        }
    }, {
        field: "timestamp",
        title: "Timestamp"
    }]
}), $("#kt_datatable_reload").on("click", function() {
    $("#kt_datatable_staff_logs").KTDatatable("reload")
})
}

var datatableCommandLogs = function() {
if ($('#kt_datatable_command_logs').length === 0) {
    return;
}

var t;
t = $("#kt_datatable_command_logs").KTDatatable({
    data: {
        type: 'remote',
        source: {
          read: {
            url: '/housekeeping/api/logs/getcommandlogs',
            headers: {'Authorization':'housekeeping_staff_logs' }
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
        field: "username",
        title: "Username",
        width: 100,
        template: function(data) {
            return '<span class="kt-font"><a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal"  data-id="' + data.username + '">' + data.username +  '</a>';
        }
    }, {
        field: "params",
        title: "Params", 
        width: 180
    }, {
        field: "command",
        title: "Command",
        width: 400
    }, {
        field: "timestamp",
        title: "Succes",
        width: 350
    }]
}), $("#kt_datatable_reload").on("click", function() {
    $("#kt_datatable_command_logs").KTDatatable("reload")
})
}
datatableBanLogs();
datatableCommandLogs();