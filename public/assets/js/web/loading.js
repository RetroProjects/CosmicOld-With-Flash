var SiteLoading;
var PageLoading;

$(function ()
{
    SiteLoading = new SiteLoadingInterface();
    SiteLoading.init();
    SiteLoading.load_file(0);
    PageLoading = new PageLoadingInterface();
});

function SiteLoadingInterface()
{
    this.files = [
        "web.badge",
        "web.locale",
        "web.pages",
        "web.core"
    ];
    this.loaded_files = 0;
    this.total_files = 0;
    this.loading_container = null;
    this.cache_id = null;

    this.init = function ()
    {
        console.log(
            "Cosmic - All rights reserved\n\n" +
            "Everything you do here falls under your own responsibility. Never give your code if someone asks for it. If you paste a code here, you will never get free credits or other items.\n\n" +
            "- Cosmic Dev");
        this.total_files = this.files.length;
        this.loading_container = $(".loading-container");

        this.cache_id = Configuration.cache;
    }
  
    this.load_file = function (file_id)
    {
        var self = this;
        var file_name = this.files[file_id];
        var script = document.createElement("script");
        $("body").append(script);
        script.onload = function ()
        {
            self.loaded_files++;

            var percent = Math.floor(self.loaded_files / self.total_files * 100);

            self.loading_container.find(".c100").attr("class", "c100 p" + percent + " center");

            if (file_id + 1 < self.total_files)
            {
                file_id++;
                self.load_file(file_id);
            }
            else
            {
                setTimeout(function ()
                {
                    self.close_loading();
                }, 100);
            }
        };
        script.onerror = function ()
        {
            console.log("Oops, file \"" + file_name + "\" not found.");
            self.write_bodytext("Oops, something went wrong. <a href=\"javascript:window.location.reload();\">Reload the page</a>.");
        };
        script.src = "/assets/js/web/" + file_name + ".js?" + this.cache_id;
    };

    this.write_bodytext = function (text)
    {
        this.loading_container.find(".loading-bodytext").html(text);
    };

    this.close_loading = function ()
    {
        this.loading_container.fadeOut(1000, function ()
        {
            $(this).remove();
        });
    };
}

function PageLoadingInterface()
{
    this.show = function ()
    {
        $(".page-loading").stop().fadeIn(500);
    };

    this.hide = function ()
    {
        $(".page-loading").stop().fadeOut(500);
    };
}
