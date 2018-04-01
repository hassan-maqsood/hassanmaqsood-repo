@extends('layouts.dashboard')
@section('page_heading','Edit User')

@section('section')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit User</div>
                    <div class="panel-body">
                        @if (count($errors) > 0 || (Session::has('global-error')))
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    <div class="form-group">
                                        <span class="error-message">{{ Session::get('global-error') }}</span>
                                        <?php Session::pull('global-error');?>
                                    </div>
                                </ul>
                            </div>
                        @endif
                        @if(Session::has('global'))
                            <div class="alert alert-success">
                                {{ Session::get('global') }}
                            </div>
                            <?php Session::forget('global'); ?>
                        @endif

                        {{Form::open(array('url' => 'edit-new-user','autocomplete'=>'off', 'class' => 'registration'))}}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">School Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="school_name" value="{{ $user->school_name }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"> Address</label>
                            <div class="col-md-6">
                                <textarea class="form-control" rows="2" cols="4" name="address">{{$user->address}}</textarea>
                            </div>
                        </div>

                        <input type="hidden" class="form-control" name="user_id" value="{{ $user->id }}">

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save Changes
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