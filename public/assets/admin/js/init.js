"use strict";

// Class definition
var KTDashboard = function() {

    return {
        // Init demos
        init: function() {
            // demo loading
            var loading = new KTDialog({
                'type': 'loader',
                'placement': 'top center',
                'message': 'Loading ...'
            });
            loading.show();

            setTimeout(function() {
                loading.hide();
            }, 4000);
        }
    };
}();

var select2Interface = function() {
    return {
        init: function() {
            $('.remoteControl').select2({
                placeholder: 'Select a user',
                width: "100%",
                ajax: {
                    url: '/housekeeping/search/get/playername',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            searchTerm: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
            $('.chatControl').select2({
                placeholder: 'Select a user',
                width: "85%",
                ajax: {
                    url: '/housekeeping/search/get/playername',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            searchTerm: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });     
        }
    };
}();

var blockPageInterfaceSubmit = function() {
    return {
        init: function() {
            KTApp.blockPage({
                overlayColor: "#000000",
                type: "v2",
                state: "primary",
                message: "Processing..."
            }), setTimeout(function() {
                KTApp.unblockPage()
            }, 2e3)
        }
    };
}();

var blockPageInterface = function() {
    return {
        init: function() {
            $(".ajaxLoad, .preventDoubleRequest").click(function() {
                KTApp.blockPage({
                    overlayColor: "#000000",
                    type: "v2",
                    state: "primary",
                    message: "Processing..."
                }), setTimeout(function() {
                    KTApp.unblockPage()
                }, 2e3)
            });


        }
    };
}();

var WebPostInterface = function() {

    return {
        init: function() {
            /*
             * Post method
             * */
            this.post = function(url, data, callback, form) {
                // Prepare data
                if (!(data instanceof FormData)) {
                    if (!(data instanceof Object))
                        return;

                    var data_source = data;
                    data = new FormData();
                    for (var key in data_source) {
                        if (!data_source.hasOwnProperty(key))
                            continue;

                        data.append(key, data_source[key]);
                    }
                }

                // Check form name
                if (form !== undefined) {
                    if (form.attr("action") === "login")
                        data.append("return_url", window.location.href);
                }

                // Requests
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    dataType: "json",
                    headers: {
                        "Authorization": "housekeeping"
                    },
                    processData: false,
                    contentType: false
                }).done(function(result) {
                    // Change full page
                    if (result.location) {
                        window.location = result.location;
                        return null;
                    }
                  
                    if(isEmpty(result.status)) {
                        if (typeof callback === "function")
                            callback(result);
                            return null;
                    }

                    // Change page
                    if (result.pagetime)
                        setTimeout(function() {
                            window.location = result.pagetime
                        }, 2500);
                    
                    // Create notification
                    if (!isEmpty(result.status) && !isEmpty(result.message))
                      
                        if(result.status == "success") {
                            $('.modal').modal('hide');
                        }
                      
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": true,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        };

                    toastr[result.status](result.message);

                    // Callback if exists
                    if (typeof callback === "function")
                        callback(result);
                });
            };
        }
    }
}();

var LoadingPage = function() {

    return {
        init: function() {
            this.web_document = $("body");
            var self = this;

            this.ajax_manager = new WebPostInterface.init();
            this.web_document.on("submit", "form:not(.default-prevent)", function(event) {
                event.preventDefault();

                if ($(this).attr("method") !== "get")
                    self.ajax_manager.post("/housekeeping/api/" + $(this).attr("action"), new FormData(this), null, $(this));
                else {
                    return true;
                }
            });
        }
    }
}();

var showOnlinePlayers = function() {

    return {
        init: function() {
            
            $("#onlinePlayers").show();

            var datatableOnlinePlayers = function() {
                if ($('#kt_datatable_online_users').length === 0) {
                    return;
                }

                var t;
                t = $("#kt_datatable_online_users").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/dashboard/usersonline',
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
                        input: $("#searchOnlinePlayers")
                    },
                    columns: [{
                        field: "id",
                        title: "#",
                        type: "number",
                        width: 40,
                        template: function(data) {
                            return '<span class="kt-font">' + data.id + '</span>';
                        }
                    }, {
                        field: "username",
                        title: "Username",
                        width: 250,
                        template: function(data, i) {
                            var output = '\
                                    <div class="kt-user-card-v2">\
                                        <div class="kt-user-card-v2__pic">\
                                            <div class="kt-badge kt-badge--xl" style="background: #d8d8d8;"><span class="kt-portlet__head-icon"><img src="' + Site.figure_url + '/?figure=' + data.look + '&gesture=sml&headonly=1&size=s" alt="image"></span></div>\
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
                        template: function(data) {
                            return '<span class="kt-font">' + data.ip_register + ' / ' + data.ip_current + '</span>';
                        }
                    }]
                });

                $("#kt_datatable_online_reload").on("click", function() {
                    $("#kt_datatable_online_users").KTDatatable("reload")
                });
            };
            datatableOnlinePlayers();
        }
    }
}();

function isEmptyObj(obj) {
    return Object.keys(obj).length === 0;
}

function isEmpty(str) {
    if (typeof str === "string")
        str = str.trim();

    return (!str || 0 === str.length);
}

// Class initialization on page load
jQuery(document).ready(function() {
    KTDashboard.init();
    LoadingPage.init();
    blockPageInterface.init();
    select2Interface.init();

    var self = this;
    this.ajax_manager = new WebPostInterface.init();

    $("#showOnlinePlayers").unbind().click(function () {
        showOnlinePlayers.init();
    });
  
    $(".clearCache").unbind().click(function () {
        self.ajax_manager.post("/housekeeping/api/dashboard/clearcache", {});
   });
  
    $(".maintenance").unbind().click(function () {
        self.ajax_manager.post("/housekeeping/api/dashboard/maintenance", {});
   });
});