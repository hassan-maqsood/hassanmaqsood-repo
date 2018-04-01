@extends('layouts.dashboard')
@section('page_heading','List Users')
@section('section')
    <div class="col-sm-12">
        @if(Session::has('global-error'))
            <div class="alert alert-error">
                {{ Session::get('global-error') }}
            </div>
            <?php Session::forget('global-error'); ?>
        @endif
        @if(Session::has('global'))
            <div class="alert alert-success">
                {{ Session::get('global') }}
            </div>
            <?php Session::forget('global'); ?>
        @endif
        <div class="row">
            <div class="col-sm-12">
                @section ('cotable_panel_title','Users Status')
                @section ('cotable_panel_body')
                    @if(count($users) > 0)
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>School Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $row)
                            <tr class="warning">
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->address }}</td>
                                <td>{{ $row->school_name }}</td>
                                <td>{{ $row->status }}</td>
                                <td class="center">
                                    @if(@$row->status == 'rejected')
                                        <a class="fa fa-times fa-fw" title="approve" href="{{URL::route('approve-request',$row->id) }}"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    @elseif(@$row->status == 'approved')
                                        <a class="fa fa-check fa-fw" title="reject" href="{{URL::route('reject-request',$row->id) }}"></a>
                                    @elseif(@$row->status == 'pending')
                                        <a class="fa fa-check fa-fw" title="approve" href="{{URL::route('approve-request',$row->id) }}"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <span class="divider">{{' '}}</span>
                                        <a class="fa fa-times fa-fw" title="reject" href="{{URL::route('reject-request',$row->id) }}"></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        No users Registered Yet
                    @endif
                @endsection
                @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
            </div>
        </div>
    </div>
@stop
