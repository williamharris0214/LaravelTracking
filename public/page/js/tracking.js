let start_date, end_date;
let filteredArray_all = [];
let array = [];
let device_array = [];
let selectedArray_all = [];
let cur_first_device, cur_second_device;
let cur_first_sliderPos, cur_second_sliderPos;
let first_device_array = [];
let second_device_array = [];
let checked_array = [];

function updateSelect() {
    $("#device_first").html('');
    $("#device_first").append('<option value="0" disabled selected>Select Device</option>');
    for(let i = 0; i < device_array.length; i++) {
        if(device_array[i] != '')
            $("#device_first").append('<option value="' + device_array[i] + '">' + device_array[i] + '</option>');
    }
    $("#device_second").html('');
    $("#device_second").append('<option value="0" disabled selected>Select Device</option>');
    for(let i = 0; i < device_array.length; i++) {
        if(device_array[i] != '')
            $("#device_second").append('<option value="' + device_array[i] + '">' + device_array[i] + '</option>');
    }
}

function updateCheckBox(filteredArrayInput, is_start) {
    array = is_start? [] : [...filteredArrayInput];
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
        checked_array = array;
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
        //let date = new moment(start_date);
        let value = $("#slider_first").prop('value');
        //date.add(value,'s');
        updateMap(value, true);
        //$("#current_first_date").html(date.format('MM/DD/YYYY hh:mm:ss a'));
    }
}

function changeSecondSlider() {
    if(cur_second_device) {
        //let date = new moment(start_date);
        let value = $("#slider_second").prop('value');
        //date.add(value,'s');
        updateMap(value, false);
        //$("#current_first_date").html(date.format('MM/DD/YYYY hh:mm:ss a'));
    }
}

function onSelectFirst() {
    var selectedDevice = $("#device_first").val();
    let selectedArray;
    for(let i = 0; i < filteredArray_all.length; i++) {
        if(filteredArray_all[i].length && filteredArray_all[i][0].device_name === selectedDevice){
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
    first_device_array = selectedArray;
    setSliderAttr('#slider_first', 0, selectedArray.length-1, 1, selectedArray.length-1);
}

function onSelectSecond() {
    var selectedDevice = $("#device_second").val();
    let selectedArray;
    for(let i = 0; i < filteredArray_all.length; i++) {
        if(filteredArray_all[i].length && filteredArray_all[i][0].device_name === selectedDevice){
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
    second_device_array = selectedArray;
    setSliderAttr('#slider_second', 0, selectedArray.length-1, 1, selectedArray.length-1);
}

function updateMap(date, is_first) {
    let device_name;
    let is_left;
    let start, end;
    if(is_first) {
        device_name = cur_first_device;
        if(date < cur_first_sliderPos) {
            start = date;
            end = cur_first_sliderPos;
            is_left = true;
        }
        else {
            start = cur_first_sliderPos;
            end = date;
            is_left = false;
        }
        cur_first_sliderPos = date;
    }
    else {
        device_name = cur_second_device;
        if(date < cur_second_sliderPos) {
            start = date;
            end = cur_second_sliderPos;
            is_left = true;
        }
        else {
            start = cur_second_sliderPos;
            end = date;
            is_left = false;
        }
        cur_second_sliderPos = date;
    }
    let filteredArray = [];
    for(let i = 0; i < filteredArray_all.length; i++) {
        if(filteredArray_all[i].length && filteredArray_all[i][0].device_name === device_name){
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
        //const timestamp = new Date(el.timestamp).getTime();
        if(index >= start && index <= end)
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
    // if(!filteredArray_all.length)
    //     return;
    let cnt = 0;
    const csvData = [];
    //const headers = Object.keys(filteredArray_all[0][0]).join(",");
    const headers = "id,device_id,device_name,lat,lon,timestamp,conf,status,isodatetime"  
    csvData.push(headers);
    // for(let i = 0; i < filteredArray_all.length; i++) {
    //     for(let j = 0 ; j < filteredArray_all[i].length; j++) {
    //         const values = Object.values(filteredArray_all[i][j]).join(",");
    //         csvData.push(values);
    //     }
    // }
    for(let i = 0; i < checked_array.length; i++) {
        if(checked_array[i].length){
            for(let j = 0 ; j < checked_array[i].length; j++) {
                const values = Object.values(checked_array[i][j]).join(",");
                csvData.push(values);
            }
            cnt++;
        }
    }
    if(cnt === 0)
        return;
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
    // $(name).prop('min', min);
    // $(name).prop('max', max);
    // $(name).prop('step', step);
    // $(name).prop('value', value);
    $(name).get(0).min = min;
    $(name).get(0).max = max;
    $(name).get(0).step = step;
    // $(name).get(0).value = value;
    setTimeout(() => $(name).get(0).value = value, 0);
}

$("#datepicker1").datepicker({
    onSelect: function(dateText, instance) {
        start_date = moment(dateText);
    }
});

$("#datepicker2").datepicker({
    onSelect: function(dateText, instance) {
        end_date = moment(dateText);
    }
});

$(document).ready(() => {
    const myValueToValueIndicatorTransform = function(value) {
        const obj = first_device_array[value];
        if(obj === undefined)
            return '';
        const res = moment.unix(first_device_array[value].timestamp).format("hh:mm:ss");
        return res;
    };

    $('#slider_first').get(0).valueToValueIndicatorTransform = myValueToValueIndicatorTransform;
    $('#slider_second').get(0).valueToValueIndicatorTransform = myValueToValueIndicatorTransform;
});