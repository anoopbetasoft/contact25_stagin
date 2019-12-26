@extends('layouts.customer')


@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="white-box">
                <div class="row text-center m-t-10">
                    <div class="col-md-12"> <h1>Summary</h1></div>
                </div>
                <div class="user-btm-box" style="padding-top:0px;">
                    <!-- /.row -->
                    <hr>
                    <!-- .row -->
                    <div class="row text-center m-t-10">
                        <div class="col-md-6 b-r" style="padding-top:5px;"><strong>Pending</strong><p style="font-size:10px;">(available after ##{{$system_setting[0]['clear_credit_period']}}## days)</p></div>
                        <div class="col-md-6" style="padding-top:2px;"><p style="font-size:30px">{{$pending_money[0]['currencysymbol']['symbol']}}{{number_format($pending_money->sum('value'))}}</p></div>
                    </div>
                    <!-- /.row -->
                    <hr>
                    <!-- .row -->
                    <div class="row text-center m-t-10">
                        <div class="col-md-12"><strong>Available Now:</strong>
                            <h1 style="font-size:40px;font-weight:bold;" class="text-success">{{$available_money[0]['currency_symbol']}}{{number_format($available_money[0]['wallet'])}}</h1>
                            <div class="form-actions">
                                <button type="button" id="submitbutton" class="btn btn-info"> Request Transfer</button>

                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- /.row -->
                    <div class="col-md-4 col-sm-4 text-center">

                    </div>
                    <div class="col-md-4 col-sm-4 text-center">
                       {{-- <h1 style="font-size:40px">£125</h1>--}}
                    </div>



                    <!-- .row -->
                    <div class="row text-center m-t-10">
                        <div class="col-md-12">
                            @if(count($paid_details)>'0')
                            <h1 style="font-size:40px">{{$paid_details[0]['currency_symbol']['symbol']}}{{number_format($paid_details->sum('amount'))}}</h1>
                            @else
                            <h1 style="font-size:40px">{{Auth::user()->currency_symbol}}0</h1>
                            @endif
                            <strong>Paid so far</strong></div>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="white-box">
                <!-- .tabs -->
                <ul class="nav nav-tabs tabs customtab">
                    <li class="active tab"><a href="#home" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-home"></i></span> <span class="hidden-xs">Activity</span> </a> </li>

                    <li class="tab"><a href="#settings" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Bank Detail</span> </a> </li>
                </ul>
                <!-- /.tabs -->
                <div class="tab-content">
                    <!-- .tabs 1 -->
                    <div class="tab-pane active" id="home">
                        <div class="steamline">
                            <div class="sl-item">
                                <div class="sl-left"> <img src="plugins/images/users/no_user.jpg" alt="user" class="img-circle"> </div>
                                <div class="sl-right">
                                    <div class="m-l-40"><a href="#" class="text-info">Payment</a> <span class="sl-date">Tue, 5 June 2018</span>
                                        <p>BACS Transfer £9.99 to Acc: 123456 (12-34-56)</p>

                                    </div>
                                </div>
                            </div>


                            <hr>
                            <div class="sl-item">
                                <div class="sl-left"> <img src="plugins/images/users/no_user.jpg" alt="user" class="img-circle"> </div>
                                <div class="sl-right">
                                    <div class="m-l-40"><a href="#" class="text-info">Sale</a> <span class="sl-date">Tue, 12 Feb 2018</span>
                                        <p class="m-t-10">£9.99 (XYZ Product) (Sold for £12.99 - 15% = £9.99)</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="sl-item">
                                <div class="sl-left"> <img src="plugins/images/users/no_user.jpg" alt="user" class="img-circle"> </div>
                                <div class="sl-right">
                                    <div class="m-l-40"><a class="text-info">Account Opened - Congratulations!</a> <span class="sl-date">Mon, 12 Feb 2018</span>
                                        <p>£0.00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.tabs1 -->

                    <!-- .tabs3 -->
                    <div class="tab-pane" id="settings">
                        <form class="form-horizontal form-material" id="my_money_form">
                            <div class="form-group">
                                <label class="col-md-12">First Name:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="First Name" class="form-control form-control-line" name="firstName" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['firstName']}}" @else value="{{$userdetails[0]['name']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Last Name:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Last Name" class="form-control form-control-line" name="lastName" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['lastName']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Email:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Email" class="form-control form-control-line" name="email" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['email']}}" @else value="{{$userdetails[0]['email']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Phone: (*Optional)</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Phone" class="form-control form-control-line" name="phone" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['phone']}}" @else value="{{$userdetails[0]['contact_code']}}{{$userdetails[0]['contact_no']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Date Of Birth:</label>
                                <div class="col-md-12">
                                    <input type="date" placeholder="Date Of Birth" class="form-control form-control-line" name="dateOfBirth" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['dateOfBirth']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">SSN: *(optional) </label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="SSN" class="form-control form-control-line" name="ssn" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['ssn']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Street Address:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Street Address" class="form-control form-control-line" name="streetAddress" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['streetAddress']}}" @else value="{{$userdetails[0]['street_address1']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Locality:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Locality" class="form-control form-control-line" name="locality" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['locality']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Region:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Region" class="form-control form-control-line" name="region">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Postal Code:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Postal Code" class="form-control form-control-line" name="postalCode" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['postalCode']}}" @else value="{{$userdetails[0]['pincode']}}" @endif>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-12">Legal Name:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Legal Name" class="form-control form-control-line" name="legalName" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['legalName']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">DBA Name:(*Optional)</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="DBA Name" class="form-control form-control-line" name="dbaName" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['dbaName']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Tax Id:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Tax Id" class="form-control form-control-line" name="taxId" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['taxId']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Street Address:(*Optional)</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Street Address" class="form-control form-control-line" name="streetAddress2" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['streetAddress2']}}" @else value="{{$userdetails[0]['street_address1']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Locality:(*Optional)</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Locality" class="form-control form-control-line" name="locality2" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['locality2']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Region:(*Optional)</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Region" class="form-control form-control-line" name="region2" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['region2']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Postal Code:(*Optional)</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Locality" class="form-control form-control-line" name="postalCode2" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['postalCode2']}}" @else value="{{$userdetails[0]['pincode']}}" @endif>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-md-12">Bank Name:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Bank name" class="form-control form-control-line" name="destination" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['destination']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Email:(*Optional)</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Email" class="form-control form-control-line" name="email2" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['email2']}}" @else value="{{$userdetails[0]['email']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Mobile Phone:(*Optional)</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Mobile Phone" class="form-control form-control-line" name="mobilePhone" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['mobilePhone']}}" @else value="{{$userdetails[0]['contact_no']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Account Number:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Account Number" class="form-control form-control-line" name="accountNumber" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['accountNumber']}}" @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Routing Number:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Routing Number" class="form-control form-control-line" name="routingNumber" @if(count($merchant_detail)>'0') value="{{$merchant_detail[0]['routingNumber']}}" @endif>
                                </div>
                            </div>


                           {{-- <div class="form-group">
                                <label class="col-md-12">Bank Acc:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="123456" class="form-control form-control-line">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Sort Code:</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="12-34-56" class="form-control form-control-line">
                                </div>
                            </div>--}}


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success" type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.tabs3 -->
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $('#my_money_form').submit(function (event) {
            event.preventDefault();
            //event.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ url('/create_merchant_account') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    if (data.success == 0) { //error
                        Swal.fire("Error", data.message, "warning");
                    }
                    if (data.success == 1) { //settings updated
                        Swal.fire('Success', 'Your Sub Merchant Account with braintree created successfully');
                        // location.reload();
                    }
                },
                error: function (data) {
                    Swal.fire('Error occured while inserting data');
                }
            });

        });
        $(".itemtime").each(function (index) {
            console.log(index + ": " + $(this).children('.itemcollectiontime').val());
        });

    </script>
    @endsection