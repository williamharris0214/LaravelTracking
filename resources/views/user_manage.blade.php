@extends('layouts.app')

@section('content')
    <!-- Main content container-->
    <div class="container">
        <div class="tab-content mb-5">
            <div class="tab-pane show active" role="tabpanel" id="tableStripedDemo" aria-labelledby="tableStripedDemoTab">
                <div class="p-3">
                    <h2 style="margin-bottom: 1rem">Assgin Devices</h2>
                    <table class="table table-bordered table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="background-color:lavender; padding:1rem;" scope="col">No</th>
                                <th style="background-color:lavender; padding:1rem;" scope="col">User Name</th>
                                <th style="background-color:lavender; padding:1rem;" scope="col">Devices</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <th width="10%">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td width="20%" style="padding:0.5rem;">
                                    <button data-userId="{{ $user->id }}" class="btn btn-primary actions-button">Assign to Device</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <button id="modal_btn" class="btn btn-primary d-none" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">Launch scrollable modal<i class="trailing-icon material-icons">launch</i></button>

        <div class="modal fade" id="exampleModalScrollable" tabindex="-1" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableLabel">Assign To Device</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding:0rem;">
                        <table class="table table-bordered table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="background-color:lavender; padding:1rem;" scope="col">No</th>
                                    <th style="background-color:lavender; padding:1rem;" scope="col">Name</th>
                                    <th style="background-color:lavender; padding:1rem;" scope="col">Add</th>
                                </tr>
                            </thead>
                            <tbody id="devices_container">
                                @foreach($devices as $device)
                                <tr>
                                    <td width="10%">{{ $device->id }}</td>
                                    <td>{{ $device->device_name }}</td>
                                    <td width="10%">
                                        <mwc-checkbox class="devices_checker" data-deviceId="{{ $device->id }}"></mwc-checkbox>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-text-primary me-2" type="button" data-bs-dismiss="modal">Close</button>
                        <button id="save_btn" class="btn btn-text-primary" type="button">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
<script>
    $(document).ready(function() {
        const users = <?php echo json_encode($users); ?>;
        const devices = <?php echo json_encode($devices); ?>;
        let current_user_devices = [];

        $('.actions-button').on('click', function() {
            const user_id = $(this).attr('data-userId');
            $("#modal_btn").click();

            let current_user = null;
            users.map((user, index) => {
                if(user_id == user.id)
                    current_user = user;
            });

            let current_devices = [];
            if(!current_user_devices.length)
                current_devices = current_user.devices;
            else
                current_devices = current_user_devices;
            current_devices = JSON.parse(current_devices);
            if(current_devices !== null)
                current_devices = current_devices.map(current_device => parseInt(current_device));
            else
                current_devices = [];
            
            $('#devices_container').html('');
            devices.map((device, index) => {
                let devices_temp = '';
                if(current_devices.includes(device.id))
                    devices_temp += '<tr>' + 
                                        '<td width="10%">' + device.id + '</td>' + 
                                        '<td>' + device.device_name + '</td>' +
                                        '<td width="10%">' +
                                            '<mwc-checkbox checked class="devices_checker" data-deviceId="' + device.id + '"></mwc-checkbox>' +
                                        '</td>' +
                                    '</tr>';
                else
                    devices_temp += '<tr>' + 
                                        '<td width="10%">' + device.id + '</td>' + 
                                        '<td>' + device.device_name + '</td>' +
                                        '<td width="10%">' +
                                            '<mwc-checkbox class="devices_checker" data-deviceId="' + device.id + '"></mwc-checkbox>' +
                                        '</td>' +
                                    '</tr>';

                $('#devices_container').append(devices_temp);
            })

            $('.devices_checker').on('click', function() {
                const device_id = $(this).attr('data-deviceId');
                if($(this).prop('checked')) {
                    current_devices = jQuery.grep(current_devices, function(value) {
                        return value != device_id;
                    });
                } else {
                    current_devices.push(device_id);
                }
            });

            $('#save_btn').on('click', function() {
                current_user_devices = current_devices;
                $.ajax({
                    type: 'POST',
                    url: '/user_manage/add_device',
                    data: {
                        '_token': $('input[name="_token"]').val(),
                        'user_id': user_id,
                        'devices': current_devices
                    },
                    success: function(res) {
                        $('#exampleModalScrollable').modal('hide');
                    },
                    error: function() {
                    }
                })
            });
        });
    });
</script>
@endpush
