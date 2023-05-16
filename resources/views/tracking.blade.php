@extends('layouts.app')

@section('content')
<div id="layoutDrawer_content">
    <!-- Main page content-->
    <main>
        <div class="container-xl p-5">
            <div class="row gx-5">
                <div class="col-xl-4 col-md-6 mb-5">
                    <div class="card card-raised" style="height: 500px;">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="fw-500">Devices</div>
                                <div class="dropdown my-n2 me-n2">
                                    <button class="btn btn-lg btn-text-light btn-icon dropdown-toggle" id="segmentsDropdownButton" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></button>
                                    <ul class="dropdown-menu" aria-labelledby="segmentsDropdownButton">
                                        <li><a class="dropdown-item" href="#!">Action</a></li>
                                        <li><a class="dropdown-item" href="#!">Another action</a></li>
                                        <li><a class="dropdown-item" href="#!">Something else here</a></li>
                                        <li><hr class="dropdown-divider" /></li>
                                        <li><a class="dropdown-item" href="#!">Separated link</a></li>
                                        <li><a class="dropdown-item" href="#!">Separated link</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="devices-card-body">
                            <div class="card card-raised overflow-hidden">
                                <div class="card-body p-0">
                                    <!-- Payment history table-->
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Last Update</th>
                                                </tr>
                                            </thead>
                                            <tbody id="devices-container">
                                                @foreach($devices as $device)
                                                    <?php
                                                        $background_color = "bg-light";
                                                        $device_latest = $device->latest_track->first();
                                                        switch($device_latest->status){
                                                            case 0:
                                                                $background_color = "bg-danger";
                                                                break;
                                                            case 1:
                                                                $background_color = "bg-secondary";
                                                                break;
                                                            case 2:
                                                                $background_color = "bg-warning";
                                                                break;
                                                            case 3:
                                                                $background_color = "bg-info";
                                                                break;
                                                        }
                                                    ?>
                                                    <tr class="{{ $background_color }}">
                                                        <td><mwc-checkbox class="devices_checker"></mwc-checkbox></td>
                                                        <td>{{ $device->device_name }}</td>
                                                        <td>{{ $device_status[$device_latest->status] }}</td>
                                                        <td>{{ $device_latest->dataFormatAttribute() }} Minutes</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-raised overflow-hidden" style="margin-top:20px;">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="fw-500">Date Range</div>
                            </div>
                        </div>
                        <div class="devices-card-body">
                            <div class="card card-raised overflow-hidden">
                                <div class="card-body p-3">
                                    <input type="text" name="daterange" value="05/01/2023 - 05/15/2023" style="padding: 10px; width: 100%;"/>
                                    <button id="save_btn" class="btn btn-primary" style="margin-top:20px; float: right;" type="button">Export to CSV</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-6 mb-5">
                    <div class="card card-raised bg-primary bg-gradient text-white h-100">
                        <div class="px-1"><canvas id="dashboardAreaChartLight"></canvas></div>
                    </div>
                </div>
            </div>
            <mwc-slider class="w-100" value="25" min="0" max="50" step="5" pin markers></mwc-slider>
        </div>
    </main>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        var dateRangePicker = $('input[name="daterange"]');
        // const devices = <?php echo json_encode($devices); ?>;
        // console.log(devices);

        // let latest_list = [];
        // @foreach($devices as $device)
        //     latest_list.push('{{ $device->latest_track->first()->id }}')
        // @endforeach
        // console.log(latest_list)

        if(dateRangePicker.length !== 0) {
            dateRangePicker.daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start + ' to ' + end);
                // $.ajax({
                //     type: 'POST',
                //     url: '/tracking/date_changed',
                //     data: {
                //         'start': start.format('YYYY-MM-DD'),
                //         'end': end.format('YYYY-MM-DD')
                //     },
                //     success: function(res) {
                //         console.log(res);
                //         // $('#devices-container').html('');
                //         // background_color = "bg-light";
                //         // res.map((device, index) => {
                //         //     let device_temp = '';
                //         //     device_temp += '<tr class=' + background_color + '>' + 
                //         //                         '<td><mwc-checkbox class="devices_checker"></mwc-checkbox></td>' + 
                //         //                         '<td>' + device->device_name '</td>' + 
                //         //                         '<td>{{ $device_status[$device_latest->status] }}</td>' + 
                //         //                         '<td>{{ $device_latest->dataFormatAttribute() }} Minutes</td>' + 
                //         //                     '</tr>';
                //         // })
                //         dateRangePicker.data('daterangepicker').hide();
                //     },
                //     error: function() {
                //         dateRangePicker.data('daterangepicker').hide();
                //     }
                // })
                $('#devices-container').html('');
                // background_color = "bg-light";
                // res.map((device, index) => {
                //     let device_temp = '';
                //     device_temp += '<tr class=' + background_color + '>' + 
                //                         '<td><mwc-checkbox class="devices_checker"></mwc-checkbox></td>' + 
                //                         '<td>' + device->device_name '</td>' + 
                //                         '<td>{{ $device_status[$device_latest->status] }}</td>' + 
                //                         '<td>{{ $device_latest->dataFormatAttribute() }} Minutes</td>' + 
                //                     '</tr>';
                // })
                const deviceArray = <?php echo json_encode($devices[0]->latest_track); ?>;
                const filteredArray = deviceArray.filter(function(el) {
                    const timestamp = new Date(el.timestamp).getTime();
                    return timestamp >= start && timestamp <=end;
                });
                console.log(deviceArray);
                console.log(filteredArray);
            });
        }

        getBackgroundColor = function(device_latest) {
            background_color = "bg-light";
            switch(device_latest) {
                case 0:
                    background_color = "bg-danger";
                    break;
                case 1:
                    background_color = "bg-secondary";
                    break;
                case 2:
                    background_color = "bg-warning";
                    break;
                case 3:
                    background_color = "bg-info";
                    break;
            }
            return background_color;
        }
    })
</script>
@endpush