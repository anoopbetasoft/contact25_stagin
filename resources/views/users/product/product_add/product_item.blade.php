
<!-- Lending price -->
<div class="col-md-6 item_lend_price">
	<label class="control-label">Lending Price / Time</label>
	<div class="input-group mb-3">
        <div class="input-group-prepend">
            <button class="btn btn-info" type="button">{{$currency_symbol[0]->symbol}}</button>
        </div>
        <input type="number" class="form-control lending_price_val" placeholder="Lending Price / Time" name="p_item_lend_price">
        <div class="input-group-prepend custom_dropdown_width">
            
            <select class="selectpicker" data-style="form-control btn-secondary" name="p_price_per_optn">
                <option value="1">Per Day</option>
                <option value="2">Per Week</option>
                <option value="3">Per Month</option>
                <option value="4">Per Year</option>
            </select>
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