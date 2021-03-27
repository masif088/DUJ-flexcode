@extends('frontend.layouts.auth')
@section('auth')
    <div class="authentication-box">
        <div class="mt-4">
            <div class="card-body">
                <div class="cont text-center">
                    <div>
                        <form class="theme-form" action="{{route('login')}}" method="POST">
                            @csrf
                            <h4>LOGIN</h4>
                            <h6>Masukkan Email dan Password anda</h6>
                            <div class="form-group">
                                <label class="col-form-label pt-0">Email</label>
                                <input  id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email"  autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Password</label>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="current-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                                @enderror
                            </div>
                            <div class="checkbox p-0">
                                <input id="checkbox1" class="form-check-input" type="checkbox" name="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="checkbox1">
                                    {{ __('Ingat Akun Saya') }}
                                </label>
                            </div>
                            <div class="form-group row mt-3 mb-0">
                                <button class="btn btn-primary btn-block" type="submit">LOGIN</button>
                            </div>
                        </form>
                    </div>
                    <div class="sub-cont">
                        <div class="img">
                            <div class="img__text m--up">
                                <h2>PT Dira Utama Jaya</h2>

                            </div>
                        </div>
                        <div>
                            <form class="theme-form" action="{{route('login')}}" method="POST">
                                @csrf
                                <h4>LOGIN</h4>
                                <h6>Masukkan Email dan Password anda</h6>
                                <div class="form-group">
                                    <label class="col-form-label pt-0">Email</label>
                                    <input  id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email"  autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                                    @enderror
                                </div>
                                <div class="checkbox p-0">
                                    <input id="checkbox1" class="form-check-input" type="checkbox" name="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="checkbox1">
                                        {{ __('Ingat Akun Saya') }}
                                    </label>
                                </div>
                                <div class="form-group row mt-3 mb-0">
                                    <button class="btn btn-primary btn-block" type="submit">LOGIN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $( document ).ready(function() {
            localStorage.removeItem("login_session");
        });
    </script>
@endsection
