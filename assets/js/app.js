$(document).ready(function(){
    
    $('.popup').magnificPopup({
        type: 'ajax',
        alignTop: true
    });

});



/* ---------------------- */
/*  ==     SENSORS        */
/* ---------------------- */

// Add or Edit a sensor
function sensor_addOrEdit(type, $form)
{
    if (type == 'add')
        var notif = 'created';
    else
        var notif = 'changed';

    $form.find('input[type="submit"], button').attr('disabled', 'disabled');

    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        dataType: 'json',
        data: $form.serialize(),
    })
    .done(function(result) {
        if (result.errors != '')
        {
            $form.find('input[type="submit"], button').removeAttr('disabled');
            
            $('body').gNotifier2({
                'title' : 'Error !',
                'text'  : result.errors,
                'type'  : 'error'
            });
        }
        else
        {
            $('body').gNotifier2({
                'title'   : 'Success !',
                'text'    : 'Sensor successfuly '+notif,
                'onClose' : function(){ window.location.href = $form.attr('data-redirect'); }
            });
        }
    });
}

// Remove a sensor
function sensor_remove(id)
{
    var msg = 'Are you sure to remove this sensor ? This will removes all associated temperatures !';

    if (!confirm(msg))
        return false;

    $.ajax({
        url: 'sensors_process.php?action=delete&id='+id,
        type: 'GET',
        dataType: 'json',
    })
    .done(function(result) {
        if (result.errors != '')
        {
            $('body').gNotifier2({
                'title'   : 'Error !',
                'text'    : result.errors,
                'type'    : 'error',
                'timeout' : 5000
            });
        }
        else
        {
            $('body').gNotifier2({
                'title'   : 'Success !',
                'text'    : 'Sensor successfuly removed !',
                'onClose' : function(){ window.location.href = 'sensors.php'; }
            });
        }
    });
}


/* --------------------- */
/*  ==     GROUPS        */
/* --------------------- */

// Add or Edit a group
function group_addOrEdit(type, $form)
{
    if (type == 'add')
        var notif = 'created';
    else
        var notif = 'changed';

    $form.find('input[type="submit"], button').attr('disabled', 'disabled');

    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        dataType: 'json',
        data: $form.serialize(),
    })
    .done(function(result) {
        if (result.errors != '')
        {
            $form.find('input[type="submit"], button').removeAttr('disabled');
            
            $('body').gNotifier2({
                'title' : 'Error !',
                'text'  : result.errors,
                'type'  : 'error'
            });
        }
        else
        {
            $('body').gNotifier2({
                'title'   : 'Success !',
                'text'    : 'Group successfuly '+notif,
                'onClose' : function(){ window.location.href = $form.attr('data-redirect'); }
            });
        }
    });
}

// Remove a group
function group_remove(id)
{
    var msg = 'Are you sure to remove this group ?';

    if (!confirm(msg))
        return false;

    $.ajax({
        url: 'groups_process.php?action=delete&id='+id,
        type: 'GET',
        dataType: 'json',
    })
    .done(function(result) {
        if (result.errors != '')
        {
            $('body').gNotifier2({
                'title'   : 'Error !',
                'text'    : result.errors,
                'type'    : 'error',
                'timeout' : 5000
            });
        }
        else
        {
            $('body').gNotifier2({
                'title'   : 'Success !',
                'text'    : 'Group successfuly removed !',
                'onClose' : function(){ window.location.href = 'groups.php'; }
            });
        }
    });
}


/* ---------------------- */
/*  ==     MANAGE        */
/* ---------------------- */

// Edit parameters
function manage_edit($form)
{
    $form.find('input[type="submit"], button').attr('disabled', 'disabled');

    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        dataType: 'json',
        data: $form.serialize(),
    })
    .done(function(result) {
        if (result.errors != '')
        {
            $form.find('input[type="submit"], button').removeAttr('disabled');
            
            $('body').gNotifier2({
                'title' : 'Error !',
                'text'  : result.errors,
                'type'  : 'error'
            });
        }
        else
        {
            $('body').gNotifier2({
                'title'   : 'Success !',
                'text'    : 'Parameters successfuly changed!',
                'onClose' : function(){ window.location.href = $form.attr('data-redirect'); }
            });
        }
    });
}

// Make password field visible on checkbox click
function manage_enableChangePasswd()
{
    $('#manage_edit #passwd').attr('disabled', 'disabled');

    if ($('#manage_edit #passwd_chk').is(':checked'))
        $('#manage_edit #passwd').removeAttr('disabled').focus();
}