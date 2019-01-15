/*! Copyright (c) 2015 Max Kulyaev (Максим Куляев) zlada.net@gmail.com
 * Licensed under the MIT License (LICENSE.txt).
 * jquery.mkGa //переименовать так,как плагин
 * Version: 1.0
 */
;(function ( $, window, document, undefined ) {
    var pluginName = 'mkGa', //переименовать так,как плагин
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

    Plugin.prototype.bindAll = function(e){ //метод для привязки всех событий
        var widget=this;
		$('.logo').bind('click',function(e){
			ga('send','event','button','click','home');
       });
        $('#menuButton').bind('click',function(e){
			ga('send','event','button','click','menu');
       });  
        $('.mainimg').bind('click',function(e){
			ga('send','event','button','click','mainImg');
       });
        $('.galleryImg').bind('click',function(e){
			ga('send','event','button','click','galleryImg');
       });         
        $('.small-category').bind('click',function(e){
			ga('send','event','button','click','category');
       }); 
        $('.list-link').bind('click',function(e){
			ga('send','event','button','click','newsLine');
       }); 
        $('.btnDossier').bind('click',function(e){
			ga('send','event','button','click','mainDossier');
       });   
        $('.btnInfografics').bind('click',function(e){
			ga('send','event','button','click','mainInfografics');
       });                 
        $('.them').bind('click',function(e){
			ga('send','event','button','click','tag');
       }); 
        $('.news-prev').bind('click',function(e){
			ga('send','event','button','click','newsPrev');
       });        
        $('.news-next').bind('click',function(e){
			ga('send','event','button','click','newsNext');
       });                       
        $('#shareFb').bind('click',function(e){
        	ga('send', 'social', 'facebook', 'share', 'http://ednist.info'+window.location.pathname);
       });  
       // $('#shareVk').bind('click',function(e){
      //  	ga('send', 'social', 'vkontakte', 'share', 'http://ednist.info'+window.location.pathname);
      // }); 
        $('#shareTwitter').bind('click',function(e){
        	ga('send', 'social', 'twitter', 'share', 'http://ednist.info'+window.location.pathname);
       }); 
        $('#shareGoogle').bind('click',function(e){
        	ga('send', 'social', 'google', 'share', 'http://ednist.info'+window.location.pathname);
       }); 
       setTimeout(ga('send','event','time','14'), 14000);

                                                    
    };//END OF Plugin.prototype.bindAll

                      
    Plugin.prototype.init = function () { //метод инициализации. В него зашиваем все,что нужно на старте работы плагина        
        var widget=this;
		widget.element.trigger('before.' + widget._name);  //триггер срабаотывания по имени 'before'     
        widget.bindAll();
        console.log('mkGa');                                                                                                                        
    };//END OF INIT
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            new Plugin( $(this), options );
        });
    }
})( jQuery, window, document );