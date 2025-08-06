// (function ($) {
//   let max_records = null;

//   Drupal.behaviors.cornell_mixpanel = {
//     attach: function (context, settings) {
//       max_records = settings.cornell_mixpanel;
//       const configObj = JSON.parse(settings.cornell_mixpanel);
//       console.log(configObj);
//     }
//   }
// })(jQuery);


(function ($) {
  // console.log('Cornell Mixpanel JavaScript loaded');
  document.addEventListener("DOMContentLoaded", function () {
    console.log('Cornell Mixpanel JavaScript loaded');
  });
  // Must attach to page before settings are available.
  Drupal.behaviors.cornell_mixpanel = {
    // Function to download and attach the mixpanel library
    initmplib: function (context, settings) {
      const configObj = JSON.parse(settings.cornell_mixpanel);
      const MIXPANEL_PROJECT_TOKEN = configObj.token;
      const MIXPANEL_PROXY_DOMAIN = configObj.proxy_server;
      if (MIXPANEL_PROXY_DOMAIN != "https://api-js.mixpanel.com") {
        const MIXPANEL_CUSTOM_LIB_URL = MIXPANEL_PROXY_DOMAIN + "/lib.min.js";
      }

      (function (f, b) {
        if (!b.__SV) {
          var e, g, i, h;
          window.mixpanel = b;
          b._i = [];
          b.init = function (e, f, c) {
            function g(a, d) {
              var b = d.split(".");
              2 == b.length && ((a = a[b[0]]), (d = b[1]));
              a[d] = function () {
                a.push([d].concat(Array.prototype.slice.call(arguments, 0)));
              };
            }
            var a = b;
            "undefined" !== typeof c ? (a = b[c] = []) : (c = "mixpanel");
            a.people = a.people || [];
            a.toString = function (a) {
              var d = "mixpanel";
              "mixpanel" !== c && (d += "." + c);
              a || (d += " (stub)");
              return d;
            };
            a.people.toString = function () {
              return a.toString(1) + ".people (stub)";
            };
            i =
              "disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking start_batch_senders people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(
                " "
              );
            for (h = 0; h < i.length; h++) g(a, i[h]);
            var j = "set set_once union unset remove delete".split(" ");
            a.get_group = function () {
              function b(c) {
                d[c] = function () {
                  call2_args = arguments;
                  call2 = [c].concat(Array.prototype.slice.call(call2_args, 0));
                  a.push([e, call2]);
                };
              }
              for (
                var d = {},
                  e = ["get_group"].concat(
                    Array.prototype.slice.call(arguments, 0)
                  ),
                  c = 0;
                c < j.length;
                c++
              )
                b(j[c]);
              return d;
            };
            b._i.push([e, f, c]);
          };
          b.__SV = 1.2;
          e = f.createElement("script");
          e.type = "text/javascript";
          e.async = !0;
          e.src =
            "undefined" !== typeof MIXPANEL_CUSTOM_LIB_URL
              ? MIXPANEL_CUSTOM_LIB_URL
              : "file:" === f.location.protocol &&
                "//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)
              ? "https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js"
              : "//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";
          g = f.getElementsByTagName("script")[0];
          g.parentNode.insertBefore(e, g);
        }
      })(document, window.mixpanel || []);
    },
    // Function to initialize our mixpanel tracking object
    initmpobj: function (context, settings) {
      if (typeof window.mixpanel == "undefined" || window.mixpanel === null) {
        return;
      }
      const configObj = JSON.parse(settings.cornell_mixpanel);
      mixpanel.init(configObj.token, {
        debug: configObj.debug_mode,
        track_pageview: true,
        persistence: "localStorage",
        api_host: configObj.proxy_server,
        ignore_dnt: configObj.ignore_dnt,
        cross_subdomain_cookie: configObj.cross_subdomain_cookie,
        record_heatmap_data: configObj.use_heatmap,
      });
    },
    // Function to initialize nav link tracking
    initnavlinktracking: function (context, settings) {
      if (typeof window.mixpanel == "undefined" || window.mixpanel === null) {
        return;
      }
      try {
        // Function tracks clicks only for children of nav elements
        mixpanel.track_links("nav a", "Navigation Clicks", function (element) {
          return {
            Section: element.innerText.trim()
          };
        });
        // Function to track all links. Location differentiates between navigation and content.
        mixpanel.track_links("a", "Link Click", function (element) {
          return {
            'Link Text': element.innerText.trim(),
            'Link Location' : element.closest('nav') == null ? 'Content' : 'Navigation'
          };
        });
      } catch (e) {
        // Skipping log due to mandate for no evidence of tracking in console.
        // console.error('Failed to initialize link tracking:', e);
      }
    },
    // attach function that Drupal runs after DOM load

    attach: function (context, settings) {
      // Use the once utility to ensure the behavior is applied only once
      once('init_cornell_mixpanel', document.querySelectorAll('html')).forEach((element) => {
        // Init Library
        this.initmplib(context, settings);
        // Init tracker
        this.initmpobj(context, settings);
        // Init link tracking
        this.initnavlinktracking(context, settings);
      });
    },
  };
})(jQuery);

