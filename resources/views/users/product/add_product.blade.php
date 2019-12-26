@extends('layouts.customer')


@section('content')

    <div class="row">

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @elseif(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <style type="text/css">
            textarea.form-control {
                height: auto;
                resize: auto;
            }

            .list-group-item.active {
                z-index: 2;
                color: #fff;
                background: #03a9f3;
                border-color: #03a9f3;
            }

            .list-group-item:hover {
                z-index: 2;
                color: #fff;
                background: #03a9f3;
                border-color: #03a9f3;
            }

            ul.service_hours {
                padding-left: 0;
                width: 100%;
            }

            ul.service_hours ul.hours-select li button {
                margin-top: 20px;
            }

            ul.hours-select {
                padding-left: 28px;
            }

            ul.list_open_hours {
                padding-left: 28px;
            }

            @media (max-width: 767px) {
                .boxitems {
                    margin-top: 10px;
                }

                .boxheadingmobile {
                    display: block !important;
                    margin-top: 10px;
                    font-size: 14px;
                }
            }

        </style>

        <div class="col-md-12 col-lg-12">
            <div class="panel panel-info card">

                <div class="panel-heading">
                    <h4 class="m-b-0 text-white"><i class="fa fa-plus" style="font-size:22px;cursor: pointer;"></i>
                        <span style="float:right;">
                        <span class="barcode_wrapper"></span><i class="barcode_scan fa fa-barcode"
                                                                style="font-size:26px; cursor:pointer;"
                                                                onclick="barcode_scan();"></i>
                    </span>
                    </h4>
                </div>
                <div class="card-body">
                    <form id="add_product" enctype="multipart/form-data">
                        @csrf

                        @if(isset($product))
                            <input type="hidden" name="updateproducttype" value="{{$product[0]['p_type']}}"
                                   id="updateproductype">
                            <input type="hidden" name="productid" value="{{$product[0]['id']}}" id="productid">
                        @else
                            <input type="hidden" name="updateproducttype" value="" id="updateproductype">
                        @endif
                        <div class="form-body">

                            <div class="row p-t-20">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Title</label>&nbsp;&nbsp;<span
                                                class="text-purple add_description_show"
                                                style="font-size:12px; cursor:pointer;" onclick="show_desc(this)">(+ Description)</span>
                                        <input type="text" name="p_title" class="form-control"
                                               placeholder="Product Title"
                                               @if(isset($product)) value="{{$product[0]['p_title']}} " @endif required>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Quantity</label>
                                        <input type="number" name="p_quantity" class="form-control"
                                               placeholder="Quantity" min="1" max="1000" required
                                               <?php if(isset($product)) { ?>value=<?php echo $product[0]['p_quantity']; } else { ?> value="1" <?php } ?>>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Type</label>
                                        <br>
                                        @include('users.product.add_product.product_type', ['p_types' => $p_types])
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 wrapper_quality_display">
                                    <div class="form-group">
                                        <label class="control-label">Quality</label>
                                        <br>
                                        @include('users.product.add_product.product_quality')
                                    </div>
                                </div>
                            </div>

                            <div class="row p-t-20 descrip_hidden" style="display: none;">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        @if(isset($product))
                                            <textarea name="p_description" class="form-control" rows="5"
                                                      column="10">{{$product[0]['p_description']}}</textarea>
                                        @else
                                            <textarea name="p_description" class="form-control" rows="5"
                                                      column="10"></textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <!--/row-->
                        @if(isset($product))  <!-- If the user is editing the product -->
                            <input type="hidden" name="producttype" id="producttype" value="{{$product[0]['p_type']}}">
                            @endif
                            <div class="row" id="dynamic_load_type"> <!-- gets replaced when product type is changed-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label text-info " style="font-size:24px;"><i
                                                    class="fa fa-tag"></i> Sell to</label>
                                        @include('users.product.add_product.product_sell_to')
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pro_type_lend item_div">
                                        <label class="control-label text-warning" style="font-size:24px"> <i
                                                    class="fa fa-refresh"></i> Lend to </label><br/>
                                        @include('users.product.add_product.product_lend_to')
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <label class="control-label selling_product_price text-info"><i
                                                class="fa fa-tag"></i> Selling Price</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-info"
                                                    type="button">{{$currency_symbol[0]->symbol}}</button>
                                        </div>
                                        <input type="number" min="0" step="any" class="form-control" placeholder="Price"
                                               name="p_sell_price"
                                               @if(isset($product)) value="{{$product[0]['p_selling_price']}}" @endif>

                                    </div>

                                    <div class="item_lend_price">
                                        <label class="control-label text-warning"> <i class="fa fa-refresh"></i> Lending
                                            Price / Time</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-warning"
                                                        type="button">{{$currency_symbol[0]->symbol}}</button>
                                            </div>
                                            <input type="number" min="0" step="any"
                                                   class="form-control lending_price_val"
                                                   placeholder="Lending Price / Time" name="p_item_lend_price"
                                                   @if(isset($product)) value="{{$product[0]['p_lend_price']}}" @endif>
                                            <div class="input-group-prepend custom_dropdown_width">

                                                <select class="form-control" data-style="form-control btn-secondary "
                                                        name="p_price_per_optn" style="cursor: pointer;">
                                                    <option value="1"
                                                            @if(isset($product) && $product[0]['p_price_per_optn']=='1') selected @endif>
                                                        Per Day
                                                    </option>
                                                    <option value="2"
                                                            @if(isset($product) && $product[0]['p_price_per_optn']=='2') selected @endif>
                                                        Per Week
                                                    </option>
                                                    <option value="3"
                                                            @if(isset($product) && $product[0]['p_price_per_optn']=='3') selected @endif>
                                                        Per Month
                                                    </option>
                                                    <option value="4"
                                                            @if(isset($product) && $product[0]['p_price_per_optn']=='4') selected @endif>
                                                        Per Year
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Delivery Option if seller not ticked selected by customer -->
                        <input type="hidden" name="deliveryvalues" value="{{count($delivery)}}">
                        <div class="delivery_option" @if((isset($product) && $product[0]['p_type']!='1') || (count($delivery)=='0')) style="display: none;" @endif>
                            @if(Auth::user()->delivery_option=='0')
                                @if(isset($product))
                                    @php
                                        $p_delivery_option = explode(',',$product[0]['p_delivery_option']);
                                    @endphp
                                @endif
                                <div class="col-md-12">
                                    <label class="control-label text-info " style="font-size:24px;"><i
                                                class="fa fa-truck"></i> Delivery Options</label>
                                    @foreach($delivery as $deliveries)
                                        <div class="checkbox checkbox-success">
                                            <input id="deliveryoption{{$deliveries->id}}" type="checkbox"
                                                   name="delivery_option[]" class="fxhdr"
                                                   @if(isset($product) && in_array($deliveries->id,$p_delivery_option)) checked=""
                                                   @endif value="{{$deliveries->id}}">
                                            <label for="deliveryoption{{$deliveries->id}}"
                                                   style="color: #00c292;cursor: pointer;">{{$deliveries->delivery_provider}} </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>


                        <!-- upload image -->

                        <div class="row p-t-20">

                            <div class="col-md-12">


                                <div class="fileupload btn waves-effect waves-light text-info"
                                     style="float:left; width:100%; font-size:30px; border: 1px solid #03a9f3;">
                                <span>
                                    <i class="fa fa-upload"></i> 
                                    <span class="hidden-xs">Upload  Image</span>
                                </span>
                                    @if(isset($product) && $product[0]['p_image']!='')
                                        @php
                                            $countimage = explode(',',$product[0]['p_image']);
                                            $imageid = [];
                                        @endphp
                                        @for($i=0;$i<count($countimage);$i++)
                                            @php
                                                $imageid[$i] = 'image'.$i;
                                            @endphp
                                        @endfor
                                        @php
                                            $images = implode(',',$imageid);
                                            $imagename = implode(',',$countimage);
                                        @endphp
                                        <input type="hidden" name="pro_img[]" id="pro_img" value="{{$images}}">
                                        @for($l=0;$l<count($countimage);$l++)
                                            <input type="hidden" name="old_images[]" class="old-img"
                                                   value="{{$countimage[$l]}}"/>
                                        @endfor
                                        <input type="file" name="p_images[]" id="imgInp" class="upload"
                                               data-default-file="{{$imagename}}" multiple value="abc.jpg"/>
                                    @else
                                        <input type="file" name="p_images[]" id="imgInp" class="upload"
                                               data-default-file="" multiple/>
                                        <input type="hidden" name="pro_img[]" id="pro_img">
                                    @endif
                                    <input type="hidden" name="pro_images[]">
                                    @if(isset($product))
                                        <input type="hidden" name="roomname" id="roomname"
                                               value="{{$product[0]['room_id']}}">
                                        <input type="hidden" name="boxid" id="boxid" value="{{$product[0]['p_box']}}">
                                    @else
                                        <input type="hidden" name="roomname" id="roomname">
                                        <input type="hidden" name="boxid" id="boxid">
                                    @endif
                                    <input type="hidden" name="boxname" id="boxname">
                                </div>

                            </div>
                            <!-- <div class="col-md-6">    // Use Later on mobile app
                                <div class="fileupload btn waves-effect waves-light text-success" style="float:left; width:100%; font-size:30px; border: 1px solid #03a9f3; cursor:pointer;"><span style="cursor: pointer;"><i class="fa fa-camera" style="cursor: pointer;"></i> <span class="hidden-xs" style="cursor: pointer;">Take Picture</span></span>
                                <input type="file" name="p_camera[]"  id="imgInp"  accept="image/*" class="upload" data-default-file="" multiple />
                                </div>

                                <div class="upload_image_object">
                                    <div class="fileupload btn waves-effect waves-light text-info" style="float:left; width:100%; font-size:14px; border: 1px solid #e9ecef;min-height:38px; cursor:pointer;"><span><i class="fa fa-camera" style="color: #4e5666;"></i> <span class="hidden-xs" style="color: #4e5666;">Take Picture</span></span>
                                        <input type="file" name="p_camera[]"  id="imgCam" class="upload" data-default-file="" accept="image/*;capture=camera" capture="camera"/>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <!-- preview slider -->

                        <div class="row">
                            <div class="col-md-12">

                                <div src="#" id="displayMore">

                                    @if(isset($product) && $product[0]['p_image']!='')
                                        @php
                                            $countimage = explode(',',$product[0]['p_image']);
                                            $imageid = [];
                                        @endphp
                                        @for($i=0;$i<count($countimage);$i++)
                                            <div class="removeImg_div">
                                                <span onclick="removeImg(this);" data-img_id="image{{$i}}"
                                                      style="cursor:pointer">×</span>
                                                <img src="{{asset("uploads/products/$countimage[$i]")}}"
                                                     id="image{{$i}}">
                                            </div>
                                        @endfor
                                    @endif

                                </div>

                            </div>
                        </div>

                        <!-- boxing : for product type item only-->

                        <!--  <h1 class="box-title m-t-40 text-success item_div"><i class="ti-package"></i> BOX IT!</h1> -->
                    <!-- <div class="item_div">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Choose the room you're storing in</label>
                                    <select class="selectpicker" data-style="form-control btn-secondary" name="p_room">
                                        @foreach($p_room as $room)
                        <option value="{{$room['id']}}"> {{$room['display_text']}} </option>
                                        @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Label a box</label>
                            <select class="selectpicker" data-style="form-control btn-secondary" name="p_box">
                                <option value="1" data-content='<i class="ti-package"></i> Box 1'>Box 1</option>
                                <option value="2" data-content='<i class="ti-package"></i> Box 2'>Box 2</option>
                                <option value="3" data-content='<i class="ti-package"></i> Box 3'>Box 3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div> -->
                        <!-- Box Code From Contact25 -->
                        <div id="box_display item_div" class="box_div">
            <span class="boxitwrapper">
                <div class="row">
                    <div class="col-md-12">
                     <h1 class="box-title m-t-40 text-success" style="display: inline-block;margin-right:10px"><i
                                 class="ti-package"></i> BOX IT!</h1>
                     @if(Auth::user()->box_preference=='0')
                            <span class="box_it_show text-purple"
                                  style="font-size: 12px; cursor: pointer; color: #ab8ce4 !important;margin-right: 10px"
                                  onclick="show_box_div(this)">(+Show)</span><p id="hidep"
                                                                                style="display: inline-block;">Choose the room you're storing in </p>
                        @else
                            <span class="box_it_show text-purple"
                                  style="font-size: 12px; cursor: pointer; color: #fec107!important;margin-right: 10px"
                                  onclick="show_box_div(this)">(-Hide)</span><p id="hidep"
                                                                                style="display: inline-block;">Choose the room you're storing in </p>
                        @endif
                     
                     
                    </div>
                </div>
                <div class="row" id="boxit" @if(Auth::user()->box_preference=='0') style="display:none" @endif>
                    <div class="col-md-6">
                        <div class="collapse m-t-15 well" id="pgr3" aria-expanded="true">
                            
                        </div>
                        <ul class="list-group list-group-full">
                             @foreach($p_room as $key => $room)
                                <li class="list-group-item rooms room-66 <?php //if($key=='0') { echo 'active'; } ?> @if(isset($product) && $product[0]['room_id'] == $room['id']) active @endif"
                                    id="room{{$key}}" data-room-list="{{$room['id']}}"
                                    style="font-size:16px;cursor: pointer;" onclick="showbox({{$key}},{{$room->id}});">
                                @php
                                    $roomitems = \App\Product::where('room_id',$room->id)->where('p_quantity','!=','0')->get();
                                @endphp
                              <span class="badge badge-success" id="roomitemcount{{$room->id}}"
                                    style="float:right;">{{count($roomitems)}}</span><span class=" edit-66"
                                                                                           style="font-weight:bold; color: white"
                                                                                           id="roomeditbutton{{$room->id}}"><i
                                                class="fa fa-edit edit_room" data-room="66"
                                                onclick="edit_room({{$room->id}});"></i></span> <span
                                            class="name_show_all name_label_display_66"
                                            id="roomname{{$room->id}}">{{$room->display_text}}</span><span
                                            id="editpart{{$room->id}}" style="display: none;"><input type="text"
                                                                                                     name="roomedit"
                                                                                                     id="roomedit{{$room->id}}"
                                                                                                     class="form-control"
                                                                                                     value="{{$room->display_text}}"><span
                                                onclick="updateroom({{$room->id}});"><i class="fas fa-save"></i>&nbsp;save</span></span>
                            </li>
                            @endforeach
                            <li class="list-group-item newboxtextbox active" style="display: none;cursor: pointer;">
                                <input type="text" class="form-control" name="newroom" placeholder="+ Add Room"><span>+Add Room</span>
                            </li>
                            <li class="list-group-item add_new_room_select" data-newroom="1" onclick="newroom();"
                                style="cursor: pointer;">
                             +
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="collapse m-t-15 well" id="pgr3" aria-expanded="true">
                              
                         </div>
                    <div id="current_room" style="display:none;">
                    66
                    </div>
                    <div id="current_box" style="display:none;">
                    60
                    </div>
                    <div id="boxheading" style="display: none;">
                        <p class="boxheadingmobile" style="display: none;">Put in a box</p>
                    </div>
                     @foreach($p_room as $key2 => $room)
                            @php
                                $box = App\p_boxe::where('p_rooms_id',$room->id)->where('status','1')->get();
                            @endphp
                            @if(count($box)>'0')
                                <ul class="list-group room-id-66 room-id-group-hide boxitems {{--@if($key2=='0') echo 'active'; @endif" "="--}}"
                                    id="box{{$key2}}"
                                    @if(isset($product) && $product[0]['p_box']!='' && in_array($product[0]['p_box'],$box->pluck('id')->toArray())) style="display:block"
                                    @else style="display: none;" @endif>
                     @foreach($box as $boxcount => $boxes)
                                        @php
                                            $roomitems = App\Product::where('room_id',$room->id)->get();
                                            $boxitemcount = App\Product::where('p_box',$boxes->id)->where('p_quantity','!=','0')->get();
                                            $emptybox = App\p_boxe::where('p_rooms_id',$room->id)->where('status','0')->take(1)->get();
                                        @endphp


                                        <li class="list-group-item box-60 box-item boxes{{$boxes->id}} @if(isset($product) && $product[0]['p_box']==$boxes->id) active @endif"
                                            id="boxdelete{{$boxes->id}}" data-box="60" data-room-list="66"
                                            onclick="boxselect({{$key2}},{{$boxes->id}},{{$boxcount+1}});"
                                            style="cursor: pointer;">
                     <span class="badge badge-success" style="float:right;" id="boxitem{{$boxes->id}}"
                           value="{{count($boxitemcount)}}">{{count($boxitemcount)}} </span>@if(count($box)>1)<i
                                                    class="fas fa-redo" id="icon{{$boxes->id}}"
                                                    onclick="movebox({{$boxes->id}});"
                                                    style="display: inline-block;"></i>@endif&nbsp;<i
                                                    class="ti-package"></i><p id="boxname{{$room->id}}{{$boxcount+1}}"
                                                                              style="display: inline-block;">&nbsp;{{$boxes->box_name}} </p><div
                                                    id="move{{$boxes->id}}" class="move" style="display: none;">
                        
                        <select class="form-control" id="moveto{{$boxes->id}}">
                            <option value="0">Move To</option>
                            @foreach($p_room as $key => $dropdownroom)
                                <optgroup label="{{$dropdownroom->display_text}}"></optgroup>
                                @php
                                    $dropdownbox = App\p_boxe::where('p_rooms_id',$dropdownroom->id)->where('status','1')->get();
                                @endphp
                                @foreach($dropdownbox as $key3 => $boxes2)
                                    @if($boxes2->id!=$boxes->id)
                                        <option value="{{$boxes2->id}}">{{$boxes2->box_name}}</option>
                                        @endif
                                        @endforeach
                                        </optgroup>
                                        @endforeach
                    </select>
                    <span onclick="updatemovebox({{$key2}},{{$room->id}},{{$boxes->id}},{{$boxcount+1}},{{count($box)}},{{count($boxitemcount)}});"><i
                                class="fas fa-save"></i>&nbsp;save</span></div>
                        </li>


                                    @endforeach
                                    @if(count($emptybox)>'0')
                                        @foreach($emptybox as $boxname)
                                            <li class="list-group-item newbox box-item add_new_box_select"
                                                data-newbox="1" id="box{{$key2}}{{$boxname->id}}" data-room-list="66"
                                                onclick="newbox2({{$key2}},'{{$boxname->box_name}}',{{$boxname->id}});"
                                                style="cursor: pointer;">
                         + &ensp;&ensp;
                        </li>
                                        @endforeach
                                    @else
                                        <li class="list-group-item newbox box-item add_new_box_select" data-newbox="1"
                                            id="box{{$key2}}{{$boxcount+2}}" data-room-list="66"
                                            onclick="newbox({{$key2}},{{$boxcount+2}});" style="cursor: pointer;">
                         + &ensp;&ensp;
                        </li>
                                    @endif
                 </ul>
                            @else
                                <ul class="list-group room-id-66 room-id-group-hide boxitems" style="display: none;"
                                    id="box{{$key2}}">
                            <li class="list-group-item newbox add_new_box_select" data-newbox="1" data-room-list="66"
                                id="box{{$key2}}1" style="cursor: pointer;" onclick="newbox({{$key2}},1);">
                         + &ensp;&ensp;
                        </li>
                            </ul>
                            @endif
                        @endforeach
                         <li class="list-group-item new_room_box active" data-newroombox="1"
                             style="display:none;cursor: pointer;">
                            <span class="badge badge-success" style="float:right;">0</span> <i class="ti-package"></i>  Box 1&ensp;&ensp;
                        </li>
                    </div>  
                     </div>
                 </span>
                        </div>
                        <!-- Box Code from contact25 ends here -->
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-actions">
                                    @if(isset($product))
                                        <button type="button" id="submitbutton" class="btn btn-info"> Update</button>
                                    @else
                                        <button type="button" id="submitbutton" class="btn btn-info"> Add</button>
                                    @endif
                                    <button type="reset" class="btn btn-inverse">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
