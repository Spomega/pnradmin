@extends('backend.layouts.app')

@section('title',app_name() . ' | ' . __('labels.backend.access.permissions.management'))

@section('content')
<div class="card">
   <div class="card-body">
       <div class="row">
           <div class="col-sm-5">
               <h4 class="class card-title mb-0">
                   @lang('labels.backend.access.permissions.management')
               </h4>
           </div>

           <div class="col-sm-7 pull-right">
               @include('backend.auth.permission.includes.header-buttons')
           </div><!--col-->
       </div><!--row-->
       <div class="row mt-4">
           <div class="col">
               <div class="table-responsive">
               <table class="table">
                   <thead>
                   <tr>
                       <th>@lang('labels.backend.access.permissions.table.permissions')</th>
                       <th>@lang('labels.backend.access.permissions.table.group')</th>
                       <th>@lang('labels.general.actions')</th>
                   </tr>
                   </thead>
                   <tbody>
                       @foreach($permissions as $permission)
                          <tr>
                              <td>{{ucwords($permission->name)}}</td>
                              <td>{{ucwords($permission->guard_name)}}</td>
                              <td> {!! $permission->action_buttons !!}</td>
                          </tr>
                       @endforeach
                   </tbody>
               </table>
               </div>
           </div><!--col-->
       </div>
   </div><!--card-body-->
</div>
@endsection
