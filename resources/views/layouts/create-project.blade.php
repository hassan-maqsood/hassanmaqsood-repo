@extends('layouts.dashboard')
@section('page_heading','Blank')
@section('section')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create User</div>
                    <div class="panel-body">
                        @if (count($errors) > 0 || (Session::has('login_fail')))
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    <div class="form-group">
                                        <span class="error-message">{{ Session::get('login_fail') }}</span>
                                        <?php Session::pull('login_fail');?>
                                    </div>
                                </ul>
                            </div>
                        @endif

                        {{Form::open(array('url' => 'auth/login','autocomplete'=>'off', 'class' => 'registration'))}}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                            <div class="form-group">
                            <label>Text Input</label>
                            <input class="form-control">
                            <p class="help-block">Example block-level help text here.</p>
                        </div>
                        <div class="form-group">
                            <label>Text Input with Placeholder</label>
                            <input class="form-control" placeholder="Enter text">
                        </div>
                        <div class="form-group">
                            <label>Static Control</label>
                            <p class="form-control-static">email@example.com</p>
                        </div>
                        <div class="form-group">
                            <label>File input</label>
                            <input type="file">
                        </div>
                        <div class="form-group">
                            <label>Text area</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 15px;">
                                    Save
                                </button>

                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
           
            
@stop
