if (typeof(window.FlashExternalnterface) === "undefined")
{
  window.FlashExternalInterface = {};
}

window.FlashExternalInterface.logLoginStep = function(b)
{
  if (b == "client.init.start")
  {
    document.getElementById('loader_bar2').style = "width:20%;";
    document.getElementById("loadingpercent").innerHTML = "Het hotel is aan het laden... 10%";
  }
  if (b == "client.init.core.init")
  {
    document.getElementById('loader_bar2').style = "width:50%;";
    document.getElementById("loadingpercent").innerHTML = "Het hotel is aan het laden... 25%";
  }
  if (b == "client.init.auth.ok")
  {
    document.getElementById('bar2').style = "display: none;";
    document.getElementById("loadingpercent").innerHTML = "Het hotel is aan het laden... 50%";
  }
  if (b == "client.init.localization.loaded")
  {
    document.getElementById('loader_bar').style = "width:50%;";
    document.getElementById("loadingpercent").innerHTML = "Het hotel is aan het laden... 75%";
  }
  if (b === "client.init.config.loaded")
  {
    setTimeout(function()
    {
      document.getElementById('loader_bar').style = "width:100%;";
      document.getElementById("loadingpercent").innerHTML = "Het hotel is aan het laden... 100%";
    }, 3000);
    setTimeout(function()
    {
      $('#loader-wrapper').fadeOut('0');
    }, 5000);
  }
}

$('#content').show();

if (!swfobject.hasFlashPlayerVersion("1"))
{
  document.getElementById("loader-wrapper").style.display = "none";
}

var loadvars = Object.assign({}, flashvars, connectionvars);



window.FlashExternalInterface.logout = function()
{
  if (window.location != window.parent.location) window.parent.location = Site.url + "/logout";
}