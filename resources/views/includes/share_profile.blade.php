<script type="text/javascript">
    function loadButtons(url) {
        $.get("{{ route('shorten.url') }}",
         {longUrl: url , _method: 'GET', _token: '{{ csrf_token() }}'})
        .done(function (response) {
            $('#social_buttons').html(response);
        });
    }
</script>