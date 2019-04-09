<?php
//print_r($_SESSION);
?>
<div class="container">
	<div class="row">
		<div class="col">Chats</div>
        <div class="col" style="text-align: right">Logged in: <?php echo $_SESSION['user_session']['user_fullname'];?></div>
	</div>
    <div class="row">
        <div class="col-lg-8">
            <div id="chat_viewport" style=""></div>
            <div class="row">
                <div class="col"></div>
            </div>
            <div id="chat_input">
                <form action="#" id="form_chat" name="form_chat">
                    <input type="hidden" value="1" name="chat_id" id="chat_id" />
                    <input type="hidden" value="<?php echo $_SESSION['user_session']['user_id']; ?>" name="user_id" id="user_id" />
                    <input type="hidden" value="<?php echo $_SESSION['user_session']['user_admin']; ?>" name="user_admin" id="user_admin" />
                    <div class="row">
                        <div class="col">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="message_content" list="suggestions" name="message_content"/>
                                <datalist id="suggestions">
<!--                                    <option value="Suggested Response #1">-->
<!--                                    <option value="Suggested Response #2">-->
<!--                                    <option value="Suggested Response #3">-->
<!--                                    <option value="Suggested Response #4">-->
<!--                                    <option value="Suggested Response #5">-->
                                </datalist>
                                <div class="input-group-append">
                                    <button class="btn btn-success" id="btn_send_message" type="button" onclick="send_message()">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <h5>Who's Online</h5>
	        <?php
                //print_r($users_online);
                foreach ($users_online as $user) {
                    echo "<h6>" . $user->user_fullname . "</h6>";
                }
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {

        //scroll_down();

        setInterval(function() {
            get_chat_messages();
            //get_auto_responses('what would you like to order');
            //get_auto_responses();
            //scroll_down();
        }, 1000);



       // $("#chat_viewport").scrollTop($("#chat_viewport")[0].scrollHeight);

        $('#message_content').keypress(function(e) {
            if (e.which == 13) {
                send_message();
                return false;
            }
        });

    });

    function scroll_down() {
        var viewport = $("#chat_viewport");
        viewport.scrollTop(viewport.prop("scrollHeight"));
    };

    function send_message() {

        $.ajax({
            url: "<?php echo site_url('index.php/Chat/ajax_add_chat_message');?>",
            type: "POST",
            data: $('#form_chat').serialize(),
            dataType: "JSON",
            success: function(data) {
               if (data.status) {
                   $('#chat_viewport').html(data.content);
                   var message_content_pre = $('#message_content').val();
                   $('#message_content').val('');
                   //var content = 'what would you like to order';
                   //get_auto_responses();
                   get_auto_responses(message_content_pre);


               }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Add New Chat Message Error adding / update data: ' + errorThrown);
            }
        });
    }

    function get_chat_messages() {
        $.ajax({
            url: "<?php echo site_url('index.php/Chat/ajax_get_chat_messages');?>",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    //var current_content = $("#chat_viewport").html();
                    //$("#chat_viewport").html(current_content + data.content);
                    $("#chat_viewport").html(data.content);

                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Get Chat Messages Error adding / update data: ' + errorThrown);
            }
        });
    }

    function get_last_message_id() {
        $.ajax({
            url: "<?php echo site_url('index.php/Chat/ajax_get_last_chat_message_id');?>",
            type: "POST",
            dataType: "JSON",
            success: function(data) {

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Get last Chat Messages Error adding / update data: ' + errorThrown);
            }
        });
    }


    function get_auto_responses(content) {

        //$('#suggestions').html('');
        document.getElementById('suggestions').innerHTML = '';

        var str = content;
        str = str.replace(/\s+/g, '-').toLowerCase();

        //alert("'"+content+"'");
        console.log(content);
        console.log(str);


        //var content = 'what would you like to order';
        //content = "'" + content + "'";

        $.ajax({
            url: "<?php echo site_url('index.php/Chat/ajax_get_api_results');?>/" + str,
            type: "POST",
            //data: str,
            dataType: "JSON",
            success: function(data) {
                //alert(JSON.stringify(data));
                var responses = data.suggestedResponses;

                $.each(responses, function(i, item) {
                   $("#suggestions").append("<option value='" + responses[i] + "'>");
                });

                //alert(responses);
                // if (data.status) {
                //     $('#message_content').val(data.suggestedResponses);
                //     //$('#message_content').val('');
                // }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Get auto responses Error adding / update data: ' + errorThrown);
            }
        });
    }

    //scroll_down();
    get_chat_messages();

</script>