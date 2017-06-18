<div style="display:none;">
<div id="dialog">
    <iframe id="myIframe" src=""></iframe>
</div>
</div>
<style> 
    #myIframe{
        height: 800px;
        width: 960px;
    }
</style>

{literal}
    <script type="text/javascript">
        function open_dialog(link) {
            $('#dialog').dialog({
                autoOpen: true,
                modal: true,
                height: 'auto',
                width: 'auto',
                open: function (ev, ui) {
                  $('#myIframe').attr('src',link);
                    //addTinyMCE();
                },
                close: function() {
                    $(this).dialog('destroy');
                    window.parent.location.reload();
                }
            });
        }
        
        function addTinyMCE() {
            $('.mceEditor').tinymce({
                script_url: '/js/tinymce/js/tinymce/tinymce.js',
                width: "550px",
                height: "290px",
                mode: "none",
                theme : "simple"
            });
        }
        
        
    </script>
{/literal}
