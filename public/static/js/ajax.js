(function( $ ){
    $.fn.ajax2container = function(options, callback) {

        var settings = {
          'controller' : '',
          'data' : ''
        };

        return this.each(function() {
            if (options) {
                $.extend(settings, options);
            }

            ajax = this;
            var $this = $(this);
            url = DOC_URL+'/'+settings.controller+'/'+settings.action;
            $this.load(url, settings.data, function(e){
                callback(e);
            });
        });
    };
})(jQuery);

(function( $ ){
    $.fn.ajax2upload = function(options, callback) {

        var settings = {
          'controller' : '',
          'data' : ''
        };

        return this.each(function() {
            if (options) {
                $.extend(settings, options);
            }

            ajax = this;
            var $this = $(this);
            url = DOC_URL+'/'+settings.controller+'/'+settings.action;
            $.ajaxFileUpload({
                url: url,
                secureuri:false,
                fileElementId: settings.data.file_id,//'fileToUpload',
                dataType: 'json',
                data: settings.data,
                success: function (e) { callback(e) },
                error: function (data, status, e) {
                        console.log(e);
                }
            });
        });
    }
})(jQuery);

(function( $ ){
    $.fn.ajax2json = function(options, callback, type) {

        var settings = {
          'controller' : '',
          'data' : ''
        };

        return this.each(function() {
            if (options) {
                $.extend(settings, options);
            }

            ajax = this;
            var $this = $(this);
            url = DOC_URL+'/'+settings.controller+'/'+settings.action;
            switch(type) {
                case 'get':
                    $.get(url, settings.data, function(e) {
                          callback(e);
                    });
                break;
                case 'post': default:
                    $.post(url, settings.data, function(e) {
                          callback(e);
                    });
                break;
            }
        });
    };
})(jQuery);