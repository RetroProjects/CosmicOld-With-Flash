var datatableBanLogs = function() {
if ($('#kt_datatable_ban_logs').length === 0) {
    return;
}

var t;
t = $("#kt_datatable_ban_logs").KTDatatable({
    data: {
        type: 'remote',
        source: {
          read: {
            url: '/housekeeping/api/logs/getbanlogs',
            headers: {'Authorization': 'housekeeping_ban_logs' }
          }
        },
        pageSize: 15
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
        field: "user_id",
        title: "User",
        width: 100,
        template: function(data) {
            return '<span class="kt-font"><a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal"  data-id="' + data.user_id + '">' + data.user_id +  '</a>';
        }
    }, {
        field: "type",
        title: "Type",
        width: 75
    }, {
        field: "ban_reason",
        title: "Reason",
        width: 250
    }, {
        field: "ban_expire",
        title: "Expire"
    }, {
        field: "user_staff_id",
        title: "By"
    }]
}), $("#kt_datatable_reload").on("click", function() {
    $("#kt_datatable_ban_logs").KTDatatable("reload")
})
}
datatableBanLogs();