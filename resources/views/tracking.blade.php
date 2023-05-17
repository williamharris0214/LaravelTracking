@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="margin-left:0.5rem; margin-top:1rem; padding-right:1rem">
            <div class="col-xl-4 col-md-6 mb-5">
                <div class="card card-raised" style="height: 500px;">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-500">Devices</div>
                        </div>
                    </div>
                    <div class="devices-card-body">
                        <div class="overflow-hidden">
                            <div class="card-body p-0">
                                <!-- Payment history table-->
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th style="background-color:lavender; padding:1rem;" scope="col"></th>
                                                <th style="background-color:lavender; padding:1rem;" scope="col">Name</th>
                                                <th style="background-color:lavender; padding:1rem;" scope="col">Status</th>
                                                <th style="background-color:lavender; padding:1rem;" scope="col">Last Update</th>
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
                                                    <td style="width:10%;"><mwc-checkbox class="devices_checker"></mwc-checkbox></td>
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
                                <input type="text" name="daterange" style="padding: 10px; width: 100%;"/>
                                <button id="export_btn" class="btn btn-primary" style="margin-top:20px; float: right;" type="button">Export to CSV</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="trackingmap" class="col-xl-8 col-md-6 mb-5">
                <div class="card card-raised bg-primary bg-gradient text-white h-100">
                </div>
            </div>
        </div>
        <mwc-slider id="slider" min="0" max="1" value="0" step="1" class="w-100" onchange="changeSlider()" pin markers></mwc-slider>
        <div id="selected_date"></div>
    </div>
@endsection

@push('script')
<script>
    let start_date, end_date;

    function updateCheckBox() {
        $("mwc-checkbox").on('change', (a, b, c, d) => {
            console.log('checkbox changed to ${checkbox.checked}');
        });
    }
    

    $(document).ready(function() {
        const date_now = Math.floor(Date.now() / 1000);
        const date_formated = moment.unix(date_now).format('MM/DD/YYYY');
        $('#selected_date').html(date_formated);
        var dateRangePicker = $('input[name="daterange"]');
        let deviceArray_all = [];
        let filteredArray_all = [];
        start_date = end_date = moment(Date.now());
        updateCheckBox();

        if(dateRangePicker.length !== 0) {
            dateRangePicker.daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                
                start_date = moment(start);
                end_date = moment(end);

                $('#devices-container').html('');
                deviceArray_all = [];
                filteredArray_all = [];
                let deviceArray = [];
                let filteredArray = [];
                let device_temp;
                let latest_track;
                @foreach($devices as $device)
                    deviceArray = <?php echo json_encode($device->tracks); ?>;
                    deviceArray_all.push(deviceArray);
                    filteredArray = deviceArray.filter(function(el) {
                        const timestamp = new Date(el.timestamp).getTime();
                        return timestamp >= Date.parse(start)/1000 && timestamp <=Date.parse(end)/1000;
                    });
                    filteredArray_all.push(filteredArray);
                    device_temp = '';
                    if(filteredArray.length) {
                        latest_track = filteredArray[filteredArray.length - 1];
                        device_temp += '<tr class=' + getBackgroundColor(latest_track.status) + '>' +
                                            '<td><mwc-checkbox class="devices_checker"></mwc-checkbox></td></td>' + 
                                            '<td>' + latest_track.device_name + '</td>' + 
                                            '<td>' + getStatusName(latest_track.status) + '</td>' + 
                                            '<td>' + getDiffMins(latest_track.timestamp) + ' Minutes' + '</td>' + 
                                        '</tr>';
                        $('#devices-container').append(device_temp);
                    }
                @endforeach
                console.log(filteredArray_all);
                console.log((end-start)/100/3600/24);
                setSliderAttr(0, Math.floor((end-start)/1000/3600/24), 1, Math.floor((end-start)/1000/3600/24));
                changeSlider();
                updateCheckBox();
            });
        }

        getBackgroundColor = function(status) {
            background_color = "bg-light";
            switch(status) {
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

        getStatusName = function(status) {
            status_name = "Online";
            switch(status) {
                case 0:
                    status_name = "Offline";
                    break;
                case 1:
                    status_name = "Unknown";
                    break;
                case 2:
                    status_name = "Warning";
                    break;
                case 3:
                    status_name = "Online";
                    break;
            }
            return status_name;
        }

        getDiffMins = function(timestamp) {
            const now = Math.floor(Date.now() / 1000);
            const diffInMinutes = Math.floor((now - timestamp) / 60);
            return diffInMinutes;
        }

        $('#export_btn').on('click', function(){
            const now_ts = Date.now();
            const now = Math.floor(now_ts / 1000);
            const filename = moment.unix(now).format('MM/DD/YYYY hh:mm:ss') + '-' + now_ts + '.csv';
            exportToCSV(filename);
        });

        exportToCSV = function(fileName) {
            if(!filteredArray_all.length)
                return;
            const csvData = [];
            const headers = Object.keys(filteredArray_all[0][0]).join(",");
            csvData.push(headers);
            for(let i = 0; i < filteredArray_all.length; i++) {
                for(let j = 0 ; j < filteredArray_all[i].length; j++) {
                    const values = Object.values(filteredArray_all[i][j]).join(",");
                    csvData.push(values);
                }
            }
            const csvContent = csvData.join("\n");
            const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;"});
            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.setAttribute("href", url);
            link.setAttribute("download", fileName);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        setSliderAttr = function(min, max, step, value) {
            console.log('set slider attr', min, max, step, value);
            $('#slider').prop('min', min);
            $('#slider').prop('max', max);
            $('#slider').prop('step', step);
            $('#slider').prop('value', max);
            setTimeout(() => $('#slider').prop('value', max), 0);
        }
    })

    
    function getCurrentData() {

    }

    function changeSlider() {
        let date = new moment(start_date);
        date.add($("#slider").prop('value'), 'd');
        console.log(date.format('MM/DD/YYYY'))
        $("#selected_date").text(date.format("MM/DD/YYYY"));
        updateMap();
    }

    function updateMap() {
        console.log('updateMap');
    }
</script>

<script src="{{ asset('page/js/tracking_map.js') }}"></script>

<script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyAf003EuRP2rjWPSSLCIcVbyKPNTF8iVc4", v: "beta"});</script>

@endpush