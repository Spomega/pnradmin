@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.company.management'))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="class card-title mb-0">
                        @lang('labels.backend.access.company.management')
                    </h4>
                </div>

                <div class="col-sm-7 pull-right">
                    @include('backend.auth.company.includes.header-buttons')
                </div><!--col-->
            </div><!--row-->
            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>@lang('labels.backend.access.company.table.company')</th>
                                <th>@lang('labels.backend.access.company.table.contact')</th>
                                <th>@lang('labels.backend.access.company.table.email')</th>
                                <th>@lang('labels.backend.access.company.table.iata')</th>
                                <th>@lang('labels.general.actions')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td>{{ucwords($company->name)}}</td>
                                    <td>{{ucwords($company->contact_person)}}</td>
                                    <td>{{ucwords($company->email)}}</td>
                                    <td>{{ucwords($company->company_code)}}</td>
                                    <td> {!! $company->action_buttons !!}</td>
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
