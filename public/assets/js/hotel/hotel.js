var HotelLoading;
var Hotel;
var tryConnectUIServer;
var UIServerOpened;
var UI;

$(function()
{
    particlesJS.load("loading-background", "/assets/js/particles-loading-config.json");

    HotelLoading = new HotelLoadingInterface();
    HotelLoading.init();
    HotelLoading.start_step("load_files");
});

!function() {
    "use strict";
    window.WebSocket = window.WebSocket || window.MozWebSocket;
}();


function UIServer()
{
    this.port = 2096 + (window.location.hostname === "cosmicproject.online" ? 10 : 0);
    this.connection = null;
    this.event_listener = {};
    this.hotel_loaded = false;

    /*
    * Main initiation
    * */
    this.init = function ()
    {
        var self = this;
        if (self.connection !== null)
        {
            self.connection.onopen = null;
            self.connection.onmessage = null;
            self.connection.onerror = null;
            try
            {
                self.connection.close()
            }
            catch (b)
            {}
            self.connection = null;
        }

        try
        {
            self.connection = new WebSocket("wss://" + window.location.hostname + ":" + this.port + "/" + User.id + "/" + User.shuttle_token);
        }
        catch (e)
        {}

        self.connection.onopen = function ()
        {
            self.send({packet: 1});
        };

        self.connection.onerror = function (error)
        {
        };

        self.connection.onclose = function ()
        {
            if (self.connection !== null)
            {
                self.connection.onopen = null;
                self.connection.onmessage = null;
                self.connection.onerror = null;
                try
                {
                    self.connection.close()
                }
                catch (b)
                {}
                self.connection = null;
            }

            if (self.technical_view === null)
                self.technical_view = Hotel.technical_view("disconnected_interface");

            if (UI)
                UI.destroy();

            tryConnectUIServer = setTimeout(function ()
            {
                self.init();
            }, 2000);
        };

        self.connection.onmessage = function (message)
        {
            console.log(message);
            var handler;
            try
            {
                handler = JSON.parse(message.data);
            }
            catch (e)
            {
                console.log("Le message reçu n'est pas au format JSON", message.data);
                return;
            }
            console.log(handler);
        };
    };

    /*
    * Send message
    * */
    this.send = function (data)
    {
        var self = this;
        if (self.connection !== null)
        {
            if (typeof data !== "object")
                return;
                console.log(data);
            if (self.connection.readyState === 1)
                self.connection.send(JSON.stringify(data));
        }
    };

    /*
    * Close connection
    * */
    this.close = function ()
    {
        var self = this;
        if (self.connection !== null)
        {
            self.connection.onopen = null;
            self.connection.onmessage = null;
            self.connection.onerror = null;
            try
            {
                self.connection.close()
            }
            catch (b)
            {}
            self.connection = null;
        }
    };

    /*
    * Event listener
    * */
    this.add_event_listener = function (name, callback)
    {
        this.event_listener[name] = callback;
    };

    this.remove_event_listener = function (name)
    {
        delete this.event_listener[name];
    };

    this.event_try_execute = function (name, handler)
    {
        if (this.event_listener.hasOwnProperty(name))
            this.event_listener[name](handler);

        return null;
    }
}

function HotelLoadingInterface()
{
    this.loading_container = null;

    this.init = function ()
    {
        this.loading_container = $(".loading-container");
    };

    this.start_step = function (step)
    {
        var self = this;
            if (step === "load_files")
                this.load_hotel();
            else
            {
                this.hide_bodytext(function ()
                {
                    if (step === "hotel_starting")
                    {
                        self.write_bodytext("... <span class=\"percent\">0%<br><a href=\"https://www.adobe.com/go/getflashplayer\" target=\"_blank\">Activer Flash</a></span>");
                        self.load_hotel();
                    }
                    else if (step === "hotel_soon")
                        self.write_bodytext(Locale.web_page_hotel_soon + " <span class=\"percent\">76%</span>");
                    else if (step === "hotel_new")
                        self.write_bodytext("...");
                    else if (step === "hotel_before_end") 
                        self.write_bodytext("...");
                    else if (step === "hotel_end")
                    {
                      
                        //window.parent.UIServer = new UIServer();
                        //window.parent.UIServer.init();

                        self.write_bodytext(Locale.web_page_hotel_welcome_at + " " + Site.name  + '!');
                        setTimeout(self.close_loading.bind(self), 2000);
                    }

                    self.show_bodytext();
                });
            }
      
    };

    this.load_file = function (file_id)
    {
        var self = this;
        var file_name = this.files[file_id];
        var script = document.createElement("script");
        $("body").append(script);
        script.onload = function () {
            self.loaded_files++;

            self.write_bodytext(Site.name + " " + Locale.web_page_hotel_loading);

            if (file_id + 1 < self.total_files)
            {
                file_id++;
                self.load_file(file_id);
            }
        };
        script.onerror = function () {
            self.write_bodytext(Locale.web_page_hotel_sometinhg_wrong_1 + " <a href=\"javascript:window.location.reload();\">" + Locale.web_page_hotel_sometinhg_wrong_2 +"</a> " + Locale.web_page_hotel_sometinhg_wrong_3);
        };

    };

    this.load_hotel = function ()
    {
        var base = Site.game_url;
        var prod = (Client.external_base) ? '/' + Client.external_base + '/' : '/';
        console.log(Site.game_url + "/" + Client.external_figuremap)
        swfobject.embedSWF(
            Site.game_url + "/" + Client.external_base + '/' + Client.client_swf + "?v=" + Configuration.revision,
            "flash-container",
            "100%",
            "100%",
            "11.1.0",
            "//habboo-a.akamaihd.net/habboweb/63_1d5d8853040f30be0cc82355679bba7c/10349/web-gallery/flash/expressInstall.swf",
            {
              "client.allow.cross.domain": "1",
              "client.notify.cross.domain": "0",
              "connection.info.host": Client.client_host,
              "connection.info.port": Client.client_port,
              "site.url": '/',
              "url.prefix": '/',
              "client.reload.url": "/hotel",
              "client.fatal.error.url": "/hotel",
              "client.connection.failed.url": "/hotel",
              "external.override.texts.txt": Site.game_url + "/" + Client.external_override_variables,
              "external.override.variables.txt": Site.game_url + "/" + Client.external_override_texts,
              "external.variables.txt": Site.game_url + "/" + Client.external_variables,
              "external.texts.txt": Site.game_url + "/" + Client.external_texts,
              "external.figurepartlist.txt": Site.game_url + "/" + Client.external_figurepartlist,
              "flash.dynamic.avatar.download.configuration": Site.game_url + "/" + Client.external_figuremap,
              "productdata.load.url": Site.game_url + "/" + Client.external_productdata,
              "furnidata.load.url": Site.game_url + "/" + Client.external_figuredata,
              "sso.ticket": User.ticket,
              "account_id": User.id,
              "ads.domain": "",
              "processlog.enabled": "1",
              "client.starting": "Frank brengt je koffers naar je hotelkamer!",
              "flash.client.url": base + prod,
              "flash.client.origin": "popup",
            },
            {
                "base": base + prod,
                "allowScriptAccess": "always",
                "menu": "false",
                "wmode": "opaque"
            },
            null,
            this.check_swf_loading
        );
    };

    this.check_swf_loading = function (e)
    {
        var ie_flash;

        try
        {
            ie_flash = (window.ActiveXObject && (new ActiveXObject("ShockwaveFlash.ShockwaveFlash")) !== false)
        }
        catch(err)
        {
            ie_flash = false;
        }

        var _flash_installed = ((typeof navigator.plugins != "undefined" && typeof navigator.plugins["Shockwave Flash"] == "object") || ie_flash);

        if (_flash_installed !== true)
        {
            HotelLoading.flash_missing();
            return false;
        }

        window.HabboFlashClient.flashInterface = document.getElementById("flash-container");
        $("#flash-container").css({height: "100%"});

        setTimeout(function ()
        {
            $(".loading-container").fadeOut("slow");
        }, 20000);
    };

    this.flash_missing = function ()
    {
        var self = this;

        this.hide_bodytext(function ()
        {
            self.write_bodytext(Locale.web_hotel_active_flash_1 + "<br><br><a href=\"https://www.adobe.com/go/getflashplayer\" target=\"_blank\">" + Locale.web_hotel_active_flash_2 + "</a> " + Locale.web_hotel_active_flash_3);
            self.show_bodytext();
        });
    };

    this.hide_bodytext = function (callback)
    {
        this.loading_container.find(".loading-bodytext").fadeOut(1000, callback);
    };

    this.show_bodytext = function (callback)
    {
        this.loading_container.find(".loading-bodytext").fadeIn(callback);
    };

    this.write_bodytext = function (text)
    {
        this.loading_container.find(".loading-bodytext").html(text);
    };

    this.close_loading = function ()
    {
        var self = this;
        this.loading_container.fadeOut(1000, function ()
        {
            $(this).remove();
            self.loading_container = null;
        });
    };

    this.open_loading = function (text)
    {

        if (this.loading_container === null)
        {
            var template = [
                '<div class="loading-container" style="display: none;">\n' +
                '    <div id="loading-background"></div>\n' +
                '    <div class="loading-content">\n' +
                '        <div class="loading-bodytext"></div>\n' +
                '    </div>\n' +
                '</div>'
            ].join("");
            this.loading_container = $(template).prependTo("body");

            particlesJS.load("loading-background", "assets/js/particles-loading-config.json");
        }

        this.loading_container.find(".loading-bodytext").html(text);
        this.loading_container.fadeIn(500);
    };

    this.update_loading = function (percent)
    {
        var rounded_percent = Math.round((percent * 100));
        this.loading_container.find(".loading-bodytext span.percent").text(rounded_percent + "%");
        
        if (rounded_percent >= 100)
            this.start_step("hotel_before_end");
    };
}

function initializeExternalInterfaces() {
    "use strict";
    window.HabboFlashClient.init(document.getElementById("flash-container"));
}

! function() {
    "use strict";
    var n = "*";

    window.MainApp = {
        web: window.parent,
        postMessage: function (e)
        {
            // window.parent.postMessage(e, n)
        },
        disconnect: function ()
        {
            HotelLoading.open_loading("Je bent uitgevallen of je bent ingelogd op een andere browser.<br><a href=\"javascript:window.location.reload();\">Herlaad Leet Hotel</a>");
        }
    }
}(),
    function() {
        "use strict";
        var n = false;

        window.FlashExternalInterface = {};

        window.FlashExternalInterface.signoutUrl = Site.url + "/logout";

        window.FlashExternalInterface.closeHabblet = function (n)
        {};

        /*window.FlashExternalInterface.disconnect = function ()
        {
            window.MainApp.disconnect();
        };*/

        window.FlashExternalInterface.heartBeat = function ()
        {
            window.HabboFlashClient.started = true;
        };

        window.FlashExternalInterface.legacyTrack = function (n, e, a)
        {
            window.HabboFlashClient.started = true;
            window.HabboTracking.track(n, e, a);
        };

        window.FlashExternalInterface.loadConversionTrackingFrame = function ()
        {};

        window.FlashExternalInterface.logCrash = function(n)
        {
            window.HabboWebApi.logCrash(n, function (n) {
                n && window.FlashExternalInterface.track("log", "fatal", "Can't log login crash: " + n);
            });
        };

        window.FlashExternalInterface.logDebug = function (n)
        {
            window.FlashExternalInterface.track("log", "debug", n);
        };

        window.FlashExternalInterface.logError = function (n)
        {
            window.HabboFlashClient.started = true;
            window.HabboWebApi.logError(n, function (n) {
                n && window.FlashExternalInterface.track("log", "error", "Can't log login error: " + n);
            });
        };

        window.FlashExternalInterface.logWarn = function (n)
        {
            window.FlashExternalInterface.track("log", "warn", n);
        };

        window.FlashExternalInterface.logLoginStep = function (e, a)
        {
            window.FlashExternalInterface.track("clientload", e, a);
            window.HabboFlashClient.started = true;
            n || "client.init.core.running" !== e || (n = true, window.MainApp.postMessage({
                call: "hotel-ready"
            }));
            window.HabboWebApi.logLoginStep(e, a, function (n) {
                n && window.FlashExternalInterface.track("log", "error", "Can't log login step: " + n);
            });
            if (e === "client.init.core.init")
                HotelLoading.start_step("hotel_soon");
            else if (e === "client.init.config.loaded")
                HotelLoading.start_step("hotel_end");
        };

        window.FlashExternalInterface.logout = function ()
        {
            if (window.opener)
            {
                window.opener.location = FlashExternalInterface.signoutUrl;
                window.close();
            } else
                window.location = FlashExternalInterface.signoutUrl;
        };

        window.FlashExternalInterface.openExternalPage = function (n)
        {};

        window.FlashExternalInterface.openHabblet = function (n, e)
        {};

        window.FlashExternalInterface.openWebHabblet = function (n, e)
        {
            window.HabboTracking.track("openwebhablet", n, e);
            var a = window.HabboPageTransformer.transformHabblet(n, e);
            window.FlashExternalInterface.openPage(a);
        };

        window.FlashExternalInterface.openExternalLink = function (n, e)
        {
            window.HabboTracking.track("openexternallink", n, e);
            var a = window.HabboPageTransformer.transformHabblet(n, e);
            window.FlashExternalInterface.openPage(a);
        };

        window.FlashExternalInterface.openPage = function (href)
        {
            href = window.HabboPageTransformer.translate(href);
            window.HabboTracking.track("openpage", "", href);

            if (!href)
                href = "home";

            if (href !== "logout")
            {
                Hotel.open_link(href);
            }
            else
                document.getElementById("disconnected-container").style.display = "block";
        };

        window.FlashExternalInterface.track = function(n, e, a)
        {
            window.HabboFlashClient.started = true;
            window.HabboTracking.track(n, e, a);
        };

        window.FlashExternalInterface.showAvatareditor = function ()
        {};

        window.FlashExternalInterface.updateLoadingScreen = function (n)
        {
            HotelLoading.update_loading(n);
        };

        window.FlashExternalInterface.roomChatColorInvisible = function ()
        {
            // console.log("invisible!");
        };

        window.FlashExternalInterface.roomChatColorVisible = function ()
        {
            // console.log("visible!");
        };

        window.FlashExternalInterface.consolelog = function (n)
        {
            console.log(n);
        };

        window.FlashExternalInterface.updateFigure = function (n)
        {};

        window.FlashExternalInterface.updateName = function (n)
        {};

        window.FlashExternalInterface.openMinimail = function (n)
        {
            window.HabboTracking.track("minimail", "open", n);
        };

        window.FlashExternalInterface.openNews = function ()
        {
            window.HabboTracking.track("news", "open", "");
        };

        window.FlashExternalInterface.openAvatars = function ()
        {
            window.FlashExternalInterface.openPage("/settings/avatars");
        };

        window.FlashExternalInterface.showInterstitial = function ()
        {};

        window.FlashExternalInterface.subscriptionUpdated = function (n)
        {};

        window.FlashExternalInterface.updateBuildersClub = function (n)
        {};
    }(),
    function() {
        "use strict";

        function n (n)
        {
            if (n.data)
            {
                var t = n.data;

                switch (t.call)
                {
                    case "open-link":
                        ev(t.target);
                        break;
                    case "open-room":
                        a(t.target);
                        break;
                    case "interstitial-status":
                        o(t.target);
                }
            }
        }

        function a (n)
        {
            n.indexOf("r-hh") >= 0 ? t.openroom(n) : ev("navigator/goto/" + n);
        }

        function o (n)
        {
            window.HabboFlashClient.flashInterface.interstitialCompleted(n);
        }
        window.addEventListener("message", n, false);
        window.HabboFlashClient = {
            started: false,
            init: function (n)
            {
                window.HabboTracking.track("clientload", "starting", "Initalizing Habbo Client.");
                window.FlashExternalInterface.logLoginStep("web.view.start");
                n || (console.error("Invalid FlashClient. Can't use JS->Flash interface."), window.FlashExternalInterface.logLoginStep("web.flash_missing"));
                // tf = n;
                setTimeout(function () {
                    window.HabboFlashClient.started || window.FlashExternalInterface.logLoginStep("client.init.swf.error");
                }, 3e4);

            }
        }

    }(), window.addEventListener("load", initializeExternalInterfaces, false),
    function() {
        "use strict";

        function n (n, e)
        {
            return 0 === n.indexOf(e);
        }

        var e = {
            "credits": "shop",
            "creditflow": "shop",
            "news": "community/category/all/1"
        };

        window.HabboPageTransformer = {
            translate: function (a)
            {
                for (var o in e)
                    if (e.hasOwnProperty(o) && n(a, o))
                        return e[o];

                return a.replace(Site.url + "/", "").replace(Site.url, "");
            },
            transformHabblet: function (n, e)
            {
                var href = n.replace(new RegExp('%3A', 'g'), ":").replace(Site.url + "/", "").replace(Site.url, "");
                return href.replace(new RegExp(':', 'g'), "/");
            }
        }
    }(),
    function() {
        "use strict";

        window.HabboShopApi = {};
        window.HabboShopApi.checkOffer = function (e)
        {}
    }(),
    function() {
        "use strict";
        var n = function (n, e, a)
        {
            // "console" in window && "log" in console && console.log("action = [" + n + "], label = [" + e + "], value = [" + a + "]")
        };
        window.HabboTracking = {
            track: function(e, a, o)
            {
                n(e, a, o);
                "clientload" === e && window.HabboTracking.gaTrack(e, a);
            },
            gaTrack: function(n, e)
            {
                window._gaq && window._gaq.push(["_trackEvent", n, e]);
            }
        }
    }(),
    function() {
        "use strict";


        window.HabboWebApi = {};

        window.HabboWebApi.checkName = function (e, a)
        {};

        window.HabboWebApi.claimName = function (e, a)
        {};

        window.HabboWebApi.saveFigure = function (e, a, o)
        {};

        window.HabboWebApi.selectRoom = function (e, a)
        {};

        window.HabboWebApi.logCrash = function (e, a)
        {};

        window.HabboWebApi.logError = function (e, a)
        {};

        window.HabboWebApi.logLoginStep = function (e, a, o)
        {}
    }(),
    function() {
        "use strict";
        window.NewUserReception = {};

        window.NewUserReception.checkName = function (n)
        {};

        window.NewUserReception.chooseRoom = function (n)
        {};

        window.NewUserReception.claimName = function (n)
        {};

        window.NewUserReception.logStep = function (n) {
            window.HabboTracking.track("nux", "log", n)
        };

        window.NewUserReception.saveOutfit = function (n, e)
        {};
    }(),
    function() {
        "use strict";

        function n() {
            window.SSA_CORE.BrandConnect.engage();
            var n = document.getElementById("ssaInterstitialTopBar"),
                e = n.innerHTML;
            n.innerHTML = "";
            var a = document.createElement("div");
            a.className = "ssaInterstitialTopBarInnerContainerLeft";
            var o = document.createElement("div");
            o.className = "ssaInterstitialTopBarInnerContainerRight";
            var t = document.createElement("div");
            t.className = "ssaTopBarCloseButton", t.setAttribute("onClick", 'SSA_CORE.close("ssaBrandConnect")'), t.innerHTML = "";
            var i = document.createElement("span");
            i.className = "ssaTopBarTextSpan", i.innerHTML = e, a.appendChild(t), a.appendChild(i), n.appendChild(o), n.appendChild(a);
            var s = document.getElementById("ssaInterstitialBottomBar"),
                r = document.createElement("div");
            r.className = "ssaBottomBarInnerLeft";
            var l = document.createElement("div");
            l.className = "ssaBottomBarInnerRight", s.appendChild(l), s.appendChild(r)
        }

        function e(n) {
            n && n.length > 0 ? window.HabboFlashClient.flashInterface.supersonicAdsOnCampaignsReady(n.length.toString()) : window.HabboFlashClient.flashInterface.supersonicAdsOnCampaignsReady("0")
        }

        function a() {
            window.HabboFlashClient.flashInterface.supersonicAdsOnCampaignOpen()
        }

        function o() {
            window.HabboFlashClient.flashInterface.supersonicAdsOnCampaignClose()
        }

        function t() {
            window.HabboFlashClient.flashInterface.supersonicAdsOnCampaignCompleted()
        }

        function i() {
            var n = document.createElement("script");
            n.setAttribute("src", s), document.getElementsByTagName("head")[0].appendChild(n)
        }
        var s = "https://a248.e.akamai.net/ssastatic.s3.amazonaws.com/inlineDelivery/delivery.min.gz.js",
            r = window.flashvars || {},
            l = r.supersonic_devmode,
            c = r.supersonic_application_key,
            w = window.ssa_json = {
                applicationKey: c,
                onCampaignsReady: e,
                onCampaignOpen: a,
                onCampaignClose: o,
                onCampaignCompleted: t,
                pagination: !1,
                customCss: r.supersonic_custom_css
            };
        l ? (r.supersonic_demo_campaigns && (w.demoCampaigns = 1), w.applicationUserId = r.supersonic_admin_id || r.account_id) : w.applicationUserId = r.account_id, window.supersonicAdsCamapaignEngage = n, window.supersonicAdsOnCampaignsReady = e, window.supersonicAdsOnCampaignOpen = a, window.supersonicAdsOnCampaignClose = o, window.supersonicAdsOnCampaignCompleted = t, window.supersonicAdsLoadCampaigns = i
    }(),
    function() {
        "use strict";
        window.TargetedWebOffer = {};
        window.TargetedWebOffer.checkOffer = function ()
        {
            console.log("Checking for offer..."), window.HabboShopApi.checkOffer(function(n, e) {
                return n ? void window.HabboFlashClient.flashInterface.targetedWebOfferCheckFailed() : void window.HabboFlashClient.flashInterface.targetedWebOfferCheckResponse(e)
            });
        }
    }();
