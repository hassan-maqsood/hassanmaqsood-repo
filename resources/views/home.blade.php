@extends('layouts.dashboard')
@section('page_heading','Dashboard')
@section('section')
           
            <!-- /.row -->
            <div class="col-sm-12">
            <div class="row">
                @if(Session::has('global'))
                    <div class="alert alert-success">
                        {{ Session::get('global') }}
                    </div>
                    <?php Session::forget('global'); ?>
                @endif
                @if(Session::has('global-error'))
                    <div class="alert alert-error">
                        {{ Session::get('global-error') }}
                    </div>
                    <?php Session::forget('global-error'); ?>
                @endif
                @if(Auth::user()->role_id == 1)
                    <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{$users}}</div>
                                    <div>Users</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ url ('list-users') }}">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                    <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{$projects}}</div>
                                    <div>Projects!</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ url ('list-projects') }}">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">

                @section ('pane2_panel_body')

                    Welcome to Project Management Portal
                @endsection
                @include('widgets.panel', array('header'=>true, 'as'=>'pane2'))
                </div>
            </div>
            </div>

@stop
