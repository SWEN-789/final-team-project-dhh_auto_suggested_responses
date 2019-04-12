<div class="container">
    <div class="row">
        <div class="col">Admin</div>
        <div class="col" style="text-align: right"><button type="button" id="btnLogOut" onclick="chat_logout()" class="btn btn-primary btn-sm">Log Out</button></div>
    </div>
	<?php
	print_r($_SESSION);
	?>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        
    });

    function chat_logout() {
        $.ajax({
            url: "<?php echo site_url('index.php/Login/ajax_logout');?>",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.logout) {
                    window.location = "<?php echo site_url('index.php'); ?>";
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error Logging Out: ' + errorThrown);
            }
        });
    }

</script>
