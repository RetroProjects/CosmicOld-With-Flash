var feeds = function() {

    return {
        init: function() {
            feeds.initDatatable();
        },

        initDatatable: function() {

            var datatableFeeds = function() {

                if ($('#kt_datatable_feeds').length === 0) {
                    return;
                }

                $("#kt_datatable_feeds").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/feeds/getfeeds',
                                headers: {
                                    'Authorization': 'housekeeping_website_feeds'
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
                        input: $("#generalSearch")
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
                        field: "from_username",
                        title: "From user",
                        width: 100,
                        template: function(data) {
                            return '<span class="kt-font"><a href="#" data-toggle="modal" data-target="#actionModal" data-id="' + data.from_username + '">' + data.from_username + '</a></span>';
                        }
                    }, {
                        field: "message",
                        title: "Message",
                        width: 300,
                        template: function(data) {
                            return '<span class="kt-font">' + data.message + '</span>';
                        }
                    }, {
                        field: "to_username",
                        title: "To user",
                        template: function(data) {
                            return '<span class="kt-font"><a href="#" data-toggle="modal" data-target="#actionModal" data-id="' + data.to_username + '">' + data.to_username + '</a></span>';
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
                            return '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-sm feedActions" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="flaticon2-trash"></i></a>'
                        }
                    }]
                }), $("#kt_datatable_reload").on("click", function() {
                    $("#kt_datatable_feeds").KTDatatable("reload")
                })
            }

            $("#kt_datatable_feeds").unbind().on("click", ".feedActions", function(e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();

                $('#confirm-delete').on('show.bs.modal', function(e) {
                    $(".modal-title").html("Delete Feed");
                    $(".btn-ok").unbind().click(function () {
                        feeds.deleteFeed(id);
                    });
                });
            });

            datatableFeeds();
        },

        deleteFeed: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/feeds/deletefeed", {
                post: id
            }, function(result) {
                if (result.status == "success") {
                    $("#kt_datatable_feeds").KTDatatable("reload");
                }
            });
        },

    }
}();


jQuery(document).ready(function() {
    feeds.init();
});