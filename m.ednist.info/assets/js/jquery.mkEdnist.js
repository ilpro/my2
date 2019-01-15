/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) zlada.net@gmail.com
 * Licensed under the MIT License (LICENSE.txt).
 * jquery.mkPluginname //переименовать так,как плагин
 * Version: 2.0
 */
;(function ( $, window, document, undefined ) {
    var pluginName = 'mkEdnist', //переименовать так,как плагин
        timerHandle,  
        defaults = { //задем переменные здесь
            a:'string',
            b:0
        };
    // конструктор плагина
    function Plugin( element, options ) {
        var widget=this;
        widget.element = element;
        widget._defaults = defaults; 
        this.metadata = this.element.data(pluginName);
        widget.config = $.extend( {}, defaults, options, widget.metadata) ;       
        widget._name = pluginName;
        $.each(widget.config,function(key,val){
    	     	if(typeof val==='function'){
    		     	widget.element.one(key+'.'+widget._name,function(){return val(widget.element)});
    	     	}
  	     });
        this.init();
    }
    Plugin.prototype.method = function(e){ //метод для копирповани и использолвания
        var widget=this;            

    };//END OF Plugin.prototype.method 

//    Plugin.prototype.bindAll = function(e){ //метод для привязки всех событий
//        var widget=this;
//        $('.class', widget.element).unbind().bind('click',function(e){
//            /*
//            	вызов метода, например:
//				widget.method();
//				обращение к переменной:
//				widget.config.a='присваиваем';
//
//             */
//        });
//    };//END OF Plugin.prototype.bindAll
//
//                       
    Plugin.prototype.init = function () { //метод инициализации. В него зашиваем все,что нужно на старте работы плагина        
        var widget=this;
		widget.element.trigger('before.' + widget._name);  //триггер срабаотывания по имени 'before'      
//        widget.bindAll();
                                                                                                                        
    };//END OF INIT
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            new Plugin( $(this), options );
        });
    }
})( jQuery, window, document );