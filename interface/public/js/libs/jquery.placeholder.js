/*! http://mths.be/placeholder v2.0.7 by @mathias */
(function(m,f,c){function n(a){var b={},d=/^jQuery\d+$/;c.each(a.attributes,function(a,c){c.specified&&!d.test(c.name)&&(b[c.name]=c.value)});return b}function g(a,b){var d=c(this);if(this.value==d.attr("placeholder")&&d.hasClass("placeholder"))if(d.data("placeholder-password")){d=d.hide().next().show().attr("id",d.removeAttr("id").data("placeholder-id"));if(!0===a)return d[0].value=b;d.focus()}else this.value="",d.removeClass("placeholder"),this==f.activeElement&&this.select()}function j(){var a,
b=c(this),d=this.id;if(""==this.value){if("password"==this.type){if(!b.data("placeholder-textinput")){try{a=b.clone().attr({type:"text"})}catch(e){a=c("<input>").attr(c.extend(n(this),{type:"text"}))}a.removeAttr("name").data({"placeholder-password":!0,"placeholder-id":d}).bind("focus.placeholder",g);b.data({"placeholder-textinput":a,"placeholder-id":d}).before(a)}b=b.removeAttr("id").hide().prev().attr("id",d).show()}b.addClass("placeholder");b[0].value=b.attr("placeholder")}else b.removeClass("placeholder")}
var h="placeholder"in f.createElement("input"),k="placeholder"in f.createElement("textarea"),e=c.fn,l=c.valHooks;h&&k?(e=e.placeholder=function(){return this},e.input=e.textarea=!0):(e=e.placeholder=function(){this.filter((h?"textarea":":input")+"[placeholder]").not(".placeholder").bind({"focus.placeholder":g,"blur.placeholder":j}).data("placeholder-enabled",!0).trigger("blur.placeholder");return this},e.input=h,e.textarea=k,e={get:function(a){var b=c(a);return b.data("placeholder-enabled")&&b.hasClass("placeholder")?
"":a.value},set:function(a,b){var d=c(a);if(!d.data("placeholder-enabled"))return a.value=b;""==b?(a.value=b,a!=f.activeElement&&j.call(a)):d.hasClass("placeholder")?g.call(a,!0,b)||(a.value=b):a.value=b;return d}},h||(l.input=e),k||(l.textarea=e),c(function(){c(f).delegate("form","submit.placeholder",function(){var a=c(".placeholder",this).each(g);setTimeout(function(){a.each(j)},10)})}),c(m).bind("beforeunload.placeholder",function(){c(".placeholder").each(function(){this.value=""})}))})(this,document,
jQuery);