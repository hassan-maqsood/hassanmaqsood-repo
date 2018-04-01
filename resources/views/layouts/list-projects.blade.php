@extends('layouts.dashboard')
@section('page_heading','List Projects')
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
                @section ('cotable_panel_title','User Projects')
                @section ('cotable_panel_body')
                    @if(count($projects) > 0)
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                @if(Auth::user()->role_id == 1)
                                <th>Owner Name</th>
                                <th>Owner Email</th>
                                @endif
                                <th>Title</th>
                                <th>Supervisor</th>
                                <th>Lead Member</th>
                                <th>Duration(Months)</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Document Download Link</th>
                                <th>Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($projects as $row)
                                <tr class="warning">
                                    @if(Auth::user()->role_id == 1)
                                    <td>{{ $row->user_name }}</td>
                                    <td>{{ $row->email }}</td>
                                    @endif
                                    @if(Auth::user()->role_id == 1)
                                        <td>{{ $row->project_name }}</td>
                                    @endif
                                    @if(Auth::user()->role_id == 2)
                                        <td>{{ $row->name }}</td>
                                    @endif
                                    <td>{{ $row->supervisor }}</td>
                                    <td>{{ $row->lead_member }}</td>
                                    <td>{{ $row->duration }}</td>
                                    <td>{{ $row->description }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td><a class="btn btn-info btn-rounded " title="download pdf" href="{{URL::route('download-document', $row->pdf_link)}}">Download Link</a></td>
                                    <td>
                                        <a class="fa fa-edit fa-fw" title="Edit" id="edit_project" href = "{{URL::route('edit-project', $row->id) }}"></a>&nbsp;
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        No Projects Registered Yet
                    @endif
                @endsection
                @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
            </div>
        </div>
    </div>
@stop
