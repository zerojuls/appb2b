//jquery.fileinput.js
$.fn.customFileInput=function(c){var e=$(this);var d=jQuery.extend({button_position:"right",classes:e.attr("class"),feedback_text:"choose a file...",button_text:"Browse",button_change_text:"Change"},c);e.addClass("customfile-input").focus(function(){b.addClass("customfile-focus");e.data("val",e.val())}).blur(function(){b.removeClass("customfile-focus");$(this).trigger("checkChange")}).bind("disable",function(){e.attr("disabled",true);b.addClass("customfile-disabled")}).bind("enable",function(){e.removeAttr("disabled");b.removeClass("customfile-disabled")}).bind("checkChange",function(){if(e.val()&&e.val()!=e.data("val")){e.trigger("change")}}).bind("change",function(){var h=$(this).val().split(/\\/).pop();var g="customfile-ext-"+h.split(".").pop().toLowerCase();f.removeClass(f.data("fileExt")||"").addClass(g).data("fileExt",g).addClass("has-file").children("span").text(h)}).click(function(){e.data("val",e.val());setTimeout(function(){e.trigger("checkChange")},100)});var b=$('<div class="customfile">');var f=$('<div class="uneditable-input '+d.classes+'" aria-hidden="true"><span>'+d.feedback_text+"</span></div>").appendTo(b);var a=$('   <span class="add-on btn">Upload</span>');if("right"===d.button_position){a.insertAfter(f)}else{a.insertBefore(f)}if(e.is("[disabled]")){e.trigger("disable")}else{b.click(function(){e.trigger("click")})}b.insertAfter(e);e.insertAfter(b);return $(this)};
//jquery.touchdown.js
(function(a){a.fn.Touchdown=function(){return this.each(function(){$this=a(this);var b=$this.parents().length,c=$this.find("a"),d="Navigate",e;if($this.attr("title")){d=$this.attr("title")}e+='<option value="">'+d+"</option>";for(var f=0;f<c.length;f++){var g=a(c[f]),h=(g.parents().length-b)/2-1,i="";while(h>0){i+="  ";h--}e+='<option value="'+g.attr("href")+'">'+i+g.text()+"</option>"}$this.addClass("touchdown-list").after('<select class="touchdown"> '+e+"</select>");$this.next("select").change(function(){window.location=a(this).val()})})}})(jQuery)