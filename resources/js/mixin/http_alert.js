export default {

    methods: {
        alert_success: function (response) {
            alert("Success! " + this.message_success(response));
        },
        alert_failed: function (error) {
            alert("Failed during the execution process!\n" + this.message_failed(error));
        },
        message_failed: function (error) {
            let validation_erorrs = error.response.data.errors;
            let error_message = error.response.data.message + "\n";
            if (validation_erorrs !== undefined) {
                Object.keys(validation_erorrs).forEach(function (key) {
                    error_message += validation_erorrs[key][0] + "\n";
                });
            }
            return error_message;
        },
        message_success: function (response) {
            return response.data.message;

        }

    }
}
