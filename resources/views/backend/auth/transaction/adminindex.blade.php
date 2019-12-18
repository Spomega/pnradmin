@extends('backend.layouts.app')

@section('title',app_name() . ' | ' . __('labels.backend.access.bookings.management'))

@push('after-styles')
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endpush

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
            <div  class="col-lg-12 border-bottom" style="display: block;margin-top: 10px;" >

                {{ html()->form('POST', route('admin.auth.transaction.filter'))->class('form-horizontal')->open() }}


                <label for="company">Company</label>
                {{ html()->select('company')->options($companies)
                        ->class('col-md-4','form-control')}}
                <label for="month">Search Date Range</label>&nbsp;
                <i class="glyphicon glyphicon-calendar fa fa-calendar" id="dateglyph"></i>&nbsp;&nbsp;
                <input type="text" id="datevalue" name="daterange" value=""  style="width: 300px"/>
                <button type="submit" class="btn btn-sm btn-success">Go</button>

                {{ html()->form()->close() }}
            </div>
            <hr>
            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table id="transaction" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>Confirmation Number</th>
                                <th>Passenger Name</th>
                                <th>Amount</th>
                                <th>Phone Number</th>
                                <th>Date Paid</th>
                                <th>User</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ucwords($transaction->confirmation_number)}}</td>
                                    <td>{{ucwords($transaction->passenger_name)}}</td>
                                    <td>{{ucwords($transaction->total_cost)}}</td>
                                    <td>{{ucwords($transaction->phone_number)}}</td>
                                    <td>{{ucwords($transaction->date_paid)}}</td>
                                    <td>{{ucwords($transaction->user->first_name)}} {{ucwords($transaction->user->last_name)}}</td>
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

@push('after-scripts')

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(function() {

            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY')).trigger('change');

                $('input[name="daterange"]').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY')).trigger('change');
            }


            $('input[name="daterange"]').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

        });
        $(document).ready(function() {
            $('#transaction').DataTable(
                {
                    dom: 'Bfrtip',
                    buttons: [
                        'csv', 'excel', 'pdf', 'print'
                    ]
                }
            );
        } );
    </script>
@endpush

