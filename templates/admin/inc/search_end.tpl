                <div class="panel-footer col-md-12">
                    <div class="form-group col-md-3">
                        {#txtPagination#}: <input type="text" name="noRowsDisplayed" class="form-control" value="{$moduleSession.paging.noRowsDisplayed}" style="width: 50px;"> {#pgRecordsPerPage#}
                    </div>
                    <div class="form-group col-md-6">
                        {#txtKeepSearchForm#}
                        <input value="1" type="radio" name="toogle" {if isset($moduleSession.search.toogle)&&$moduleSession.search.toogle==1} checked="checked" {/if} /> {#txtVisible#}
                        <input value="2" type="radio" name="toogle" {if isset($moduleSession.search.toogle)&&$moduleSession.search.toogle==2||!isset($moduleSession.search.toogle)} checked="checked" {/if} /> {#txtHidden#}
                        
                        <button type="submit" class="btn btn-primary">cauta</button>
                        <button type="button" class="btn btn-danger" onClick="formReset('form_search')">reseteaza</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript">
    {if isset($moduleSession.search.toogle)&&$moduleSession.search.toogle==1}
        $('.collapse').addClass('in');
    {else}
        $('.collapse.in').hide();
    {/if}
</script>