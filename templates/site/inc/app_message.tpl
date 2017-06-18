{if $APP_MESSAGE}
    {if $APP_CODE=="success" || $APP_CODE=="error"}
        <div class="modal {$APP_CODE}">
            <div class=" modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    {*<button type="button" class="close" style="margin-bottom:10px;" data-dismiss="modal">&times;</button>*}
                    {*}<h4 class="modal-title">{$APP_MESSAGE}</h4>{*}
                  </div>
                  <div class="modal-body">
                    <p>{$APP_MESSAGE}</p>
                  </div>
                  {*}<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Inchide</button>
                  </div>{*}
                </div>
            </div>
        </div>
    {else}
        <div class="modal">
            <div class=" modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    {*<button type="button" class="close" style="margin-bottom:10px;" data-dismiss="modal">&times;</button>*}
                    {*}<h4 class="modal-title">{$APP_MESSAGE}</h4>{*}
                  </div>
                  <div class="modal-body">
                    <p>{$APP_MESSAGE}</p>
                  </div>
                  {*}<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Inchide</button>
                  </div>{*}
                </div>

            </div>
        </div>
    {/if}
{/if}
