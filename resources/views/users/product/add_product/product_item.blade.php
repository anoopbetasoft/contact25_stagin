<div class="col-md-3">
    <div class="form-group">
        <label class="control-label text-info " style="font-size:24px;"><i class="fa fa-tag"></i> Sell to</label>
        @include('users.product.add_product.product_sell_to')
    </div>
</div>

<div class="col-md-3">
    <div class="form-group pro_type_lend item_div">
        <label class="control-label text-warning" style="font-size:24px"> <i class="fa fa-refresh"></i> Lend to </label><br/>
        @include('users.product.add_product.product_lend_to')
    </div>
</div>


<div class="col-md-6">
    <label class="control-label selling_product_price text-info"><i class="fa fa-tag"></i> Selling Price</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <button class="btn btn-info" type="button">&pound;</button>
        </div>
        <input type="number" min="0"  step="any" class="form-control" placeholder="Price" name="p_sell_price">
    </div>
    <div class="item_lend_price">
        <label class="control-label text-warning"> <i class="fa fa-refresh"></i> Lending Price / Time</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <button class="btn btn-warning" type="button">&pound;</button>
            </div>
            <input type="number" min="0"  step="any" class="form-control lending_price_val" placeholder="Lending Price / Time" name="p_item_lend_price">
            <div class="input-group-prepend custom_dropdown_width">
                                            
                <select class="form-control" data-style="form-control btn-secondary " name="p_price_per_optn">
                    <option value="1">Per Day</option>
                    <option value="2">Per Week</option>
                    <option value="3">Per Month</option>
                    <option value="4">Per Year</option>
                </select>
            </div>
        </div>
    </div>
</div>