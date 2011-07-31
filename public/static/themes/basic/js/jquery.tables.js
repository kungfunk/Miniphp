(function($){
    $.paginate = function(el, options) {
        var base = this; 
        base.$el = $(el);
        base.el = el;
        base.$el.data("paginate", base);
        base.init = function(){
            base.options = $.extend({},$.paginate.defaults, options);
            var trs = base.$el.find('> tbody > tr');
            var pages = $('<ul class="pagination clearfix"></ul>');
            if (!base.$el.hasClass('paginate')) base.$el.addClass('paginate');
            for(var i = 0; i < trs.length; i+=base.options.rows) {
                trs.slice(i, i+base.options.rows).wrapAll("<tbody></tbody>");
                pages.append('<li class="page"><a class="'+base.options.buttonClass+'" href="#">'+(i/base.options.rows+1)+'</a></li>');
            }
            var api = base.$el.find("> tbody > tbody").unwrap().parents('table.paginate').after(pages).next().tabs("table.paginate > tbody", {effect: base.options.effect}).data("tabs");
            $('<li class="prev"><a class="'+base.options.buttonClass+'" href="#">&laquo;</a></li>').click(function(){
                if (api.getIndex()>0) api.prev(); return false;
            }).prependTo(pages);
            $('<li class="first"><a class="'+base.options.buttonClass+'" href="#">Primera</a></li>').click(function(){
                api.click(0); return false;
            }).prependTo(pages);
            $('<li class="next"><a class="'+base.options.buttonClass+'" href="#">&raquo;</a></li>').click(function(){
                if (api.getIndex()<trs.length/base.options.rows) api.next(); return false;
            }).appendTo(pages);
            $('<li class="last"><a class="'+base.options.buttonClass+'" href="#">Ultima</a></li>').click(function(){
                api.click(Math.round(trs.length/base.options.rows)); return false;
            }).appendTo(pages);
            return base.$el;
        };
        base.init();
    }

    $.paginate.defaults = {
        rows: 20,
        buttonClass: 'blue-button',
        effect: 'default'
    };

    $.fn.paginate = (function(options) {
        return this.each(function(){
            (new $.paginate(this, options));
        });
    });

    $.fn.sortElements = (function(){

        var sort = [].sort;
 
        return function(comparator, getSortable) {
 
            getSortable = getSortable || function(){return this;};
 
            var placements = this.map(function(){
 
                var sortElement = getSortable.call(this),
                    parentNode = sortElement.parentNode,
 
                // Since the element itself will change position, we have
                // to have some way of storing its original position in
                // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );
 
                return function() {
 
                    if (parentNode === this) {
                        throw new Error(
                            "You can't sort elements if any one is a descendant of another."
                        );
                    }
 
                    // Insert before flag:
                    parentNode.insertBefore(this, nextSibling);
                    // Remove flag:
                    parentNode.removeChild(nextSibling);
 
                };
 
            });
 
            return sort.call(this, comparator).each(function(i){
                placements[i].call(getSortable.call(this));
            });
 
        };
 
    })();

    $.tablesort = function(el, options) {
        var base = this; 
        base.$el = $(el);
        base.el = el;
        base.$el.data("tablesort", base);
        base.init = function(){
            base.options = $.extend({},$.tablesort.defaults, options);
            var table = $(el);
            table.find('> thead > tr > th:not(:empty)').wrapInner('<a href="#"/>').find('> a').click(function(){
              var sort = $(this).data('sort');
              $(this).parents('thead').find('th > a').removeClass('sort-asc sort-desc');
              sort = (sort=='asc'? 'desc' : 'asc');
              $(this).data('sort', sort).addClass('sort-'+sort);
              table.find('> tbody > tr > td').removeClass('column-selected');
              table.find('> tbody > tr > td:nth-child('+($(this).parent().index()+1)+')').sortElements(
                function(a, b){
                    if (isNumber($(a).text()) && isNumber($(b).text())) {
                        var af = parseFloat($(a).text());
                        var bf = parseFloat($(b).text());
                        return sort=='desc'? (af < bf) - (af > bf) : (af > bf) - (af < bf);
                    }
                    return sort=='desc'? ($(a).text() < $(b).text()) - ($(a).text() > $(b).text()) : ($(a).text() > $(b).text()) - ($(a).text() < $(b).text());
                },
                function(){
                    return this.parentNode; 
                }
              ).addClass('column-selected');
              return false;
            });
            return base.$el;
        };
        base.init();
    }

    $.tablesort.defaults = {
    };

    $.fn.tablesort = (function(options) {
        return this.each(function(){
            (new $.tablesort(this, options));
        });
    });

    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    $.selectable = function(el, options) {
        var base = this;
        var ctrlPressed = false;
        base.$el = $(el);
        base.el = el;
        base.$el.data("selectable", base);
        base.init = function(){
            base.options = $.extend({},$.selectable.defaults, options);
            base.$el.find("tbody tr").hover(
                function() {$(this).addClass('hover');},
                function() {$(this).removeClass('hover');}
            ).click(function(){
                if (!ctrlPressed) {
                    $(this).siblings().removeClass('selected');
                    $(this).parents('tbody').siblings().find('tr').removeClass('selected');
                }
                if ($(this).toggleClass('selected').hasClass('selected')) {
                    base.options.onSelect(this);
                } else {
                    base.options.onDeselect(this);
                }
            });

		$(document).keydown(function(e) {
                if (e.which == 17) { // ctrl
                    ctrlPressed = true;
                }
		}).keyup(function(e) {
                if (e.which == 17) { // ctrl
                    ctrlPressed = false;
                }
            });

        };
        base.init();
    }

    $.selectable.defaults = {
        onSelect : function(row) {
        },
        onDeselect : function(row) {
        }
    }

    $.fn.selectable = (function(options){
        return this.each(function(){
            (new $.selectable(this, options));
        });
    });

})(jQuery);

function allCheck(table, value){
	if (value == true){
		$(table).find('tbody tr:visible td:first-child input[type=checkbox]').attr('checked', 'checked');
	}else {
		$(table).find('tbody tr:visible td:first-child input[type=checkbox]').removeAttr('checked');
	}
}
function indCheck(table,value){
	if (value == false){
		$(table).find('.checkall').removeAttr('checked');
	}else{
		if ($(table).find('tbody tr:visible td:first-child input[type=checkbox]:not(:checked)').length) {
			$(table).find('.checkall').removeAttr('checked');
		}else {
			$(table).find('.checkall').attr('checked', 'checked');
		}
	}
}
