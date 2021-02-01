@extends('frontend.layouts.auth')
@section('auth')
  <div class="authentication-box">
    <div class="mt-4">
      <div class="card-body">
        <div class="cont text-center">
          <div>
            <form class="theme-form">
              <h4>Signup</h4><br>
              <div class="form-group">
                <label class="col-form-label pt-0">Name</label>
                <input  id="name" type="text"
                              class="form-control @error('name') is-invalid @enderror" name="name"
                              value="{{ old('name') }}" required autocomplete="name"  autofocus>
                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="exampleFormControlSelect9">Role</label>
                <select class="form-control digits" id="exampleFormControlSelect9">
                  <option disabled selected hidden></option>
                  <option>admin</option>
                  <option>teknisi</option>
                  <option>ketua cabang</option>
                  <option>Head office</option>
                </select>
              </div>
              <div class="form-group ">
                <label class="col-form-label pt-0">Telephone</label>
                <input  id="nohp" type="tel"
                              class="form-control @error('nohp') is-invalid @enderror" name="nohp"
                              value="{{ old('nohp') }}" required autocomplete="nohp"  autofocus>
                @error('nohp')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
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
              <div class="form-group row mt-3 mb-0">
                <button class="btn btn-primary btn-block" type="submit">Signup</button>
              </div>
            </form>
          </div>
          <div class="sub-cont">
            <div class="img">
              <div class="img__text m--up">
                <h2>PT Wira Utama Jaya</h2>

              </div>
            </div>
            <div>
              <form class="theme-form">
                <h4>Signup</h4><br>
                <div class="form-group">
                  <label class="col-form-label pt-0">Name</label>
                  <input  id="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name') }}" required autocomplete="name"  autofocus>
                  @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleFormControlSelect9">Role</label>
                  <select class="form-control digits" id="exampleFormControlSelect9">
                    <option disabled selected hidden></option>
                    <option>admin</option>
                    <option>teknisi</option>
                    <option>ketua cabang</option>
                    <option>Head office</option>
                  </select>
                </div>
                <div class="form-group ">
                  <label class="col-form-label pt-0">Telephone</label>
                  <input  id="nohp" type="tel"
                                class="form-control @error('nohp') is-invalid @enderror" name="nohp"
                                value="{{ old('nohp') }}" required autocomplete="nohp"  autofocus>
                  @error('nohp')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
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
                <div class="form-group row mt-3 mb-0">
                  <button class="btn btn-primary btn-block" type="submit">Signup</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
