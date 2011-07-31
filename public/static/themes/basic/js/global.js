$(document).ready(function(){
    /**$("select").each(function(){
        var a=this;
        $(this).attr("size",$(this).find("option").length+1).wrap('<span class="ui-select" />').before('<span class="ui-select-value" />').bind("change, click",function(){
            $(this).hide().prev().html($(this).find("option:selected").text())
        }).after('<a class="ui-select-button button button-gray"><span></span></a>').next().click(function(){
            if($(a).toggle().is(":visible")){
                $(a).focus()
            }return false
        }).prev().prev().html($(this).find("option:selected").text()).click(function(){
            if($(a).toggle().is(":visible")){
                $(a).focus()
            }return false
        });
        $(this).blur(function(){
            $(this).hide()
        }).parent().disableSelection()
    });**/
    
    $(":file").each(function(){
        var a=this;
        $(this).attr("size",25).wrap('<span class="ui-file" />').before('<span class="ui-file-value">Selecciona un archivo</span><button class="ui-file-button button button-gray">Buscar...</button>').change(function(){
            $(a).parent().find(".ui-file-value").html($(this).val()?$(this).val():"Selecciona un archivo")
        }).hover(function(){
            $(a).prev().addClass("hover")
        },function(){
            $(a).prev().removeClass("hover")
        }).mousedown(function(){
            $(a).prev().addClass("active")
        }).bind("mouseup mouseleave",function(){
            $(a).prev().removeClass("active")
        }).parent().disableSelection()
    });
    
    
    $.tools.validator.fn("[type=time]","Please supply a valid time",function(a,b){return(/^\d\d:\d\d$/).test(b)});
    $.tools.validator.fn("[data-equals]","Value not equal with the $1 field",function(a){
        var b=a.attr("data-equals"),
        c=this.getInputs().filter("[name="+b+"]");
        return a.val()===c.val()?true:[b]
    });
    $.tools.validator.fn("[minlength]",function(a,c){
        var b=a.attr("minlength");
        return c.length>=b?true:{en:"Please provide at least "+b+" character"+(b>1?"s":"")}
    });
    $.tools.validator.localizeFn("[type=time]",{en:"Please supply a valid time"});
    $(".form").validator({
        position:"bottom left",
        offset:[5,0],
        messageClass:"form-error",
        message:"<div><em/></div>"
    });

    if($.paginate){
        $("table.paginate").paginate({rows:10,buttonClass:"button-gray"})
    }
    if($.tablesort){
        $("table.tablesort").tablesort()
    }
    if($.selectable){
        $("table.selectable").selectable({onSelect:function(a){},onDeselect:function(a){}})
    }

    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
});