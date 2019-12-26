@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row u-mb-large">
        <div class="col-12">
            <div class="c-tabs">
            
                <ul class="c-tabs__list c-tabs__list--splitted nav nav-tabs" id="myTab" role="tablist">
                    <li class="c-tabs__item"><a class="c-tabs__link active" id="nav-home-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fa fa-user-o u-mr-xsmall"></i>Basic</a></li>
                    <li class="c-tabs__item"><a class="c-tabs__link" id="nav-home-tab" data-toggle="tab" href="#return_setting" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fa fa-user-o u-mr-xsmall"></i>Return Setting</a></li>
                    {{--<li class="c-tabs__item"><a class="c-tabs__link" id="nav-home-tab" data-toggle="tab" href="#remind_setting" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fa fa-user-o u-mr-xsmall"></i>Reminder Setting</a></li>--}}

                </ul>

                <div class="c-tabs__content tab-content" id="nav-tabContent">
                    <div class="c-tabs__pane active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                        @include('admin.system_setting.basic')
                    </div>
                    <div class="c-tabs__pane" id="return_setting" role="tabpanel" aria-labelledby="basic-tab">
                        @include('admin.system_setting.return_setting')
                    </div>
                 {{--   <div class="c-tabs__pane" id="remind_setting" role="tabpanel" aria-labelledby="basic-tab">
                        @include('admin.system_setting.reminder')
                    </div>--}}


                </div>
            </div>

        </div><!-- // .col-12 -->
    </div>

   <!--  <div class="row">
        <div class="col-lg-6">
            <div class="c-credit-card u-mb-large">
                <div class="c-credit-card__card">
                    <img class="c-credit-card__logo" src="img/logo-visa.png" alt="Visa Logo">
                    <h5 class="c-credit-card__number">**** **** **** 3528</h5>
                    <p class="c-credit-card__status">Valid Thru 11/18</p>
                </div>

                <div class="c-credit-card__user">
                    <h3 class="c-credit-card__user-title">Your Bank Account</h3>
                    <p class="c-credit-card__user-meta">
                        <span class="u-text-mute">User Number:</span> 582 458
                    </p>
                    <p class="c-credit-card__user-meta">
                        <span class="u-text-mute">User ID:</span> WX84579522CY
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="c-card u-p-medium u-mb-medium">

                <div class="u-flex u-justify-between u-align-items-center u-mb-small">
                    <h4 class="u-h5 u-mb-zero u-text-bold">How to use Dashboard</h4>
                    <a class="u-text-small" href="#">Visit FAQ Page</a>
                </div>
                
                <ul>
                    <li class="u-mb-xsmall">
                        <a class="u-text-small u-text-dark" href="#">How can I connect my bank account?</a>
                    </li>

                    <li class="u-mb-xsmall">
                        <a class="u-text-small u-text-dark" href="#">Why Dashboard doesn�t show any data?</a>
                    </li>
                    <li>
                        <a class="u-text-small u-text-dark" href="#">If I change my avatar in one version will it appears in next version?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div> -->
</div>

<!-- edit avatar -->
<!-- <div id="editAvatar" class="modal fade" role="dialog">
  <div class="modal-dialog">

   
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div> -->
@endsection





