<script type="text/javascript">
    function changeImmediateAvailableStatus(user_id, old_status) {
        $.post("{{ route('update.immediate.available.status') }}",
         {user_id: user_id, old_status: old_status, _method: 'POST', _token: '{{ csrf_token() }}'})
        .done(function (response) {
            if (response == 'ok') {
                if (old_status == 0)
                    $('#is_immediate_available').attr('checked', 'checked');
                else
                    $('#is_immediate_available').prop('checked');
            }
        });

    }
</script>