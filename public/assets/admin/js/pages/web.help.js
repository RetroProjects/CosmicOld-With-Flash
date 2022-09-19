var help = function() {

    return {
        init: function() {
            $(".addMessage").unbind().click(function() {
              tinyMCE.triggerSave();
              help.blockPage();
              help.addMessage($("[name=ticketId]").val(), $("#body-input").val());
            });
            
        },
      
        updateStatus: function (id, status) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            self.ajax_manager.post("/housekeeping/api/help/updateticket", {post: id, action: status}, function (result) {
                $("#kt_datatable_tickets").KTDatatable("reload");
                help.goBack();
            });
        },
      
        goBack: function () {
            $("#responses").fadeOut().empty();
            $("#userinfo").fadeOut().empty();
            $(".kt-notification").empty();
            $("#notifications").fadeOut();
            $("#sendmessage").fadeOut();
            $("#ticketTable").fadeIn();

            help.blockPage();
            help.loadTickets();
        },
      
        blockPage: function () {
            KTApp.blockPage({
                overlayColor: "#000000",
                type: "v2",
                state: "primary",
                message: "Processing..."
            });

            setTimeout(function() {
                KTApp.unblockPage();
            }, 2e3);
        },
      
        addMessage: function(id, msg) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();
          
            self.ajax_manager.post("/housekeeping/api/help/sendmessage", {post: id, message: msg}, function (result) {
                $("#responses").empty();
                $("#userinfo").empty();
                $("#kt-notification").empty();

                help.blockPage();
                help.ticketRequest(id);
            });
        },
      
        ticketRequest: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            this.notification_tmp = [             
            '<a href="#" class="kt-notification__item">\n' + 
                '<div class="kt-notification__item-icon">\n' +
                    '<i class="flaticon2-{option}"></i>\n' +
                '</div>\n' +
                '<div class="kt-notification__item-details">\n' + 
                    '<div class="kt-notification__item-title">\n' + 
                        '{assistant} {type} {value}\n' + 
                    '</div>\n' + 
                    '<div class="kt-notification__item-time">\n' + 
                        '{timestamp}\n' + 
                    '</div>\n' + 
                '</div>\n' + 
            '</a>'
            ].join(""); 
          
            this.ticket_tmp = [
            '<div class="kt-portlet">\n'  +
               '<div class="kt-portlet__head">\n'  +
                  '<div class="kt-portlet__head-label">\n'  +
                     '<span class="kt-portlet__head-icon">\n'  +
                     '<img src="' + Site.figure_url + '?figure={figurePath}&headonly=1&direction=2&head_direction=2&action=&gesture=&" alt="image">\n' +
                     '</span>\n'  +
                     '<h3 class="kt-portlet__head-title">\n'  +
                        '<a href="#" data-toggle="modal" data-target="#actionModal" data-id="{reporter}">{reporter}</a> - <small>{timestamp}</small>\n'  +
                     '</h3>\n'  +
                  '</div>\n'  +
                    '<div class="kt-portlet__head-toolbar">'  +
                       '<div class="kt-portlet__head-wrapper">'  +
                          '<select class="form-control bootstrap-select" id="status">'  +
                             '<option value="open">Open</option>\n'  +
                             '<option value="closed">Closed</option>\n'  +
                             '<option value="in_treatment">In treatment</option>\n'  +
                          '</select>'  +
                          '&nbsp;'  +
                          '<div class="kt-form__label">'  +
                             '<button class="btn btn-secondary" type="button" id="goBack">Back</button>'  +
                          '</div>'  +
                          '&nbsp;'  + 
                          '<div class="kt-input-icon kt-input-icon--left">'  +
                             '<input type="text" class="form-control" placeholder="Search..." id="generalSearch">'  +
                             '<span class="kt-input-icon__icon kt-input-icon__icon--left">'  +
                             '<span><i class="la la-search"></i></span>'  +
                             '</span>'  +
                          '</div>'  +
                       '</div>'  +
                    '</div>'  +
               '</div>\n'  +
               '<div class="kt-portlet__body" style="color: #5d5b6f">\n'  +
                  '{message}\n' +
               '</div>\n'  +
            '</div> \n' 
            ].join(""); 
              
            this.reaction_tmp = [
            '<div class="kt-portlet">\n'  +
               '<div class="kt-portlet__head">\n'  +
                  '<div class="kt-portlet__head-label">\n'  +
                     '<span class="kt-portlet__head-icon">\n'  +
                     '<img src="' + Site.figure_url + '?figure={figurePath}&headonly=1&direction=2&head_direction=2&action=&gesture=&" alt="image">\n' +
                     '</span>\n'  +
                     '<h3 class="kt-portlet__head-title">\n'  +
                        '<a href="#" data-toggle="modal" data-target="#actionModal" data-id="{reporter}">{reporter}</a> - <small>{timestamp}</small>\n'  +
                     '</h3>\n'  +
                  '</div>\n'  +
               '</div>\n'  +
               '<div class="kt-portlet__body" style="color: #5d5b6f">\n'  +
                  '{message}\n' +
               '</div>\n'  +
               '<div class="kt-portlet__foot kt-hidden">\n'  +
                  '<div class="row">\n'  +
                     '<div class="col-lg-6">\n'  +
                        'Portlet footer: \n'  +
                     '</div>\n'  +
                     '<div class="col-lg-6">\n'  +
                        '<button type="submit" class="btn btn-primary">Submit</button>\n'  +
                        '<span class="kt-margin-left-10">or <a href="#" class="kt-link kt-font-bold">Cancel</a></span>\n'  +
                     '</div>\n'  +
                  '</div>\n'  +
               '</div>\n'  +
            '</div> \n' 
            ].join(""); 

            self.ajax_manager.post("/housekeeping/api/help/getticket", {post: id}, function (result) {  
                             
                var reactions_template = $(self.ticket_tmp.replace(/{figurePath}/g, result.ticket.user.look).replace(/{reporter}/g, result.ticket.user.username)
                .replace(/{timestamp}/g, result.ticket.timestamp).replace(/{subject}/g, result.ticket.subject)
                .replace(/{message}/g, result.ticket.message));
              
                for (var x = 0; x < result.reactions.length; x++)
                {
                    var ticket_reactions = result.reactions[x];
                    var ticket_template  = $(self.reaction_tmp.replace(/{figurePath}/g, ticket_reactions.user.look).replace(/{reporter}/g, ticket_reactions.user.username)
                    .replace(/{timestamp}/g, ticket_reactions.timestamp).replace(/{message}/g, ticket_reactions.message));
                  
                    $("#responses").append(ticket_template);
                }
              
                for (var i = 0; i < result.logs.length; i++)
                {
                    var logs = result.logs[i];

                    if(logs.type == "SEND") { var icon = "send" }
                    if(logs.type == "CHANGE") { var icon = "refresh" }
                  
                    var notification_template = $(self.notification_tmp.replace(/{type}/g, logs.type)
                    .replace(/{timestamp}/g, logs.timestamp).replace(/{assistant}/g, logs.assistant)
                    .replace(/{value}/g, logs.value).replace(/{option}/g, icon));
                  
                    $(".kt-notification").append(notification_template);
                }
              
                $('[name=ticketId]').val(result.ticket.id);
                $("#userinfo").append(reactions_template);
              
                $("#goBack").unbind().click(function() {
                    help.goBack();
                });
              
                $("#status").selectpicker('val', result.ticket.status);
              
                $("#status").on("change", function() {
                    help.updateStatus(id, $(this).val());
                });
              
            });
        },
      
        loadTickets: function(roleid) {
            var datatableTickets = function() {
            if ($('#kt_datatable_tickets').length === 0) {
                return;
            }

            var t;
            t = $("#kt_datatable_tickets").KTDatatable({
               data: {
                   type: 'remote',
                   source: {
                     read: {
                       url: '/housekeeping/api/help/gethelptickets',
                       headers: {'Authorization': 'housekeeping_website_helptool' }
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
                   width: 50,
                   template: function(data) {
                       return '<span class="kt-font">' + data.id + '</span>';
                   }
               }, {
                   field: "subject",
                   title: "Subject",
                   template: function(data) {
                       return '<a href="#" class="viewTicket"><span class="kt-font">' + data.subject + '</span></a>';
                   }
               }, {
                   field: "username",
                   title: "Reporter"
               }, {
                   field: "practitioner",
                   title: "Staff"
               }, {
                   field: "timestamp",
                   title: "Timestamp"
               }, {
                  field: "status",
                  title: "Status",
                  sortable: "desc",
                  template: function(t) {
                    var e = {
                        "in_treatment": {
                            title: "IN TREATMENT",
                            class: "kt-badge--warning"
                        },
                        "closed": {
                            title: "CLOSED",
                            class: " kt-badge--dark"
                        },
                        "open": {
                            title: "OPEN",
                            class: " kt-badge--danger"
                        },
                        "wait_reply": {
                            title: "WAITING",
                            class: " kt-badge--info"
                        }
                    };
                    return '<span class="kt-badge ' + e[t.status].class + ' kt-badge--inline kt-badge--pill">' + e[t.status].title + "</span>"
                    }
               }]
            }), $("#kt_form_status").on("change", function() {
                t.search($(this).val().toLowerCase(), "status");
            }), $("#kt_form_status").selectpicker(),
              
                $("#kt_datatable_tickets_reload").on("click", function() {
                $("#kt_datatable_tickets").KTDatatable("reload")
            })
            }
                
            datatableTickets();
          
            $("body").unbind().on('click', '.viewTicket', (e) => {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
              
                $("#responses").fadeIn();
                $("#sendmessage").fadeIn();
                $("#userinfo").fadeIn();
                $("#notifications").fadeIn();
                $("#ticketTable").fadeOut();
              
                blockPageInterfaceSubmit.init();
                help.ticketRequest(id);
            });   
        }
        
    }
}();

jQuery(document).ready(function() {
    help.init();  
    help.loadTickets();
  
    tinymce.init({
         selector:   "textarea",
         width:      '100%',
         height:     270,
         plugins:    "advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern imagetools codesample",
         statusbar:  true,
         menubar:    true,
         toolbar:    "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
     });
});
