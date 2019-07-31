<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Add reference to the current document</h3>
        </div>
        <form>
        <div class="box-body">
            <div class="form-group">
                <label for="">Document</label>
                <input type="text" placeholder="Find document..." class="form-control" aria-describedby="helpId">
                <div class="checkbox">
                    <label><input type="checkbox" value="">Set Public</label>
                </div>
            </div>
            <button type="button" class="btn btn-default pull-right"> <i class="fa fa-save"></i> Save Reference</button>
        </div>

        </form>
    </div>
</template>
