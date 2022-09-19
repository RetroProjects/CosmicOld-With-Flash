"use strict";
var KTWizard2 = function() {
    var e, r, i;
    return {
        init: function() {
            var t;
            KTUtil.get("kt_wizard_v2"), e = $("#kt_form"), (i = new KTWizard("kt_wizard_v2", {
                startStep: 1
            })).on("beforeNext", function(e) {
                !0 !== r.form() && e.stop()
            }), i.on("beforePrev", function(e) {
                !0 !== r.form() && e.stop()
            }), r = e.validate({
                ignore: ":hidden",
                rules: {
                    fname: {
                        required: !0
                    },
                    lname: {
                        required: !0
                    },
                    phone: {
                        required: !0
                    },
                    emaul: {
                        required: !0,
                        email: !0
                    },
                    address1: {
                        required: !0
                    },
                    postcode: {
                        required: !0
                    },
                    city: {
                        required: !0
                    },
                    state: {
                        required: !0
                    },
                    country: {
                        required: !0
                    },
                    delivery: {
                        required: !0
                    },
                    packaging: {
                        required: !0
                    },
                    preferreddelivery: {
                        required: !0
                    },
                    locaddress1: {
                        required: !0
                    },
                    locpostcode: {
                        required: !0
                    },
                    loccity: {
                        required: !0
                    },
                    locstate: {
                        required: !0
                    },
                    loccountry: {
                        required: !0
                    },
                    ccname: {
                        required: !0
                    },
                    ccnumber: {
                        required: !0,
                        creditcard: !0
                    },
                    ccmonth: {
                        required: !0
                    },
                    ccyear: {
                        required: !0
                    },
                    cccvv: {
                        required: !0,
                        minlength: 2,
                        maxlength: 3
                    }
                }
            })
        }
    }
}();