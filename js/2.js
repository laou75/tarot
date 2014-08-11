<script type="text/javascript">
$(function () {
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