<script type="text/javascript">
$(function () {
    $('a[id^="info_"]').popover({html:true,trigger:'hover'});
    $('[id^="joueur_"]').popover({html:true,trigger:'hover'});

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