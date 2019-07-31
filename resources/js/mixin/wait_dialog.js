export default {

    methods: {
        show_wait: function (text) {
            $('body').loadingModal({
               position: 'auto',
               text: text,
               color: '#fff',
               opacity: '0.7',
               backgroundColor: 'rgb(0,0,0)',
               animation: 'circle'

            });
        },
        hide_wait: function () {
            // hide the loading modal
            $('body').loadingModal('hide');
            /// destroy the plugin
            $('body').loadingModal('destroy');

        }


    }
}
