@extends('backend.layouts.app')

@section('Title', __('labels.backend.access.company.management') .' | '.__('labels.backend.access.company.create'))


@section('content')

    {{ html()->form('POST', route('admin.auth.company.store'))->class('form-horizontal')->open() }}

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.backend.access.company.management')
                        <small class="text-muted">@lang('labels.backend.access.company.create')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{html()->label(__('validation.attributes.backend.access.company.name'))
                        ->class('col-md-2 form-control-label')
                        ->for('name')}}

                        <div class="col-md-6">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.company.name'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{html()->label(__('validation.attributes.backend.access.company.contact'))
                        ->class('col-md-2 form-control-label')
                        ->for('contact')}}

                        <div class="col-md-6">
                            {{ html()->text('contact')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.company.contact'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{html()->label(__('validation.attributes.backend.access.company.email'))
                        ->class('col-md-2 form-control-label')
                        ->for('email')}}

                        <div class="col-md-6">
                            {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.company.email'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{html()->label(__('validation.attributes.backend.access.company.iata'))
                        ->class('col-md-2 form-control-label')
                        ->for('iata')}}

                        <div class="col-md-6">
                            {{ html()->text('iata')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.company.iata'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div>
                </div>
            </div>
        </div><!--card-body-->
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.auth.permission.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->


    </div><!--card-->

    {{ html()->form()->close() }}

@endsection
