<script>
    function updateUser() {
        try {
            var validate_password = passwordValidation('update');
            if (validate_password) {
                // get data
                var update_data = {
                    id_user: $('#id_user').val(),
                    nama: $('#update_nama').val(),
                    username: $('#update_username').val(),
                    email: $('#update_email').val(),
                    password: $('#update_password').val(),
                    user_level: $('#update_user_level').val(),
                };
                $.ajax({
                    url: '<?php base_url() ?>/settings-account/update/' + update_data.id_user,
                    type: 'POST',
                    data: update_data,
                    dataType: 'JSON',
                    success: function(data){
                        if (data.status != 'success') {
                            showSWAL('error', data.message);
                        } else {
                            showSWAL('success', data.message);
                        }
                    },
                    error: function(jqXHR){
                        showSWAL('error', jqXHR);
                    }
                });
            } else {
                showSWAL('error', 'Validasi password gagal! Password tidak cocok!');
            }
        } catch (error) {

        }
    }

    function passwordValidation(type) {
        switch (type) {
            case 'create':
                var password = $('#create_password').val();
                var confirm_password = $('#create_confirm_password').val();
                if (password === confirm_password) {
                    return true;
                } else {
                    return false;
                }
                break;

            case 'update':
                var password = $('#update_password').val();
                var confirm_password = $('#update_confirm_password').val();
                if (password === confirm_password) {
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                break;
        }
    }
</script>