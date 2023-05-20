$(document).ready(() => {
    $('#email, #password').on('keydown', (event) => {
        if(event.key === 'Enter'){
            $('form').submit();
        }
    });
})
