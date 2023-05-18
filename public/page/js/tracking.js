let start_date, end_date;
let filteredArray_all = [];
let array = [];
let device_array = [];
let selectedArray_all = [];
let cur_first_device, cur_second_device;
let cur_first_sliderPos, cur_second_sliderPos;

function updateSelect() {
    $("#device_first").html('');
    $("#device_first").append('<option value="0" disabled selected>Select Device</option>');
    for(let i = 0; i < device_array.length; i++) {
        $("#device_first").append('<option value="' + device_array[i] + '">' + device_array[i] + '</option>');
    }
    $("#device_second").html('');
    $("#device_second").append('<option value="0" disabled selected>Select Device</option>');
    for(let i = 0; i < device_array.length; i++) {
        $("#device_second").append('<option value="' + device_array[i] + '">' + device_array[i] + '</option>');
    }
}

function updateCheckBox(filteredArrayInput) {
    array = [...filteredArrayInput];
    $("mwc-checkbox").on('change', (a) => {
        const isChecked = $(a.target).prop('checked');
        const device_id = $(a.target).attr('data-deviceid');
        if(isChecked) {
            for(let i = 0; i < filteredArrayInput.length; i++){
                if(filteredArrayInput[i].length && filteredArrayInput[i][0].device_id == device_id) {
                    device_array.splice(i, 0, filteredArrayInput[i][0].device_name);
                    array.splice(i, 0, filteredArrayInput[i]);
                    break;
                }
            }
            var loc_data = getLocationData(array);
            remove_all_markers();
            refresh_marker(loc_data);
        }
        else{
            for(let i = 0; i < array.length; i++){
                if(array[i].length && array[i][0].device_id == device_id) {
                    device_array.splice(i, 1);
                    array.splice(i, 1);
                    break;
                }
            }
            var loc_data = getLocationData(array);
            remove_all_markers();
            refresh_marker(loc_data);
        }
        updateSelect();
        cur_first_device = cur_second_device = null;
        selectedArray_all = [];
    });
}

getLocationData = function(d_array) {
        const res = new Object;
        for(let i = 0; i < d_array.length ; i++) {
            if(d_array[i].length){
                res[d_array[i][0].device_name] = [];
                for(let j = 0; j < d_array[i].length; j++) { 
                    res[d_array[i][j].device_name].push([d_array[i][j].lat, d_array[i][j].lon]);
                }
            }
        }
        return res;
}

function setTextFromSlider() {
    let date = new moment(start_date);
    let value = $("#slider").prop('value');
    date.add(value, 'd');
    return date;
}

function changeFirstSlider() {
    if(cur_first_device){
        let date = new moment(start_date);
        let value = $("#slider_first").prop('value');
        date.add(value,'s');
        updateMap(date, true);    
    }
}

function changeSecondSlider() {
    if(cur_second_device) {
        let date = new moment(start_date);
        let value = $("#slider_second").prop('value');
        date.add(value,'s');
        updateMap(date, false);
    }
}

function onSelectFirst() {
    var selectedDevice = $("#device_first").val();
    let selectedArray;
    for(let i = 0; i < filteredArray_all.length; i++) {
        if(filteredArray_all[i][0].device_name === selectedDevice){
            selectedArray = filteredArray_all[i];
            break;
        }
    }
    if(cur_first_device)
        selectedArray_all[0] = selectedArray;
    else
        selectedArray_all.splice(0, 0, selectedArray);
    cur_first_device = selectedDevice;
    var loc_data = getLocationData(selectedArray_all);
    remove_all_markers();
    refresh_marker(loc_data);
}

function onSelectSecond() {
    var selectedDevice = $("#device_second").val();
    let selectedArray;
    for(let i = 0; i < filteredArray_all.length; i++) {
        if(filteredArray_all[i][0].device_name === selectedDevice){
            selectedArray = filteredArray_all[i];
            break;
        }
    }
    if(cur_first_device)
        selectedArray_all[1] = selectedArray;
    else
        selectedArray_all.splice(1, 0, selectedArray);
    var loc_data = getLocationData(selectedArray_all);
    remove_all_markers();
    refresh_marker(loc_data);
    cur_second_device = selectedDevice;
}

function updateMap(date, is_first) {
    let device_name;
    let is_left;
    let start, end;
    if(is_first) {
        device_name = cur_first_device;
        if(date < cur_first_sliderPos) {
            start = date.unix();
            end = cur_first_sliderPos.unix();
            is_left = true;
        }
        else {
            start = cur_first_sliderPos.unix();
            end = date.unix();
            is_left = false;
        }
        cur_first_sliderPos = date;
    }
    else {
        device_name = cur_second_device;
        if(date < cur_second_sliderPos) {
            start = date.unix();
            end = cur_second_sliderPos.unix();
            is_left = true;
        }
        else {
            start = cur_second_sliderPos.unix();
            end = date.unix();
            is_left = false;
        }
        cur_second_sliderPos = date;
    }
    let filteredArray = [];
    for(let i = 0; i < filteredArray_all.length; i++) {
        if(filteredArray_all[i][0].device_name === device_name){
            filteredArray = getFilteredIndex(filteredArray_all[i], start, end);
            break;
        }
    }
    if(is_left)
        remove_markers(device_name, filteredArray);
    else
        add_markers(device_name, filteredArray);
}

function getFilteredArray(filteredArray, start, end) {
    filteredArray = filteredArray.filter(function(el) {
        const timestamp = new Date(el.timestamp).getTime();
        return timestamp >= start && timestamp <= end;
    });
    return filteredArray;
}

function getFilteredIndex(filteredArray, start, end) {
    let filteredIndexes = [];
    filteredArray.map(function(el, index) {
        const timestamp = new Date(el.timestamp).getTime();
        if(timestamp >= start && timestamp <= end)
            filteredIndexes.push(index);
    });
    return filteredIndexes;
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

setSliderAttr = function(name, min, max, step, value) {
    $(name).prop('min', min);
    $(name).prop('max', max);
    $(name).prop('step', step);
    $(name).prop('value', max);
    setTimeout(() => $(name).prop('value', max), 0);
}