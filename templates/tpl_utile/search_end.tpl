					</td>
				</tr>
				<tr>
					<td>
						<div>
                        	{#pgPaging#}
                            <input type="text" name="noRowsDisplayed" maxlength="4" value="{$moduleSession.paging.noRowsDisplayed}" format="integer" style="width:40px;" /> {#pgRecordsPerPage#}
                            {#txtKeepSearchForm#}
                            <select name="toogle" style="width:60px;">
                                <option value="1" {if $moduleSession.search.toogle==1}selected{/if}>{#txtVisible#}</option>
                                <option value="2" {if $moduleSession.search.toogle==2 || $moduleSession.search.toogle!=1}selected{/if}>{#txtHidden#}</option>
                            </select>
                            &nbsp;
                            <input type="submit" value="{#bSearch#}" {$buttonStyle} />
                            <input type="button" value="{#bReset#}" onClick="formReset('form_search')" {$buttonStyle} />
                        </div>
					</td>
				</tr>
			</table>
			</fieldset>			
		</form>
			{literal}<script type="text/javascript" language="javascript">
			{if $moduleSession.search.toogle==2}
				onoffToggle('toggleFS', 'imgToggleFS', '{$smarty.const.IMAGES_URL}admin/utile/acdsee_toggle_open.gif', '{$smarty.const.IMAGES_URL}admin/utile/acdsee_toggle_closed.gif');
				onoffToggle('toggleFS', 'imgToggleFS', '{$smarty.const.IMAGES_URL}admin/utile/acdsee_toggle_open.gif', '{$smarty.const.IMAGES_URL}admin/utile/acdsee_toggle_closed.gif');
			{elseif $moduleSession.search.toogle==1}			
				onoffToggle('toggleFS', 'imgToggleFS', '{$smarty.const.IMAGES_URL}admin/utile/acdsee_toggle_open.gif', '{$smarty.const.IMAGES_URL}admin/utile/acdsee_toggle_closed.gif');
			{/if}
			</script>{/literal}