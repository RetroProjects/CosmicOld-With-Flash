var home = function() {

    return {
        init: function() {
            home.initDatatable();
        },

        initDatatable: function () {
            var datatableLatestPlayers = function() {
                if ($('#kt_datatable_latest_players_table').length === 0) {
                    return;
                }

                var t;
                t = $("#kt_datatable_latest_players_table").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/dashboard/latestplayers',
                                headers: {'Authorization': 'housekeeping' }
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
                        input: $("#search_LatestPlayers")
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
                        field: "username",
                        title: "Username",
                        width: 200,
                        template: function(data, i) {
                            var output = '\
                                    <div class="kt-user-card-v2">\
                                        <div class="kt-user-card-v2__pic">\
                                            <div class="kt-badge kt-badge--xl" style="background: #d8d8d8;"><span class="kt-portlet__head-icon"><img src="' + Site.figure_url + '?figure=' + data.look + '&headonly=1&gesture=&size=s" alt="image"></span></div>\
                                        </div>\
                                        <div class="kt-user-card-v2__details">\
                                            <a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal" data-id="' + data.username + '">' + data.username + '</a>\
                                            <span class="kt-user-card-v2__email">' + data.mail + '</span>\
                                        </div>\
                                    </div>';

                            return output;
                        }
                    }, {
                        field: "lastip",
                        title: "Last / Reg IP",
                        width: 250,
                        template: function(data) {
                            return '<span class="kt-font">' + data.ip_current + ' / ' + data.ip_register + '</span>';
                        }
                    }, {
                        field: "lastvisit",
                        title: "Date",
                        width: 130,
                        template: function(data) {
                            return '<span class="kt-font">' + data.last_login + '</span>';
                        }
                    }]
                });

                $("#kt_datatable_reload_latest").on("click", function() {
                    $("#kt_datatable_latest_players_table").KTDatatable("reload");
                });
            };

            var datatableLatestNamechanges = function() {
                if ($('#kt_datatable_latest_namechanges').length === 0) {
                    return;
                }

                var t;
                t = $("#kt_datatable_latest_namechanges").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/dashboard/latestnamechanges',
                                headers: {'Authorization': 'housekeeping' }
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
                        input: $("#searchNamechange")
                    },
                    columns: [{
                        field: "id",
                        title: "#",
                        type: "number",
                        width: 75,
                        template: function(data) {
                            return '<span class="kt-font">' + data.user_id + '</span>';
                        }
                    }, {
                        field: "new_name",
                        title: "New username",
                        width: 130,
                        template: function(data) {
                            return '<span class="kt-font"><a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal" data-id="' + data.new_name + '">' + data.new_name + '</a></span>';
                        }
                    }, {
                        field: "old_name",
                        title: "Old username",
                        width: 130,
                        template: function(data) {
                            return '<span class="kt-font">' + data.old_name + '</span>';
                        }
                    }]
                });

                $("#kt_datatable_reload_l").on("click", function() {
                    $("#kt_datatable_latest_namechanges").KTDatatable("reload");
                });
            };

            datatableLatestPlayers();
            datatableLatestNamechanges();
        }
    }
}();

jQuery(document).ready(function() {
    home.init();
});
