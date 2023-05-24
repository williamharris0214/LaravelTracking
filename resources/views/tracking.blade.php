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
                                                    <td style="width:10%;"><mwc-checkbox class="devices_checker" data-deviceid="{{ $device->id }}"></mwc-checkbox></td>
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
                                <!-- <input type="text" name="daterange" style="padding: 10px; width: 100%;"/> -->
                                <input type="text" id="datepicker1" placeholder="Select start date" style="padding: 10px; width: 100%;">
                                <input type="text" id="datepicker2" placeholder="Select end date" style="padding: 10px; width: 100%; margin-top:10px;">
                                <div style="display:flex; margin-top:10px; justify-content: space-between">
                                    <button id="export_btn" class="btn btn-primary" type="button">Export to CSV</button>
                                    <button id="apply_btn" class="btn btn-primary" style="margin-left:20px;" type="button">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="trackingmap" class="col-xl-8 col-md-6 mb-5">
            </div>
        </div>
        <div>
            <div class="row">
                <div class="col-xl-2 col-md-4">
                    <select class="form-select" aria-label="Default select example" id="device_first" onChange="onSelectFirst()">
                        <option value="0" disabled selected>Select Device</option>
                    </select>
                </div>
                <mwc-slider discrete class="col-xl-10 col-md-8" id="slider_first" min="0" max="1" value="1" step="1" class="w-100" oninput="changeFirstSlider()" pin markers></mwc-slider>
            </div>
            <div class="row">
                <div class="col-xl-2 col-md-4">
                    <select class="form-select" aria-label="Default select example" id="device_second" onChange="onSelectSecond()">
                        <option value="0" disabled selected>Select Device</option>
                    </select>
                </div>
                <mwc-slider discrete class="col-xl-10 col-md-8" id="slider_second" min="0" max="1" value="1" step="1" class="w-100" oninput="changeSecondSlider()" pin markers></mwc-slider>
            </div>
            <!-- <div style="display:flex; flex-direction:column; align-items:self-end;">
                <p id="current_first_date" style="margin-right:50px;">Device 1</p>
                <p id="current_second_date" style="margin-right:50px;">Device 2</p>
            </div> -->
        </div>
        <div style="margin-top:30px; display:flex; width:100%">
            <div class="card card-raised" style="height: 500px; flex-grow:1;">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div id="first_device_history" class="fw-500">History</div>
                        <div style="float:right;">Points to show: <input oninput="check(this)" type="number" value="1" min="1" name="first_scroll_range" id="first_scroll_range" onchange="onScrollRangeFirst()"/></div>
                    </div>
                </div>
                <div class="devices-card-body">
                    <div class="overflow-hidden">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th style="background-color:lavender; padding:1rem;" scope="col">Date</th>
                                            <th style="background-color:lavender; padding:1rem;" scope="col">Time</th>
                                            <th style="background-color:lavender; padding:1rem;" scope="col">Position</th>
                                        </tr>
                                    </thead>
                                    <tbody id="first_device_history_table">
                                        <tr>
                                            <td style="padding:1rem;">05/10/2023</td>
                                            <td style="padding:1rem;">10:30:21</td>
                                            <td style="padding:1rem;">(10.342, 11.234)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-raised" style="height: 500px; flex-grow:1; margin-left:30px">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div id="second_device_history" class="fw-500">History</div>
                        <div style="float:right;">Points to show: <input type="number" value="1" min="1" name="second_scroll_range" id="second_scroll_range" onchange="onScrollRangeSecond()"/></div>
                    </div>
                </div>
                <div class="devices-card-body">
                    <div class="overflow-hidden">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th style="background-color:lavender; padding:1rem;" scope="col">Date</th>
                                            <th style="background-color:lavender; padding:1rem;" scope="col">Time</th>
                                            <th style="background-color:lavender; padding:1rem;" scope="col">Position</th>
                                        </tr>
                                    </thead>
                                    <tbody id="second_device_history_table">
                                        <tr>
                                            <td style="padding:1rem;">05/10/2023</td>
                                            <td style="padding:1rem;">10:30:21</td>
                                            <td style="padding:1rem;">(10.342, 11.234)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    
    function setFilteredArray() {
        filteredArray_all = [];
        device_array = [];
        @foreach($devices as $device)
            filteredArray = <?php echo json_encode($device->tracks); ?>;
            filteredArray_all.push(filteredArray);
        @endforeach
        device_array = [];
        updateSelect();
    }

    $(document).ready(function() {
         setFilteredArray();
        // var loc_data = getLocationData(filteredArray_all);
        // remove_all_markers();
        // refresh_marker(loc_data);
        
        const date_now = Math.floor(Date.now() / 1000);
        const date_formated = moment.unix(date_now).format('MM/DD/YYYY');
       
        start_date = end_date = moment(Date.now());
        cur_first_sliderPos = cur_second_sliderPos = end_date;
        updateCheckBox(filteredArray_all, true);
    })
    $('#apply_btn').on('click', function(){
        cur_first_sliderPos = cur_second_sliderPos = end_date;

        $('#devices-container').html('');
        filteredArray_all = [];
        let deviceArray = [];
        let filteredArray = [];
        let device_temp;
        let latest_track;
        device_array = [];
        @foreach($devices as $device)
            deviceArray = <?php echo json_encode($device->tracks); ?>;
            filteredArray = deviceArray.filter(function(el) {
                const timestamp = new Date(el.timestamp).getTime();
                return timestamp >= Date.parse(start_date)/1000 && timestamp <=Date.parse(end_date)/1000;
            });
            filteredArray_all.push(filteredArray);
            device_temp = '';
            if(filteredArray.length) {
                latest_track = filteredArray[filteredArray.length - 1];
                device_temp += '<tr class=' + getBackgroundColor(latest_track.status) + '>' +
                                    '<td><mwc-checkbox checked class="devices_checker" data-deviceid="' + latest_track.device_id + '"></mwc-checkbox></td>' + 
                                    '<td>' + latest_track.device_name + '</td>' + 
                                    '<td>' + getStatusName(latest_track.status) + '</td>' + 
                                    '<td>' + getDiffMins(latest_track.timestamp) + ' Minutes' + '</td>' + 
                                '</tr>';
                $('#devices-container').append(device_temp);
            }
            filteredArray.length ? device_array.push(latest_track.device_name) : device_array.push('');
        @endforeach
        checked_array = filteredArray_all;
        var loc_data = getLocationData(filteredArray_all);
        remove_all_markers();
        refresh_marker(loc_data);
        //setSliderAttr('#slider_first', 0, Math.floor((end_date-start_date)/1000), 1, Math.floor((end_date-start_date)/1000));
        //setSliderAttr('#slider_second', 0, Math.floor((end_date-start_date)/1000), 1, Math.floor((end_date-start_date)/1000));
        updateCheckBox(filteredArray_all, false);
        updateSelect();
        cur_first_device = cur_second_device = null;
        selectedArray_all = [];
    });
</script>

<script src="{{ asset('page/js/tracking.js') }}"></script>
<script src="{{ asset('page/js/tracking_map.js') }}"></script>
<script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyAf003EuRP2rjWPSSLCIcVbyKPNTF8iVc4", v: "beta"});</script>

@endpush