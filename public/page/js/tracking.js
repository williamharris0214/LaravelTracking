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
let first_scroll_range = 1, second_scroll_range = 1;
let first_device_date, seconde_device_date;

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
    $("#first_device_history").html(cur_first_device + ' History');
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
    $("#second_device_history").html(cur_second_device + ' History');
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
    let savedArray = [];
    for(let i = 0; i < filteredArray_all.length; i++) {
        if(filteredArray_all[i].length && filteredArray_all[i][0].device_name === device_name){
            filteredArray = getFilteredIndex(filteredArray_all[i], start, end);
            savedArray = filteredArray_all[i];
            break;
        }
    }
    if(is_left){
        let pos = remove_markers(device_name, filteredArray);
        if(is_first) {
            $('#first_device_history_table').html('<tr>'+
                '<td style="padding:1rem;">'+ moment.unix(savedArray[filteredArray[0]].timestamp).format('MM/DD/YYYY')+ '</td>'+
                '<td style="padding:1rem;">'+ moment.unix(savedArray[filteredArray[0]].timestamp).format('hh:mm:ss')+ '</td>'+
                '<td style="padding:1rem;">'+ '(' + pos.lat + ', ' + pos.lng +')' + '</td>'+
                '</tr>');
        }
        else {
            $('#second_device_history_table').html('<tr>'+
                '<td style="padding:1rem;">'+ moment.unix(savedArray[filteredArray[0]].timestamp).format('MM/DD/YYYY')+ '</td>'+
                '<td style="padding:1rem;">'+ moment.unix(savedArray[filteredArray[0]].timestamp).format('hh:mm:ss')+ '</td>'+
                '<td style="padding:1rem;">'+ '(' + pos.lat + ', ' + pos.lng +')' + '</td>'+
                '</tr>');
        }
    }
    else{
        let res = add_markers(device_name, filteredArray, is_first ? first_scroll_range : second_scroll_range);
        if(is_first){
            $('#first_device_history_table').html('');
            res.forEach((obj) => {
                $('#first_device_history_table').append('<tr>' +
                    '<td style="padding:1rem;">' + moment.unix(savedArray[obj.index].timestamp).format('MM/DD/YYYY') + '</td>' + 
                    '<td style="padding:1rem;">' + moment.unix(savedArray[obj.index].timestamp).format('hh:mm:ss') + '</td>' + 
                    '<td style="padding:1rem;">' + '(' + obj.pos.lat + ', ' + obj.pos.lng +')' + '</td>' + 
                    '</tr>'
                );
            });
        }
        else {
            $('#second_device_history_table').html('');
            res.forEach((obj) => {
                $('#second_device_history_table').append('<tr>' +
                    '<td style="padding:1rem;">' + moment.unix(savedArray[obj.index].timestamp).format('MM/DD/YYYY') + '</td>' + 
                    '<td style="padding:1rem;">' + moment.unix(savedArray[obj.index].timestamp).format('hh:mm:ss') + '</td>' + 
                    '<td style="padding:1rem;">' + '(' + obj.pos.lat + ', ' + obj.pos.lng +')' + '</td>' + 
                    '</tr>'
                );
            });
        }
    }
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

getBackgroundColor = function(status, timestamp) {
    background_color = "bg-info";
    const now = Math.floor(Date.now() / 1000);
    let diffInMinutes = Math.floor((now - timestamp) / 60);
    switch(status) {
        case 0:
            background_color = "bg-danger";
            break;
        case 1:
            background_color = "bg-warning";
            break;
        case 2:
            background_color = "bg-success";
            break;
        case 3:
            background_color = "bg-info";
            if(diffInMinutes >= 12 * 24 * 60)
                background_color = "bg-success";
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
    let diffInMinutes = Math.floor((now - timestamp) / 60);
    let res = `${diffInMinutes} mins`;
    if(diffInMinutes >= 60) {
        let mins = diffInMinutes % 60;
        let hours = parseInt(diffInMinutes / 60);
        if(hours >= 24)
        {
            days = parseInt(hours / 24);
            hours = hours % 24;
            if(hours == 0) {
                if(mins == 0)
                    res = `${days} days`;
                else
                    res = `${days} days ${mins} mins`;
            }
            else {
                if(mins == 0)
                    res = `${days} days ${hours} hours`;
                else
                    res = `${days} days ${hours} hours ${mins} mins`;
            }
        }
        else{
            if(mins == 0)
                res = `${hours} hours`;
            else
                res = `${hours} hours ${mins} mins`;
        }
    }
    return res;
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

// $("#datepicker1").datepicker({
//     onSelect: function(dateText, instance) {
//         start_date = moment(dateText);
//     }
// });

// $("#datepicker2").datepicker({
//     onSelect: function(dateText, instance) {
//         end_date = moment(dateText);
//     }
// });

$("#datepicker1").on("change", function() {
    let datetimeValue = $(this).val();
    start_date = moment(datetimeValue);
});

$("#datepicker2").on("change", function() {
    let datetimeValue = $(this).val();
    end_date = moment(datetimeValue);
});

function onScrollRangeFirst() {
    first_scroll_range = Number($('#first_scroll_range').val());
    if(!Number.isInteger(first_scroll_range) || first_scroll_range < 1){
        $('#first_scroll_range').val(1);
        first_scroll_range = 1;
    }
}

function onScrollRangeSecond() {
    second_scroll_range = Number($('#second_scroll_range').val());
    if(!Number.isInteger(second_scroll_range) || second_scroll_range < 1){
        $('#second_scroll_range').val(1);
        second_scroll_range = 1;
    }
}

$(document).ready(() => {
    const myValueToValueIndicatorTransform = function(value) {
        const obj = first_device_array[value];
        if(obj === undefined)
            return '';
        const res = moment.unix(first_device_array[value].timestamp).format("MM/DD/YYYY hh:mm:ss");
        first_device_date = res;
        return res;
    };

    const myValueToValueIndicatorTransformX = function(value) {
        const obj = second_device_array[value];
        if(obj === undefined)
            return '';
        const res = moment.unix(second_device_array[value].timestamp).format("MM/DD/YYYY hh:mm:ss");
        seconde_device_date = res;
        return res;
    };

    $('#slider_first').get(0).valueToValueIndicatorTransform = myValueToValueIndicatorTransform;
    $('#slider_second').get(0).valueToValueIndicatorTransform = myValueToValueIndicatorTransformX;
});