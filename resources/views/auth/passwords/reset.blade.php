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

		<div class="o-page__card">

			<div class="c-card u-mb-xsmall">
				@if (session('status'))
					<div class="alert alert-success" role="alert">
						{{ session('status') }}
					</div>
				@endif
				<form class="c-card__body" action="{{ route('password.update') }}" method="POST">
					@csrf
					<input type="hidden" name="token" value="{{ $token }}">

					<div class="c-field u-mb-small">
						<label class="c-field__label" for="input1">E-mail address</label>
						<input class="c-input {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
						       value="{{ $email ?? old('email') }}" required autofocus type="email" id="input1">
						@if ($errors->has('email'))
							<span class="error">
                                       {{ $errors->first('email') }}
                                    </span>
						@endif
					</div>


					<div class="c-field u-mb-small">
						<label class="c-field__label" for="input2">Password</label>
						<input class="c-input {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password"
						       name="password" id="input2"
						>
						@if ($errors->has('password'))
							<span class="error">
                                        {{ $errors->first('password') }}
                                    </span>
						@endif

					</div>

					<div class="c-field u-mb-small">
						<label class="c-field__label" for="input2">Confirm Password</label>
						<input id="password-confirm" type="password" class="form-control"
						       name="password_confirmation" required>

					</div>


					<button class="c-btn c-btn--info c-btn--fullwidth" type="submit"> {{ __('Reset Password') }}
					</button>
				</form>
			</div>
		</div>



	{{--

		<section id="wrapper">
			<div class="login-register" style="background-image:url({{asset('admin-login/images/login-register.jpg);')}})">
				<div class="login-box card">
					<div class="card-body">
						<div class="form-horizontal form-material">

							<h3 class="text-center m-b-20"><img src="{{asset('admin-login/images/logo-icon.png')}}"
																alt="Home"><br>

								<img src="{{asset('admin-login/images/logo-text.png')}}" alt="Home"></h3>
							<form method="POST" action="{{ route('password.update') }}">
								@csrf

								<input type="hidden" name="token" value="{{ $token }}">

								<div class="form-group row">
									<label for="email"
										   class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

									<div class="col-md-6">
										<input id="email" type="email"
											   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
											   name="email" value="{{ $email ?? old('email') }}" required autofocus>

										@if ($errors->has('email'))
											<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
										@endif
									</div>
								</div>

								<div class="form-group row">
									<label for="password"
										   class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

									<div class="col-md-6">
										<input id="password" type="password"
											   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
											   name="password" required>

										@if ($errors->has('password'))
											<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
										@endif
									</div>
								</div>

								<div class="form-group row">
									<label for="password-confirm"
										   class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

									<div class="col-md-6">
										<input id="password-confirm" type="password" class="form-control"
											   name="password_confirmation" required>
									</div>
								</div>

								<div class="form-group row mb-0">
									<div class="col-md-6 offset-md-4">
										<button type="submit" class="btn btn-primary">
											{{ __('Reset Password') }}
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
	--}}

@endsection
