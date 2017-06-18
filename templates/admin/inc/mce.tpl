<script type="text/javascript" src="/js/tinymce/js/tinymce/tinymce.js"></script>

{literal}
<script type="text/javascript">
    $().ready(function() {
        tinyMCE.init({

            // General options
            mode : "specific_textareas",
            editor_selector : "mceEditor",
            theme : "modern",
            //plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
            plugins: [
                "advlist autolink lists link image charmap print preview hr pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "textcolor insertdatetime media table contextmenu paste jbimages filemanager"
                ],
            toolbar1: "insertfile undo redo pasteword | styleselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
            toolbar2: "print preview media | forecolor backcolor | bold italic | pastetext pasteword | link image jbimages",


            // Cleanup/Output
            inline_styles : true,
            gecko_spellcheck : true,
            entity_encoding : "raw",
            extended_valid_elements : "hr[id|title|alt|class|width|size|noshade|style],ul[class],li[class],i[class],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],a[id|class|name|href|hreflang|target|title|onclick|rel|style]",
            force_br_newlines : true, force_p_newlines : false, forced_root_block : '',
            invalid_elements : "applet",
            // URL
            relative_urls : false,
            convert_urls : false,
            resize : true,
            width:550,
            // Theme options
            theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            //theme_advanced_buttons4 : "styleprops,|,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true
        });
    });
</script>
{/literal}