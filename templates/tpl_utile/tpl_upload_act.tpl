{if $form_act.$file_name}
{assign var="file_ext" value=$form_act.$file_name|pathinfo:$smarty.const.PATHINFO_EXTENSION}
<table>
    <tr>
        <td>
            <a class="fancybox" href="{$url}/{$form_act.$file_name}">
            {if $file_ext=="pdf"}<img src="/img/admin/utile/files/pdf.png" border="0" />
            {elseif $file_ext=="doc"}<img src="/img/admin/utile/files/doc.gif" border="0" />
            {elseif $file_ext=="docx"}<img src="/img/admin/utile/files/doc.gif" border="0" />
            {elseif $file_ext=="jpg"}<img src="/img/admin/utile/files/jpeg.png" border="0" />
            {elseif $file_ext=="jpeg"}<img src="/img/admin/utile/files/jpeg.png" border="0" />
            {elseif $file_ext=="gif"}<img src="/img/admin/utile/files/gif.gif" border="0" />
            {elseif $file_ext=="png"}<img src="/img/admin/utile/files/png.jpg" border="0" />
            {else}download{/if}
            </a>
        </td>
        <td>
            <a style="cursor:pointer;" onclick="if( window.confirm('Are you sure you want to remove this file?') ) window.location='index.php?obj={$smarty.get.obj}&action=delete_file&location={$location}&file_name={$file_name}&{$idName}={$form_act.$idName}';" >
            <img src="/img/admin/utile/del_small.jpg" border="0" />
            Delete
            </a>
        </td>
    </tr>
</table>
{else}
<input type="file" name="{$file_name}" />
{/if}
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            $(".fancybox").fancybox();
        });
    </script>
{/literal}