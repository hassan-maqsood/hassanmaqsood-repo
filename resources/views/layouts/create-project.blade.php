@extends('layouts.dashboard')
@section('page_heading','Create Project')
@section('section')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Project</div>
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

                        {{Form::open(array('url' => 'create-new-project','autocomplete'=>'off', 'files'=>true, 'class' => 'registration'))}}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Project Title</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value=""><br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Project Supervisor</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="supervisor" value=""><br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Project Lead Member</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="lead_member" value=""><br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Project Duration (Months)</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="duration" value=""><br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"> Project Description</label>
                            <div class="col-md-6">
                                <textarea class="form-control" rows="2" cols="4" name="description"></textarea><br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Project Document(.pdf)</label>
                            <div class="col-md-6">
                                {{Form::file('pdf_link',array('class'=>'form-control','id' => 'pdf_link'))}}
                            </div>
                            <br/>
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
