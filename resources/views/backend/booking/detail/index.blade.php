@extends('backend.layouts.app')

@section('title',app_name() . ' | ' . __('labels.backend.access.bookings.management'))

@section('content')

    {{ html()->form('POST', route('admin.auth.booking.view'))->class('form-horizontal')->open() }}

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.backend.access.bookings.management')
                        <small class="text-muted">@lang('labels.backend.access.bookings.detail')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{html()->label(__('validation.attributes.backend.access.bookings.detail'))
                        ->class('col-md-2 form-control-label')
                        ->for('detail')}}

                        <div class="col-md-8">
                            {{ html()->text('detail')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.bookings.detail'))
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
                    {{ form_cancel(route('admin.auth.booking.detail'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.detail')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->


    </div><!--card-->

    {{ html()->form()->close() }}

@endsection
