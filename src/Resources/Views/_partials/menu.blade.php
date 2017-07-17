<div class="menuBuilder">
    <section class="container-fluid">
        <div class="row " id="builderContain">
            <div class="col-xs-12 col-sm-6 previewAray menu-preview">
                <ol id="menus-list" class="sortable ui-sortable"></ol>
            </div>
            <div class="col-xs-12 col-sm-6 formoptions ">
                <div class="row formrow form-horizontal">

                    <div class="row">
                        <!-- Item template used by JS -->
                        <script type="template" id="item-template">
                            <li data-details='[serialized_data]'>
                                <div class="drag-handle not-selected">
                                    [title]
                                    <div class="item-actions">
                                        <a href="javascript:;" data-action="addChild">
                                            <i class="fa fa-plus"></i> Add Child
                                        </a>
                                        <a href="javascript:;" data-action="delete"><i class="fa fa-trash-o"></i> Remove</a>
                                    </div>
                                </div>
                                <ol></ol>
                            </li>
                        </script>
                        <!-- END Item template -->

                        <div class="col-md-12">
                            <a href="javascript:void(0)" data-action="newItem" class="btn btn-primary btn-black">Add New Item</a>
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-primary dropdown-toggle btn-black" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Upload Json <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="p-l-5 p-r-5 p-b-5">
                                        <input type="file" data-file="uploadjson" class="file" data-show-preview="false" data-browse-label="Add New Item" data-type="" data-show-caption="false" data-show-upload="false" data-show-remove="false" data-allowed-file-extensions='["json"]' data-browse-class=" btn btn-default btn-block" />
                                    </li>
                                    <li class="p-l-5 p-r-5">
                                        <input type="file" data-file="uploadjson" class="file" data-show-preview="false" data-browse-label="Add New menu" data-type="new" data-show-caption="false" data-show-upload="false" data-show-remove="false" data-allowed-file-extensions='["json"]' data-browse-class=" btn btn-default btn-block">
                                    </li>
                                </ul>
                            </div>
                            <a href="#" class="btn btn-default pull-right btn-black download-btn" download="menudata.json" data-download="json">Download</a>
                        </div>
                    </div>
                </div>
                <div class="row formrow">
                    <div class="hide" id="new-menu-item">
                        <!-- Save Status -->
                        <input type="hidden" name="save_state" value="add" />
                        <form id="new-item-form">
                            <input type="hidden" name="parent_id" value="0" />
                            <input type="hidden" name="item_id" value="0" />
                            <input type="hidden" name="menus_id" value="" />

                            <label><span class="iconform arrowicon"></span><span class="form-title"></span></label>
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="edittext" class="col-sm-3 control-label">Text</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edittext" placeholder="Text" name="title">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="editcustom-link" class="col-sm-3 control-label">Link</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="editcustom-link" placeholder="http://www.example.com/home" name="custom-link">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="editicon" class="col-sm-3 control-label">Icon</label>
                                    <div class="col-sm-9">
                                        <a id="icons" class="btn btn-default btn-sm" href="#">Edit</a>
                                        <span data-iconseting="" class="iconView">No Icon</span>
                                        <input type="text" hidden="hidden" id="geticonPlacement">
                                        <input type="text" id="geticonseting" name="icon" vk_1fa1c="subscribed">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="editopenNewtab"></label>
                                    <div class=" col-sm-9">
                                        <input type="checkbox" id="editopenNewtab" name="new_link"> Open in new Tab? </div>
                                </div>
                            </div>
                            <p class="text-right p-r-15">
                                <button type="button" class="btn btn-black save-item">Add Item</button>
                                <button type="button" class="btn btn-default" data-action="cancel">Cancel</button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>