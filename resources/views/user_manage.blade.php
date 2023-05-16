@extends('layouts.app')

@section('content')
    <div id="layoutAuthentication">
        <!-- Layout content-->
        <div id="layoutAuthentication_content">
            <!-- Main page content-->
            <main>
                <!-- Main content container-->
                <div class="container">
                <div class="tab-content mb-5">
                    <div class="tab-pane show active" role="tabpanel" id="tableStripedDemo" aria-labelledby="tableStripedDemoTab">
                        <div class="p-3 p-sm-5">
                            <h2 style="margin-bottom: 1rem">Assgin Devices</h2>
                            <table class="table table-bordered table-hover table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Devices</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <th width="10%">{{ $user->id }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td width="20%">
                                            <button data-userId="{{ $user->id }}" class="btn btn-primary actions-button">Assign to Device</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <button id="modal_btn" class="btn btn-primary d-none" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">Launch scrollable modal<i class="trailing-icon material-icons">launch</i></button>

    <div class="modal fade" id="exampleModalScrollable" tabindex="-1" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableLabel">Get this party started?</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Add</th>
                            </tr>
                        </thead>
                        <tbody id="devices_container">
                            @foreach($devices as $device)
                            <tr>
                                <th width="10%">{{ $device->id }}</th>
                                <td>{{ $device->name }}</td>
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

@endsection

@push('script')
<script>
    $(document).ready(function() {
        const users = <?php echo json_encode($users); ?>;
        const devices = <?php echo json_encode($devices); ?>;
        console.log(users);

        $('.actions-button').on('click', function() {
            const user_id = $(this).attr('data-userId');
            $("#modal_btn").click();

            let current_user = null;
            users.map((user, index) => {
                if(user_id == user.id)
                    current_user = user;
            });

            let current_devices = current_user.devices;
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
                                        '<th width="10%">' + device.id + '</th>' + 
                                        '<td>' + device.name + '</td>' +
                                        '<td width="10%">' +
                                            '<mwc-checkbox checked class="devices_checker" data-deviceId="' + device.id + '"></mwc-checkbox>' +
                                        '</td>' +
                                    '</tr>';
                else
                    devices_temp += '<tr>' + 
                                        '<th width="10%">' + device.id + '</th>' + 
                                        '<td>' + device.name + '</td>' +
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

                console.log(current_devices);
            });

            $('#save_btn').on('click', function() {
                $.ajax({
                    type: 'POST',
                    url: '/user_manage/add_device',
                    data: {
                        '_token': $('input[name="_token"]').val(),
                        'user_id': user_id,
                        'devices': current_devices
                    },
                    success: function(res) {
                    },
                    error: function() {
                    }
                })
            });
        });
    });
</script>
@endpush
