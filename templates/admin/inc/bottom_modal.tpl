            </div>
        </div>
    </div>
    <script type="text/javascript">
    {$js_code}
    {if $msgErr!=''}
    	alert('{$msgErr}');
    {/if}
    </script>
    
    <div id="dhtmltooltip"></div>
    <link rel="stylesheet" href="{$smarty.const.ROOT_HOST}js/toolTip/toolTip.css" type="text/css" />
    <script type="text/javascript" src="{$smarty.const.ROOT_HOST}js/toolTip/toolTip.js"></script>
    <script>
        $(document).ready(function(){
            $(".navbar").affix();
        });
    </script>    
</div>
</body>
</html>