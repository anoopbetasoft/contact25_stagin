 <!-- selling price -->

<!-- <div class="col-md-6">
    <div class="form-group">
        <label class="control-label">Selling Price</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend custom_dropdown_width">
                <button class="btn btn-info" type="button">&pound;</button>
            </div>
            <input type="text" class="form-control input_types" placeholder="Selling Price" name="p_subs_price">
        </div>
    </div>
</div> -->
<!-- group -->
<div class="col-md-6 group_subs" style="display: none">
    <div class="form-group">
        <label class="control-label">Group Session</label>
        <input type="number"  class="form-control input_types" placeholder="Lending Price / Time"  name="p_subs_group">
    </div>
</div>

<!-- location -->



<div class="col-md-6 location_subs" style="display: none">
    <div class="form-group">
        <label class="control-label">Location</label>
        <input type="text" class="form-control input_types" placeholder="Location" name='p_subs_location'>
    </div>
</div>

<div class="col-md-6 location_subs" style="display:none">
    <label class="control-label">Radius</label>
    <div class="input-group mb-3">
        <input type="number"  class="form-control input_types" placeholder="miles / km you will travel to carry out this service"   name="p_subs_radius">
        <div class="input-group-prepend custom_dropdown_width">
            
            <select class="selectpicker" data-style="form-control btn-secondary" name="p_subs_radius_options">
                <option value="0" selected="selected">No Radius</option>
                <option value="1">Distance (Km)</option>
                <option value="2">Distance (Miles)</option>
            </select>
        </div>
    </div>
</div>

<!-- time -->
<div class="col-md-6 time_subs" style="display: none">
    <div class="form-group" >
        <label class="control-label">Start Time</label>
        <input type="text" class="form-control input_types" placeholder="select Time" name="p_subs_time" id="p_subs_time">
    </div>
</div>


<div class="col-md-6 time_subs" style="display:none">
    <label class="control-label">Repeat</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <select class="selectpicker" data-style="form-control btn-secondary" name="p_subs_repeat_option">
                <option value="1">Daily</option>
                <option value="2">Weekly</option>
                <option value="3">Monthly</option>
                <option value="4">Anually</option>
            </select>
        </div>
        <input type="number"  class="form-control input_types" name="p_subs_repeat" placeholder="x times">
        <div class="input-group-append">
            <span class="input-group-text" style="cursor: pointer;">
                <div class="custom-control custom-checkbox" style="cursor: pointer;">
                    <input type="checkbox" class="custom-control-input" id="subs_time_forever" placeholder="" name="p_subs_forever">
                    <label class="custom-control-label" for="subs_time_forever" style="cursor: pointer;">Forever</label>
                </div>
            </span>
           
        </div>
    </div>
</div>

<div class="col-md-6">
    <label class="control-label">Product Image</label>
    
    <div class="upload_image_object">
        <div class="fileupload btn waves-effect waves-light text-info" style="float:left; width:100%; font-size:14px; border: 1px solid #e9ecef;min-height:38px; cursor:pointer;"><span><i class="fa fa-upload" style="    color: #4e5666;"></i> <span class="hidden-xs" style="    color: #4e5666;">Upload  Image</span></span>
            <input type="file" name="p_images[]"  id="imgInp"  accept="image/*" class="upload" data-default-file=""     multiple />
        </div>
    </div>
</div>