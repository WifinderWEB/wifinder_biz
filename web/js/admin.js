var FormAction = {
    init : function(){
        this.saveMainForm();
        this.filterForm();
        this.imagesControl();
        this.filesControl();
        this.initTab();
        this.tooltipe();
        this.useVisualEditor('show_editor','content');
    },
    saveMainForm : function(){
        $('.button_save_main_form').click(function(){
            $('form.main_form input[id$=action]').val('');
            $('form.main_form').submit();
        });
        $('.button_save_and_close_main_form').click(function(){
            $('form.main_form input[id$=action]').val('close');
            $('form.main_form').submit();
        });
    },
    deleteContent : function(id){
        $('form#delete_form_' + id).submit();
    },
    filterForm : function(){
        $('#button_filter_form').click(function(){
            $('form.filter_form').submit();
        })
    },
    addImageForm : function(collectionHolder, idLi, label) {
        var prototype = collectionHolder.attr('data-prototype');
        var newForm = prototype.replace(/\_\_name\_\_/g, idLi);

        var $viewFile = $('<div class="file_preview image_preview"><div class="thumb_image"><img src="/images/new_image.png" alt=""/></div><div><div class="title_file">Новое изображение <span class="label label-info">new</span></div><div class="btn-group"><a href="#imageCatalogModal__' + idLi + '" class="btn btn-small" data-toggle="modal" role="button"><i class="icon-edit"></i> изменить</a></div></div></div>');
        var $viewFormFile = $('<div id="imageCatalogModal__' + idLi + '" class="modal hide fade file_form_fields" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-header"><h4 id="myModalLabel">Новое изображение</h4></div><div class="modal-body">' + newForm + '</div><div class="modal-footer"></div></div>');
        var $newFormLi = $('<li></li>').append($viewFile).append($viewFormFile);
        
        collectionHolder.append($newFormLi);
        FormAction.addTagFormDeleteLink($newFormLi, label);
        FormAction.addSaveButton($newFormLi);
        FormAction.initCKEditor('images_' + idLi);
    },
    addFileForm : function(collectionHolder, idLi, label) {
        var prototype = collectionHolder.attr('data-prototype');
        var newForm = prototype.replace(/\_\_name\_\_/g, idLi);

        var $viewFile = $('<div class="file_preview worning_new_file"><table><tr><td><a href="#" class="title_file">Новый файл</a> <span class="label label-info">new</span></td><td><div class="btn-group"><a href="#fileCatalogModal__' + idLi + '" class="btn btn-small" data-toggle="modal" role="button"><i class="icon-edit"></i> изменить</a></div></td><tr></table></div>');
        var $viewFormFile = $('<div id="fileCatalogModal__' + idLi + '" class="modal hide fade file_form_fields" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-header"><h4 id="myModalLabel">Новый файл</h4></div><div class="modal-body">' + newForm + '</div><div class="modal-footer"></div></div>');
        var $newFormLi = $('<li></li>').append($viewFile).append($viewFormFile);

        collectionHolder.append($newFormLi);
        FormAction.addTagFormDeleteLink($newFormLi, label);
        FormAction.addSaveButton($newFormLi);
        FormAction.initCKEditor('files_' + idLi);
    },
    addTagFormDeleteLink : function($newFormLi, label) {
        var $removeFormA = $('<a href="#" class="btn btn-small btn-danger"><i class="icon-trash icon-white"></i>' + label + '</a>');
        var $clonRemoveFormA = $removeFormA.clone();
        $newFormLi.find('.file_preview .btn-group').append($removeFormA);
        $newFormLi.find('.modal-footer').append($clonRemoveFormA);

        $removeFormA.on('click', function(e) {
            e.preventDefault();
            $('#' + $newFormLi.find('.modal').attr('id')).modal('hide');
            $newFormLi.remove();
        });
        $clonRemoveFormA.on('click', function(e) {
            e.preventDefault();
            $('#' + $newFormLi.find('.modal').attr('id')).modal('hide');
            $newFormLi.remove();
        });
    },
    addSaveButton : function($newFormLi){
        var $saveButton = $('<button class="btn btn-small" data-dismiss="modal" aria-hidden="true"><i class="icon-ok"></i> Сохранить</button>');
        $newFormLi.find('.modal-footer').append($saveButton);
        $saveButton.on('click', function(e) {
            var fileTitle = $newFormLi.find('input[id$=ru_title]').val();
            if(fileTitle){
                $newFormLi.find('.title_file').text(fileTitle);
                $newFormLi.find('#myModalLabel').text('Новый файл "' + fileTitle + '"');
            }
        });
    },
    imagesControl : function(){
        var collectionImageHolder = $('ul.images');
        var labelAddImage = ' Удалить';
        collectionImageHolder.find('li.image_box').each(function(i) {
            FormAction.addTagFormDeleteLink($(this), labelAddImage);
            FormAction.initCKEditor('images_' + i);
        });

        var $addImageLink = $('#sidebar_left ul li a.button_add_image_link');

        $addImageLink.on('click', function(e) {
            e.preventDefault();
            var idLi = collectionImageHolder.children().length;
            $('#content-tabs a:eq(1)').tab('show');
            FormAction.addImageForm(collectionImageHolder, idLi, labelAddImage);
            
            $('#imageCatalogModal__' + idLi + " .a2lix_translationsLocales a:first").tab("show");
            var tabs = $('#imageCatalogModal__' + idLi + " .tab-pane");
            tabs.hide();
            tabs.eq(0).show();
            $('#imageCatalogModal__' + idLi + " .a2lix_translationsLocales a").click(function(e){
                var box = $(this).parents('.symfony-form-row-translations');
                box.find('.tab-pane').hide();
                console.log($(this).attr('data-target'));
                box.find($(this).attr('data-target')).show();
                $(this).tab('show');
            });
            
            $('#imageCatalogModal__' + idLi).modal('show');
        });
    },
    filesControl : function(){
        var collectionFileHolder = $('ul.files');
        var labelDeleteFile = ' Удалить';
        collectionFileHolder.find('li.file_box').each(function(i) {
            FormAction.addTagFormDeleteLink($(this), labelDeleteFile);
            FormAction.initCKEditor('files_' + i);
        });

        var $addFileLink = $('#sidebar_left ul li a.button_add_file_link');
        
        $addFileLink.on('click', function(e) {
            e.preventDefault();
            var idLi = collectionFileHolder.children().length;
            $('#content-tabs a:eq(2)').tab('show');
            FormAction.addFileForm(collectionFileHolder, idLi, labelDeleteFile);
            
            $('#fileCatalogModal__' + idLi + " .a2lix_translationsLocales a:first").tab("show");
            var tabs = $('#fileCatalogModal__' + idLi + " .tab-pane");
            tabs.hide();
            tabs.eq(0).show();
            $('#fileCatalogModal__' + idLi + " .a2lix_translationsLocales a").click(function(e){
                var box = $(this).parents('.symfony-form-row-translations');
                box.find('.tab-pane').hide();
                box.find($(this).attr('data-target')).show();
                $(this).tab('show');
            });
            
            $('#fileCatalogModal__' + idLi).modal('show');
        });
    },
    initTab : function(){
        this.mainTab();
        this.translationTab();
    },
    mainTab : function(){
        $("#content-tabs a:first").tab("show");
        $("#content-tabs a").click(function(e){
            e.preventDefault();
            $(this).tab('show');
        });
    },
    translationTab : function(){
        $('.symfony-form-row-translations').each(function(i){
            $(this).find(".a2lix_translationsLocales a:first").tab("show");
            $(this).find(".tab-pane").hide().eq(0).show();
            $(this).find(".a2lix_translationsLocales a").click(function(e){
                e.preventDefault();
                var box = $(this).parents('.symfony-form-row-translations');
                box.find('.tab-pane').hide();
                box.find($(this).attr('data-target')).show();
                $(this).tab('show');
            });
        })
    },
    tooltipe : function(){
        $('.is_active').tooltip({
            placement : 'left',
            animation : true,
            title : $(this).attr('title')
        })
        $('.move_up').tooltip({
            placement : 'left',
            animation : true,
            title : $(this).attr('title')
        })
        $('.move_down').tooltip({
            placement : 'right',
            animation : true,
            title : $(this).attr('title')
        })
    },
    chengeActive : function(url, id, active){
        $.post(url,{id:id, active:active}, function(data){
            if(data == 0)
                $('.change_catalog_' + id).find('i').removeClass('icon-ok').addClass('icon-ban-circle').end()
                   .removeClass('btn-info').addClass('btn-warning')
                   .attr('onclick', 'FormAction.chengeActive(\'' + url + '\', ' + id + ', 0)');
            if(data == 1)
                $('.change_catalog_' + id).find('i').removeClass('icon-ban-circle').addClass('icon-ok').end()
                   .removeClass('btn-warning').addClass('btn-info')
                   .attr('onclick', 'FormAction.chengeActive(\'' + url + '\', ' + id + ', 1)');
        })
    },
    initCKEditor : function(idLi){
        var lang = ['ru', 'en'];
        for(var i= 0; i <= lang.length-1; i++){
            var id = $('textarea[id$=' + idLi + '_translations_' + lang[i] + '_description]').attr('id');
            var instance = CKEDITOR.instances[id];
            if (instance) {
                instance.destroy(true);
            }
            CKEDITOR.replace(id,
            {
                "uiColor":"#DED0DF",
                "toolbar":
                    [
                        { name: 'document', items : [ 'Source']},
                        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Subscript','Superscript','-','RemoveFormat' ] },
			{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] }
                    ]
            });
        }
    },
    singlePrettyPhoto : function(id){
        $("a[rel^='prettyPhoto[" + id + "]']").prettyPhoto({
            show_title: true,
	    allow_resize: true, 
            social_tools: false
        });
    },
    singleEditFormPrettyPhoto : function(id){
        $("a[rel='prettyPhoto[" + id + "]']").prettyPhoto({
            show_title: false,
	    allow_resize: true, 
            social_tools: false,
	    gallery_markup: '',
            markup: '<div class="pp_pic_holder"> \
                            <div class="ppt">&nbsp;</div> \
                            <div class="pp_top"> \
                                    <div class="pp_left"></div> \
                                    <div class="pp_middle"></div> \
                                    <div class="pp_right"></div> \
                            </div> \
                            <div class="pp_content_container"> \
                                    <div class="pp_left"> \
                                    <div class="pp_right"> \
                                            <div class="pp_content"> \
                                                    <div class="pp_loaderIcon"></div> \
                                                    <div class="pp_fade"> \
                                                            <div id="pp_full_res"></div> \
                                                            <div class="pp_details"> \
                                                                    <a class="pp_close" href="#">Close</a> \
                                                            </div> \
                                                    </div> \
                                            </div> \
                                    </div> \
                                    </div> \
                            </div> \
                            <div class="pp_bottom"> \
                                    <div class="pp_left"></div> \
                                    <div class="pp_middle"></div> \
                                    <div class="pp_right"></div> \
                            </div> \
                    </div> \
                    <div class="pp_overlay"></div>'
        });
    },
    useVisualEditor: function(check, field){
        $('input[id$=' + check + ']').change(function(){
            var id = $('textarea[id$=' + field + ']').attr('id');
            var instance = CKEDITOR.instances[id];
            if($(this).is(':checked')){
                if (instance) {
                    instance.destroy(true);
                }
                CKEDITOR.replace(id);
            }
            else{
                if (instance) {
                    instance.destroy(true);
                }
            }
        });
    }
}


$().ready(function(){
    FormAction.init();
})
