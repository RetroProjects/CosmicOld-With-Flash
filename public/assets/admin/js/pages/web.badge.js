var Badges = function() {

    return {
        init: function() {        
            $("#badgeAccept, #badgeDecline").unbind().click(function() {
                Badges.action($(this).data("uri"), $(this).data("badge"))
            });
         
        },
      
        action: function(action, id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/badge/action", {id: id, action: action}, function (result) {
                if(result.status == "success") {
                    $("#" + id).remove();
                }
            });
        },
    }
  
}();

jQuery(document).ready(function() {
     Badges.init();
});