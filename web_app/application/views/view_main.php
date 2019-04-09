<div class="container">
	<div class="row">
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col">
			<div class="card">
				<div class="card-header">
					<h5>Log-in</h5>
				</div>
				<div class="card-body">
					<form action="#" id="form_login" class="form-horizontal" onsubmit="return false;">
						<p class="card-text">
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Password">
						</div>
						</p>
						<button type="button" id="btnSave" onclick="chatLogin()" class="btn btn-primary">Sign in!</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col"></div>
	</div>
</div>

<script type="text/javascript">

    $(document).ready(function() {



    });

    function chatLogin() {
        //alert('logging in...');
        $.ajax({
            url: "<?php echo site_url('index.php/Chat/ajax_check_login'); ?>",
            type: "POST",
            data: $('#form_login').serialize(),
            dataType: "JSON",
            success: function(data) {
                if (data.authenticated) {
                    //alert('Logged in.');
                    window.location = "<?php echo site_url('index.php/Chat/view_chats'); ?>";
                } else {
                    alert('You are not authorized.');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error logging in: ' + errorThrown);
            }
        });
    }

</script>