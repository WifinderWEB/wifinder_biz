var Frontend = {
    init: function() {
        this.slider();
        this.showMap();
        this.InitCallback();
        this.submitCallback();
        this.tabsDistribution();
        this.heightRightSidebar();
        this.initializeContactPageMap();
        this.submitSubscribe();
        this.submitModalSubscribe();
        this.tabWf.Init();
        this.addFileToCallback();
        $('input, textarea').placeholder();
        this.initPhoneMask();
    },
    initPhoneMask : function(){
        $('input[id$=phone]').mask("+7-?999-999-99-99", {placeholder: "_"});
        $('input[id$=phone]').bind('blur', function() {
            if($(this).val() == '+7-')
                $(this).val(null);
        })
    },
    tabWf : {
        Init : function(){
            var tabs = $('.wfTab');
            $.each(tabs, function(i){
                $(tabs[i]).click(function(){
                    Frontend.tabWf.Click(i, tabs);
                })
            })
        },
        Click : function(i, tabs){
            tabs.removeClass('active');
            $(tabs[i]).addClass('active');
            if(i == 0){
                Frontend.tabWf.RemoveOpacity(tabs);
                Frontend.tabWf.AddOpacity(tabs[0], 1);
                Frontend.tabWf.AddOpacity(tabs[1], 2);
                Frontend.tabWf.AddOpacity(tabs[2], 3);
            }
            if(i == 1){
                Frontend.tabWf.RemoveOpacity(tabs);
                Frontend.tabWf.AddOpacity(tabs[0], 2);
                Frontend.tabWf.AddOpacity(tabs[1], 1);
                Frontend.tabWf.AddOpacity(tabs[2], 2);
            }
            if(i == 2){
                Frontend.tabWf.RemoveOpacity(tabs);
                Frontend.tabWf.AddOpacity(tabs[0], 3);
                Frontend.tabWf.AddOpacity(tabs[1], 2);
                Frontend.tabWf.AddOpacity(tabs[2], 1);
            }
        },
        RemoveOpacity : function(tabs){
            var blocks = tabs.find('.header').add(tabs.find('.content'));
            for(var i=1; i <= tabs.length; i++){
               blocks.removeClass('tab-opacity' + i);
               tabs.removeClass('tab-index' + i);
            }
        },
        AddOpacity : function(tab, i){
            $(tab).addClass('tab-index' + i).find('.header').add($(tab).find('.content')).addClass('tab-opacity' + i);
        }
    },
    tabsDistribution: function() {
        $('#tabs').tabs({collapsible: true});
    },
    heightRightSidebar: function() {
        var hSidebar = $('#siderbar_right').height();
        var hContent = $('#content').height();
        if (hContent > hSidebar)
            $('#siderbar_right').css('min-height', hContent);
    },
    video: function() {
        Shadowbox.init({
            modal: true,
            overlayColor: "#000",
            overlayOpacity: 0.75,
            player: 'iframe'
        });
    },
    slider: function() {
        var c = $('#carousel').carousel({
            interval: 8000
        })
        $('#carousel_left').click(function() {
            $('#carousel').carousel('next');
        });
        $('#carousel_right').click(function() {
            $('#carousel').carousel('prev');
        });
    },
    InitCarouselBrands: function() {
        var next = $('#slider_control_next');
        var prev = $('#slider_control_prev');
        $('.slider4').bxSlider({
            slideWidth: 101,
            minSlides: 2,
            maxSlides: 9,
            moveSlides: 3,
            slideMargin: 10,
            pager : false,
            nextSelector : next,
            prevSelector : prev,
            nextText : '›',
            prevText : '‹',
            auto: true,
            autoHover : true
          });
    },
    initializeMap: function() {
        var latLng = new google.maps.LatLng(55.760798, 37.678793);

        var mapOptions = {
            center: latLng,
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas2"), mapOptions);

        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            title: "г. Москва, наб. Академика Туполева, дом 15, корпус 28, офис 1 (3-й этаж)"
        });
    },
    initializeContactPageMap: function() {
        if ($('#map_canvas2').length > 0 && typeof google.maps.LatLng != 'undefined') {
            var latLng = new google.maps.LatLng(55.760798, 37.678793);

            var mapOptions = {
                center: latLng,
                zoom: 16,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("map_canvas2"), mapOptions);

            var marker = new google.maps.Marker({
                position: latLng,
                map: map,
                title: "г. Москва, наб. Академика Туполева, дом 15, корпус 28, офис 1 (3-й этаж)"
            });
        }
        else{
            setTimeout(function(){Frontend.initializeContactPageMap()}, 100); 
        }
    },
    showMap: function() {
        $('#map_canvas2').on('shown', function() {
            Frontend.initializeMap();
        });
    },
    singlePrettyPhoto: function(id) {
        $("a[rel^='prettyPhoto[" + id + "]']").prettyPhoto({
            show_title: true,
            allow_resize: true,
            social_tools: false
        });
    },
    submitCallback: function(id) {
        var modal = $(id).parent().parent();
        $.ajax({
            type: "POST",
            async: false,
            url: modal.find('form').attr("action"), // Or your url generator like Routing.generate('discussion_create')
            data: modal.find('form').serialize(),
            dataType: "html",
            success: function(msg) {
                modal.find(".modal-body").html(msg);
            }
        });
    },
    submitSubscribe : function(){
        $('#subscribeModal').on('show', function(){
            if('#Wifinder_callbackbundle_subscribetype_captcha_div')
                Frontend.InitReCaptcha('Wifinder_callbackbundle_subscribetype_captcha_div');
        })
        $('#subscribeModal').on('hide', function(){
            Frontend.DestroyReCaptcha();
        })
        
        $("#button_subscribe_form").click(function(event) {
            var email = $('#subscribetype_email').val();
            $('#Wifinder_callbackbundle_subscribetype_email').val(email);
            $("#subscribeModal").modal();
        });
        $('form#subscribe').submit(function(){
            var email = $('#subscribetype_email').val();
            $('#Wifinder_callbackbundle_subscribetype_email').val(email);
            $("#subscribeModal").modal();
            return false;
        })
    },
    submitModalSubscribe : function(id){
        var modal = $(id).parent().parent();
        $.ajax({
            type: "POST",
            async: false,
            url: modal.find('form').attr("action"), // Or your url generator like Routing.generate('discussion_create')
            data: modal.find('form').serialize(),
            dataType: "html",
            success: function(msg){
                modal.find(".modal-body").html(msg);
                Frontend.DestroyReCaptcha();
                if('#Wifinder_callbackbundle_subscribetype_captcha_div')
                    Frontend.InitReCaptcha('Wifinder_callbackbundle_subscribetype_captcha_div');
            }
        });
    },
    InitCallback : function(url){
        $('#reviewModal').on('show', function(){
            if('#wifinder_reviewsbundle_frontendreviewtype_captcha_div')
                Frontend.InitReCaptcha('wifinder_reviewsbundle_frontendreviewtype_captcha_div');
        })
        $('#reviewModal').on('hide', function(){
            Frontend.DestroyReCaptcha();
        })
        
        $('#callbackModal').on('show', function(){
            if('#wifinder_callbackbundle_frontendcallbacktype_captcha_div')
                Frontend.InitReCaptcha('wifinder_callbackbundle_frontendcallbacktype_captcha_div');
        })
        $('#callbackModal').on('hide', function(){
            Frontend.DestroyReCaptcha();
        })

        if('#wifinder_callbackbundle_frontendcallbackduble_type_captcha_div')
            Frontend.InitReCaptcha('wifinder_callbackbundle_frontendcallbackduble_type_captcha_div');
    },
    InitReCaptcha : function(id){
//        Recaptcha.create(
//            '6LcqK-kSAAAAAKs41E7BTFR7_2_a7wqy4-9MTzyf',
//            id,
//            {"theme":"white","lang":"ru"}
//        );
    },
    DestroyReCaptcha : function(){
//         Recaptcha.destroy();
    },
    addFileToCallback : function(){
        $('#add_file a').click(function(){
            $('#file_box').show(400);
            $(this).parent().parent().hide();
            Frontend.filesControl();
        })
    },
    filesControl : function(){
        var collectionFileHolder = $('ul.files');
        if(collectionFileHolder.children().length == 0){
            Frontend.addFileForm(collectionFileHolder, 0);
        }
    },
    addTagFormDeleteLink : function($newFormLi) {
        var $removeFormA = $('<a href="javascript:void(0)"><i class="icon-trash icon-white"></i></a>');
        $newFormLi.find('.block_button').append($removeFormA);

        $removeFormA.on('click', function(e) {
            e.preventDefault();
            $newFormLi.remove();
            
            if($('ul.files').children().length == 0){
                $('#add_file').parent().show(400);
                $('#file_box').hide();
            }
        });
    },
    addFileForm : function(collectionFileHolder, idLi) {
        var prototype = collectionFileHolder.attr('data-prototype');
        var newForm = prototype.replace(/\_\_name\_\_/g, idLi);

        var $viewFormFile = $('<div id="file__' + idLi + '">' + newForm + '</div>');
        var $newFormLi = $('<li></li>').append($viewFormFile);

        collectionFileHolder.append($newFormLi);
        Frontend.addTagFormDeleteLink($newFormLi);
        $viewFormFile.find('td:not(:first)').children().hide();
        Frontend.testFile(collectionFileHolder, $newFormLi);
    },
    testFile : function(collectionFileHolder, $newFormLi){
        $newFormLi.find('input[type=file]').on('change', function(){
            var file = $newFormLi.find('input[type=file]');
            var fileName = file[0].files[0];
            file.parent().append('<span>' + fileName.name + '</span>');
            file.hide();
            $newFormLi.find('td:not(:first)').children().show();
            
            var idLi = collectionFileHolder.children().length;
            Frontend.addFileForm(collectionFileHolder, idLi);
        })
    }
}

$().ready(function() {
    Frontend.init();
})
