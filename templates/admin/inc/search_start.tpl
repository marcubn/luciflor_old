<div class="form-group col-md-12 panel-info panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">{#txtSearch#}</a>
          </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse">
            <form class="form-inline" role="form" name="form_search" action="index.php?obj={$smarty.get.obj}&action={$smarty.get.action}" method="post" onSubmit="applyStyle('collapseOne', 'visibility', 'visible', 1); return formValidate('form_search', 0);">
                <input type="hidden" name="act" value="search">
                