@extends('backend.layouts.app')

@section('title',app_name() . ' | ' . __('labels.backend.access.bookings.management'))


@section('content')
    {{ html()->form('POST', route('admin.auth.booking.pay'))->class('form-horizontal')->open() }}
    <div class="row">
        <div class="table-responsive panel border-danger">
            <table class="table">
                <tbody>

                <tr>
                    <td class="text-info"><i class="fa fa-sign-in-alt"></i> <span>PNR</span></td>
                    <td>{{$details->pnr}}</td>
                </tr>
                <tr>
                    <td class="text-info"><i class="fa fa-plane"></i> <span>Route</span></td>
                    <td>{{$details->route}}</td>
                </tr>
                <tr>
                    <td class="text-info"><i class="fa fa-book"></i> <span>Type</span></td>
                    <td>{{$details->type}}</td>
                </tr>
                <tr>
                    <td class="text-info"><i class="fa fa-user"></i> <span>Passenger Name</span></td>
                    <td> {{$details->name}}</td>
                </tr>
                <tr>
                    <td class="text-info"><i class="fa fa-money-bill"></i> <span>Amount</span></td>
                    <td>{{$details->charges}}</td>
                </tr>
                <tr>
                    <td class="text-info"><i class="fa fa-plane-departure"></i> <span>Departure</span></td>
                    <td>{{$details->departure}}</td>
                </tr>
                <tr>
                    <td class="text-info"><i class="fa fa-plane-arrival"></i> <span>Arrival</span></td>
                    <td>{{$details->arrival}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col">
            {{ form_cancel(route('admin.auth.booking.detail'), __('buttons.general.cancel')) }}
        </div><!--col-->
        <div class="col text-center">
            {{ form_submit(__('buttons.general.pay')) }}
        </div><!--col-->
    </div>
    {{ html()->form()->close() }}
@endsection
