@if(count($product_list) > 0)
        <div class="row">
        @foreach($product_list as $p)
            <div class="col-lg-3">
                <div class="card">
                    <div class="white-box-pd buy-page">
                        <div class="tick_box_819713" style="position:absolute; left:370px; top:31px;">                  
                    
                    </div>  
                    <h3 style="padding-bottom:10px;" class="box-title m-b-0 ">{{$p['p_title']}}</h3>
                        @php
                            $encoded = base64_encode($p['id']);
                        @endphp
                        <div class="product-img">
                        @if(empty($p['p_image']))
                                <a href="<?php echo url('add_product/'.$encoded); ?>"><img src="{{asset('assets/images/logo-balls.png')}}" alt="{{$p['p_title']}} image1" style="width: 100%"></a>
                        @else
                            <?php 
                            $p_img_arr = explode(',', $p['p_image']);
                            //echo "<pre>";print_r($p_img_arr); echo "</pre>";
                            if(count($p_img_arr) > 1){
                            ?>
                            <div id="carouselExampleControls{{$p['id']}}" class="carousel slide" data-ride="carousel" style="width: 200px;margin: 0 auto;">
                                <div class="carousel-inner">
                                    @foreach($p_img_arr as $key=>$img)
                                    <div class="carousel-item @if($key == 0) active @endif">
                                        <a href="<?php echo url('add_product/'.$encoded); ?>"><img class="d-block w-100" src='{{asset("uploads/products/$img")}}' alt="{{$p['p_title']}} image{{$key+1}}" style="width: 100%; margin: 0 auto;"></a>
                                    </div>
                                    @endforeach
                                </div>

                                <a class="carousel-control-prev" href="#carouselExampleControls{{$p['id']}}" role="button" data-slide="prev" style="left: 10px;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls{{$p['id']}}" role="button" data-slide="next" style="right: 10px;">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <?php }else{ ?>
                                <a href="<?php echo url('add_product/'.$encoded); ?>"><img class="" src='{{asset("uploads/products/$p_img_arr[0]")}}' alt="{{$p['p_title']}} image1" style="width: 100%"></a>
                            <?php }?>
                        @endif
                        </div>
                        <div class="card-body">
                            <hr>
                            <!-- <h3 class="font-normal">{{$p['p_title']}}</h3>
                            <p class="m-b-0 m-t-10">{{$p['p_description']}}</p>
                             -->
                             <div style="float:left; margin-top:7px; margin-left:10px; background: #ffffff;  margin-bottom: 15px;">                         
                                            
                                    <div class="col-md-6" style="float:left; padding-bottom:10px;">
                                    <label class="text-success" style="position:absolute; left:-17px; top:8px;">Qty</label>
                                        <input type="number" id="qty_update{{$p['id']}}" class="form-control form-control-line qty_update" data-s_id="{{$p['id']}}" value="{{$p['p_quantity']}}">
                                    </div>
                                 <input type="hidden" name="code" id="code" value="{{$p['code']}}">
                                    @if($p['p_type']=='3')
                                    <div class="col-md-6" style="float:left;">
                                    <label class="text-info" style="position:absolute; left:-6px; top:8px;" title="Subscription Price"><i class="fa fa-tag"></i></label>
                                        <input type="number" id="subs-price_update{{$p['id']}}" class="form-control form-control-line subs-price_update" data-s_id="{{$p['id']}}" value="{{$p['p_subs_price']}}">
                                        <input type="hidden" id="price_update{{$p['id']}}" value="not">
                                    </div>
                                    @else
                                    <div class="col-md-6" style="float:left;">
                                    <label class="text-info" style="position:absolute; left:-6px; top:8px;" title="Sale Price"><i class="fa fa-tag"></i></label>
                                        <input type="number" id="price_update{{$p['id']}}" class="form-control form-control-line price_update" data-s_id="{{$p['id']}}" value="{{$p['p_selling_price']}}">
                                        <input type="hidden" id="subs-price_update{{$p['id']}}" value="not">
                                    </div>
                                    @endif
                                      </div>
                            <div class="update_button_center">
                                <input type="hidden" id="id{{$p['id']}}" value="{{$p['id']}}">
                                <button type="button" class="btn btn-success btn-rounded" onclick="updateall({{$p['id']}});"><i class="fa fa-check"></i>&nbsp;&nbsp;Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
        <div class="row">
            <div class="col-md-12">
              
            </div>
        </div>
    @else
    <div class="row">
        <div class="col-lg-4">

            No products are added yet.

        </div>
    </div>
    @endif