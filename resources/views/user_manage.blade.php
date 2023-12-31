@extends('layouts.app')

@section('content')
    <!-- Main content container-->
    <div class="container">
        <div class="tab-content mb-5">
            <div class="tab-pane show active" role="tabpanel" id="tableStripedDemo" aria-labelledby="tableStripedDemoTab">
                <div class="p-3">
                    <h2 style="margin-bottom: 1rem">User Management</h2>
                    <table class="table table-bordered table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="background-color:lavender; padding:1rem;" scope="col">No</th>
                                <th style="background-color:lavender; padding:1rem;" scope="col">User Name</th>
                                <th style="background-color:lavender; padding:1rem;" scope="col">Devices</th>
                            </tr>
                        </thead>
                        <tbody id="user_container">
                            @foreach($users as $user)
                            <tr trdata="{{ $user->id }}" onclick="onUserClicked()">
                                <th width="10%">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td width="20%" style="padding:0.5rem;">
                                    <button data-userId="{{ $user->id }}" class="btn btn-primary actions-button">Assign to Device</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-primary add-button mt-3">Add a user</button>
                </div>
            </div>
        </div>

        <button id="modal_btn" class="btn btn-primary d-none" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">Launch scrollable modal<i class="trailing-icon material-icons">launch</i></button>
        <button id="modal_btn_new" class="btn btn-primary d-none" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable_new">Launch scrollable modal<i class="trailing-icon material-icons">launch</i></button>
        <button id="modal_btn_user" class="btn btn-primary d-none" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable_user">Launch scrollable modal<i class="trailing-icon material-icons">launch</i></button>

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

        <div class="modal fade" id="exampleModalScrollable_new" tabindex="-1" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableLabel">Add a user</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding:1rem;">
                        <form>
                            Name: <input type="text" required id="new_user" name="new_user" class="w-100">
                            Email: <input type="email" required id="new_email" name="new_email" class="w-100">
                            Password: <input type="password" required id="new_pwd" name="new_pwd" class="w-100">
                            <button type="button" id="add_btn" class="mt-2 btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="exampleModalScrollable_user" tabindex="-1" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableLabel">User Detail</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding:1rem;">
                        <form>
                            Name: <input type="text" required id="cur_user" name="new_user" class="w-100">
                            Email: <input type="email" required id="cur_email" name="new_email" class="w-100">
                            Password: <input type="password" required id="cur_pwd" name="new_pwd" class="w-100">
                            <div><input type="checkbox" id="cur_role" name="new_role" class="mt-2"> Administrator</div>
                            <button id="cur_btn" class="mt-2 btn btn-primary">OK</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
<script>
    let users = <?php echo json_encode($users); ?>;
    let devices = <?php echo json_encode($devices); ?>;
    let current_user_devices = [];
    let current_user = null;

    $(document).ready(function() {
        
        
        console.log(users);
        console.log(devices);

        $('.add-button').on('click', function() {
            $("#modal_btn_new").click();
            $("#add_btn").on('click', function() {
                let new_user_name = $("#new_user").val();
                let new_user_email = $("#new_email").val();
                let new_user_pwd = $("#new_pwd").val();
                $.ajax({
                    type: 'POST',
                    url: '/user_manage/add_user',
                    data: {
                        '_token': $('input[name="_token"]').val(),
                        'new_user': new_user_name,
                        'new_email': new_user_email,
                        'new_pwd': new_user_pwd
                    },
                    /*
                    <tr trdata="{{ $user->id }}" onclick="onUserClicked()">
                        <th width="10%">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td width="20%" style="padding:0.5rem;">
                            <button data-userId="{{ $user->id }}" class="btn btn-primary actions-button">Assign to Device</button>
                        </td>
                    </tr>
                    */
                    success: function(res) {
                        console.log(res);
                        let new_user_id = res['id'];
                        let new_user_devices = res['devices'];
                        new_user_name = res['name'];
                        new_user_email = res['email'];

                        // control view
                        var $user_table = $("#user_container");
                        var html = [
                            `<tr trdata="${new_user_id}" onclick="onUserClicked()">`,
                                `<th width="10%">${new_user_id}</th>`,
                                `<td>${new_user_name}</td>`,
                                '<td width="20%" style="padding:0.5rem;">',
                                    `<button data-userId="${new_user_id}" class="btn btn-primary actions-button">Assign to Device</button>`,
                                '</td>',
                            '</tr>'
                        ].join('\n');
                        
                        $user_table.append(html);
                        refresh_device_event();
                        //control logic
                        users.push({
                            'id': new_user_id,
                            'name': new_user_name,
                            'email': new_user_email,
                            'devices' : new_user_devices,
                        })
                        $('#exampleModalScrollable_new').modal('hide');
                    },
                    error: function(err) {
                        alert("Failed to create a new user");
                        $('#exampleModalScrollable_new').modal('hide');
                    }
                })
            });
        });
        refresh_device_event();
    });
    
    function refresh_device_event() {
        $('.actions-button').on('click', function(e) {
            e.stopPropagation()  
            const user_id = $(this).attr('data-userId');
            $("#modal_btn").click();
            users.map((user, index) => {
                if(user_id == user.id)
                    current_user = user;
            });
            console.log(current_user);
            
            let current_devices = [];
            // if(!current_user_devices.length)
                current_devices = current_user.devices;
            // else
            // current_devices = current_user_devices;
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

                users.map((user, index) => {
                    if(current_user.id == user.id) {
                        users[index].devices = JSON.stringify(current_devices);
                        console.log(index, users[index]);
                    }
                });

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
    }
    onUserClicked = function() {
        $("#modal_btn_user").click();
        const user_id = $(event.currentTarget).attr('trdata');

        users.map((user, index) => {
            if(user_id == user.id)
                current_user = user;
        });
        let current_username = current_user.name;
        let current_useremail = current_user.email;
        let current_userrole = current_user.role;

        $("#cur_user").val(current_username);
        $("#cur_email").val(current_useremail);

        $("#cur_role").prop("checked", current_userrole == 5);


        $('#cur_btn').on('click', function(e) {
            e.preventDefault();
            $role = $("#cur_role").prop("checked") ? 5 : 1;
            $.ajax({
                type: 'POST',
                url: '/user_manage/update_user',
                data: {
                    '_token': $('input[name="_token"]').val(),
                    'user_id': user_id,
                    'user_name': $("#cur_user").val(),
                    'user_email': $("#cur_email").val(),
                    'user_pwd': $("#cur_pwd").val(),
                    'user_role': $role
                },
                success: function(res) {

                    //view
                        //user_id
                        let user_name = res['name'];
                        let user_email = res['email'];
                        let user_role = res['role']

                        // control view
                        var $user_tr = $("#user_container tr[trdata='"+user_id+"']");
                        var html = [
                                `<th width="10%">${user_id}</th>`,
                                `<td>${user_name}</td>`,
                                '<td width="20%" style="padding:0.5rem;">',
                                    `<button data-userId="${user_id}" class="btn btn-primary actions-button">Assign to Device</button>`,
                                '</td>'
                        ].join('\n');
                        $user_tr.html(html);
                    //logic
                    users.map((user, index) => {
                        if(current_user.id == user.id) {
                            console.log(index, users[index]);
                            users[index] = {
                                ...users[index],
                                'name': user_name,
                                'email': user_email,
                                'role': user_role,
                            }
                        }
                    });
                
                $('#exampleModalScrollable_user').modal('hide');
                },
                error: function() {
                }
            })
        });
    }
</script>
@endpush
