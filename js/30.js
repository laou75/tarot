<script type="text/javascript">
$(function () {
    $('[id^="joueur_"]').popover({html: true,trigger: 'hover'});

    $('#myModal').on('show.bs.modal', function (e) {
        $.ajax({
            type: "GET",
            url: e.relatedTarget,
            success: function(msg) {
                $('.modal-content').html(msg);
            }
        });
    });
});
</script>