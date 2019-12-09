@extends('backend.layouts.app')

@section('title',app_name() . ' | ' . __('labels.backend.access.bookings.management'))


@section('content')
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
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Confirmation Number</th>
                                <th>Route</th>
                                <th>Passenger Name</th>
                                <th>Amount</th>
                                <th>Phone Number</th>
                                <th>Date Paid</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ucwords($transaction->confirmation_number)}}</td>
                                    <td>{{ucwords($transaction->route)}}</td>
                                    <td>{{ucwords($transaction->passenger_name)}}</td>
                                    <td>{{ucwords($transaction->total_cost)}}</td>
                                    <td>{{ucwords($transaction->phone_number)}}</td>
                                    <td>{{ucwords($transaction->date_paid)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!--card-body-->

    </div><!--card-->
@endsection
