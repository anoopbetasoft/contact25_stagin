@extends('layouts.admin')


@section('content')

<div class="container-fluid">
    
    <?php
    if(!empty($customers)){
        $count = 0;
        foreach($customers as $c){ 
            if($count % 3 == 0){
            ?>
            <div class="row">
            <?php } ?>    
            <div class="col-xl-4">
                <div class="c-candidate">
                    <div class="c-candidate__cover">
                        <!-- <img src="img/candidate4.jpg" alt="Candidate's Cover photo"> -->
                    </div>
                    
                    <div class="c-candidate__info">
                        <div class="c-candidate__avatar">
                            <img src="{{asset('assets/images/icon/staff.png')}}" alt="Candiates's Avatar">
                        </div>

                        <div class="c-candidate__meta">
                            <h3 class="c-candidate__title">{{$c['name']}}
                                <span class="c-candidate__country">
                                    
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>{{$c['email']}}

                                </span>
                            </h3>
                            
                            <div class="c-candidate__actions">
                                <!-- <a href="#"><i class="fa fa-trash-o"></i></a>
                                <a href="#"><i class="fa fa-cog"></i></a> -->
                            </div>
                        </div>
                    </div>

                    <div class="c-candidate__footer">
                        <a href="{{url('impersonate',$c['id'])}}" target="_blank" class="c-btn c-btn--info">
                            <i class="fa fa-user-o u-mr-xsmall u-opacity-heavy"></i>Impersonate
                        </a>
                        @if(empty($c['email_verified_at']))
                        <a target="_blank" class="c-btn c-btn--danger">
                            <i class="fa fa-exclamation u-mr-xsmall u-opacity-heavy"></i>Not Verified
                        </a>
                        @else
                        <a target="_blank" class="c-btn c-btn--success">
                            <i class="fa fa-check u-mr-xsmall u-opacity-heavy"></i>Verified
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        <?php 
            if($count % 3 == 2){ echo '</div>'; }  $count++;
        }
    }else{  
        echo "no customers yet";
    }

    ?>
    </div>
</div>

@endsection
