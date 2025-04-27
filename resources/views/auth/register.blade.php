<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/front') }}/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="{{ asset('assets/front') }}/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/front') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link href="{{ asset('assets/front') }}/css/style.css?v={{ date('His') }}" rel="stylesheet">
    <link href="{{ asset('assets/front') }}/css/rtl.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/plugins/apexcharts/apexcharts.css">
    <link rel="icon" type="image/png" href="{{ asset('assets/front') }}/images/favicon.png" />
    @stack('styles')
    <title>{{ config('app.name') }}</title>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-R79SKJ9L03"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-R79SKJ9L03');
    </script>
</head>
<body>
<div class="container">
    <div class="text-center mt-3">
        <a class="navbar-brand" href="{{ route('login') }}">
            <img class="img-fluid" src="{{ asset('assets/admin/images/logo.png') }}">
        </a>
    </div>
    @include('flash::message')

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="row my-3">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="bordered rounded p-4">
                    <div class="row">
                         <div class="mb-1 col-md-6  @error('name') is-invalid @enderror">
                            <label class="form-label">{{ __('users.name') }}</label>
                            <input class="form-control" name="name" type="text" value="{{ $item->name ?? old('name') }}">
                            @error('name')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-6  @error('email') is-invalid @enderror">
                            <label class="form-label">{{ __('users.email') }}</label>
                            <input class="form-control input" name="email"  placeholder="" type="email" value="{{ $item->email ?? old('email') }}"
                                   autocomplete="false" >
                            @error('email')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('password') is-invalid @enderror">
                            <label class="form-label">{{ __('users.password') }}</label>
                            <input class="form-control input" name="password"  placeholder="" type="password"
                                   autocomplete="false" >
                            @error('password')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="mb-1 col-md-6  @error('phone') is-invalid @enderror">
                            <label class="form-label">{{ __('users.phone') }}</label>
                            <input class="form-control" name="phone" type="text" value="{{ $item->phone ?? old('phone') }}">
                            @error('phone')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                      
                        <div class="mb-1 col-md-6 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('users.image') }}</label>
                            <input type="file" class="form-control input" name="image" id="image">
                            @error('image')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(!empty($item->image))
                                    <img src="{{ $item->photo }}"
                                         class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>
                        <div class="mb-1 col-md-6 @error('passport') is-invalid @enderror">
                            <label class="form-label" for="passport">{{ __('users.passport') }}</label>
                            <input type="file" class="form-control input" name="passport" id="passport">
                            @error('passport')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(isset($item) && !empty($item->passport))
                                    @if(pathinfo($item->passport, PATHINFO_EXTENSION)=='pdf')
                                        <a href="{{ $item->passport }}" download>
                                        <img src="{{ asset('default.jpg') }}" class="img-fluid img-thumbnail">
                                        </a>
                                        @else
                                        <a href="{{ $item->passport }}" download>
                                        <img src="{{ $item->passport }}" class="img-fluid img-thumbnail">
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="mb-1 col-md-6 @error('licence') is-invalid @enderror">
                            <label class="form-label" for="licence">{{ __('users.licence') }}</label>
                            <input type="file" class="form-control input" name="licence" id="licence">
                            @error('licence')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                 @if(isset($item) && !empty($item->licence))
                                    @if(pathinfo($item->licence, PATHINFO_EXTENSION)=='pdf')
                                        <a href="{{ $item->licence }}" download>
                                        <img src="{{ asset('default.jpg') }}" class="img-fluid img-thumbnail">
                                        </a>
                                        @else
                                        <a href="{{ $item->licence }}" download>
                                        <img src="{{ $item->licence }}" class="img-fluid img-thumbnail">
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-1 col-md-12  @error('description') is-invalid @enderror">
                            <label class="form-label" for="description">{{ __('products.description') }}</label>
                            <textarea name="description" id="description" class="form-control" placeholder="">{{ $item->description ?? old('description') }}</textarea>
                            @error('description')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                        <div class="form-group">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-gradient-green rounded">تسجيل</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </form>
</div>
</body>
</html>
