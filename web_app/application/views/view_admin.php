<div class="container">
    <div class="row">
        <div class="col">Admin</div>
        <div class="col" style="text-align: right"><button type="button" id="btnLogOut" onclick="chat_logout()" class="btn btn-primary btn-sm">Log Out</button></div>
    </div>
    <div class="row">
        <div class="col"><h6>Who's Online</h6></div>
    </div>
    <div class="row">
        <div class="col">
            <ul>
	            <?php
	            //print_r($users_online);
	            foreach ($users_online as $user) {
		            echo "<li>" . $user->user_fullname . "</li>";
	            }
	            ?>
            </ul>

        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="chat_viewport" style=""></div>
            <div class="row">
                <div class="col"></div>
            </div>
            <div id="chat_input">
                <form action="#" id="form_chat" name="form_chat">
                    <input type="hidden" value="<?php echo $_SESSION['chat_id']; ?>" name="chat_id" id="chat_id" />
                    <input type="hidden" value="1" name="user_id" id="user_id" />
                    <input type="hidden" value="1" name="user_admin" id="user_admin" />
                    <div class="row">
                        <div class="col">
                            <div class="input-group mb-3">
						        <?php
//						        if ($_SESSION['user_session']['user_admin']) {  // if admin, don't show suggested responses
//							        ?>
                                    <input type="text" class="form-control" id="message_content" name="message_content"/>
<!--							        --><?php
//						        } else {
//							        ?>
<!--                                    <input type="text" class="form-control" id="message_content" list="suggestions" name="message_content" autocomplete="off"/>-->
<!--                                    <datalist id="suggestions"></datalist>-->
<!--							        --><?php
//						        }
//						        ?>

                                <div class="input-group-append">
                                    <button class="btn btn-success" id="btn_send_message" type="button" onclick="send_message()">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </div>
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
