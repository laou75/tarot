<script type="text/javascript">
$(function () {
    $('#myModal').on('show.bs.modal', function (e) {
        alert('ouverte');
        })

    $('#myModal').on('hidden.bs.modal', function (e) {
        alert('fermée');
        })

});
</script>