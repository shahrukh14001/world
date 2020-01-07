@extends('layouts.app')

@section('page_title')
    Users
@stop

@section('content_header')
    <div class="row">
        <div class="col-md">
            <div class="form-group float-right">
                <button class="btn btn-primary" data-toggle="modal" data-target="#create_user_modal"><i class="fa fa-fw fa-plus"></i> Add user</button>
            </div>
        </div>
    </div>
@stop

@section('body_content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List of Users</h3>
                </div>

                <div class="card-body">
                    dataTable
                </div>
            </div>
        </div>
    </div>
@stop