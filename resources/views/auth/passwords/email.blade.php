@extends('layouts.guest')

@section('content')


    <div class="o-page__card">
        <div class="c-card u-mb-xsmall">
            <header class="c-card__header u-pt-large">
                <a class="c-card__icon" href="#!">
                    <img src="{{ asset('admin-login/images/logo-icon.png')}}" alt="Dashboard UI Kit">
                </a>
                <h1 class="u-h3 u-text-center u-mb-zero">Reset Password.</h1>
            </header>
        </div>
    <div class="c-card u-mb-xsmall">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form class="c-card__body" action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="c-field u-mb-small">
                <label class="c-field__label" for="input1">Email Address:</label>
               {{-- <input class="c-input" type="email" name="email" id="input1" placeholder="clark@dashboard.com">--}}

                <input id="email" type="email" class="c-input {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="error">
                                       {{ $errors->first('email') }}
                                    </span>
                @endif
            </div>

            <button class="c-btn c-btn--info c-btn--fullwidth" type="submit">Send Password Reset Instructions
            </button>
        </form>
    </div>
    </div>


{{--
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}
@endsection
