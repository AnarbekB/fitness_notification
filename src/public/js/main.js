$( document ).ready(function() {
    $('#channel-notification').on('change', function () {
        currentChannel = $(this).find('option:selected').data('channel');

        url = '/channel/change/notification/' + currentChannel;
        $.ajax( url )
            .done(function() {
                UIkit.notification({message: 'Настройки сохранены', status: 'success', pos: 'bottom-right'})
            })
            .fail(function() {
                UIkit.notification({message: 'Ошибка сохранения', status: 'warning', pos: 'bottom-right'})
            });
    })
});