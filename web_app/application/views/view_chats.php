
<div class="container">
	<div class="row">
		<div class="col">Logged in: <?php echo $_SESSION['user_session']['user_fullname'];?> (Chat #<?php echo $chat_id; ?>)</div>
        <div class="col" style="text-align: right"><button type="button" id="btnLogOut" onclick="chat_logout()" class="btn btn-primary btn-sm">Log Out</button></div>
	</div>
    <div class="row">
        <div class="col-lg-8">
            <div id="chat_viewport" style=""></div>
            <div class="row">
                <div class="col"></div>
            </div>
            <div id="chat_input">
                <form action="#" id="form_chat" name="form_chat">
                    <input type="hidden" value="<?php echo $_SESSION['chat_id']; ?>" name="chat_id" id="chat_id" />
                    <input type="hidden" value="<?php echo $_SESSION['user_session']['user_id']; ?>" name="user_id" id="user_id" />
                    <input type="hidden" value="<?php echo $_SESSION['user_session']['user_admin']; ?>" name="user_admin" id="user_admin" />
                    <div class="row">
                        <div class="col">
                            <div class="input-group mb-3">
                                <?php
                                if ($_SESSION['user_session']['user_admin']) {  // if admin, don't show suggested responses
                                ?>
                                <input type="text" class="form-control" id="message_content" name="message_content"/>
                                <?php
                                } else {
                                ?>
                                    <input type="text" class="form-control" id="message_content" list="suggestions" name="message_content" autocomplete="off"/>
                                    <datalist id="suggestions"></datalist>
	                                <?php
                                }
                                ?>

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
<!--            <h5>Who's Online</h5>-->
	        <?php
//                //print_r($users_online);
//                foreach ($users_online as $user) {
//                    echo "<h6>" . $user->user_fullname . "</h6>";
//               }
            ?>
        </div>
    </div>
	<?php
	print_r($_SESSION);
	?>
</div>



<script type="text/javascript">

    var last_sender;
    var last_message = '';
    var user_role = <?php echo $_SESSION['user_session']['user_admin']; ?>;
    console.log('user_role: ' + user_role);

    $(document).ready(function() {

        //get_chat_messages();

        $( "#message_content" ).on( "keyup", function( event ) {

            //$( "#log" ).html( event.type + ": " +  event.which );

            if (event.keyCode == 13) {
                output = output + '[ENTER]';
                console.log(output);
                write_data(output);
                //$('#whichkey').val('');
            } else if (event.which == 8) {
                output = output + '[BACKSPACE]';
                console.log(output);
                write_data(output);

                // } else if (event.which == 32) {
                //     output = output + '*SPACE*';
            } else if (event.which == 46) {
                output = output + '[DEL]';
            } else {
                output = $(this).val();
            }

            $( "#log" ).html( output );
        });

        function write_data(textData) {
            userName = <?php echo $_SESSION['user_session']['user_id']; ?>;

            $.ajax({
                url: "<?php echo site_url('index.php/DataCollector/ajax_write_data');?>",
                type: "POST",
                data: {"user" : userName, "data" : textData},
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        //alert('Success!');
                    }

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error writing data to file: ' + errorThrown);
                }
            });
        }

        //scroll_down();

        setInterval(function() {
            get_chat_messages();
            //get_auto_responses('what would you like to order');
            //get_auto_responses();
            //scroll_down();

            get_last_sender();

            console.log('last_sender: ' + last_sender);
            if (last_sender == 1) {
                get_last_message();
                console.log('last_message: ' + last_message);
                if (last_message != null) {
                    get_auto_responses(last_message);
                    last_message = '';
                }

            } else {
                console.log('user_role: ' +  user_role);
                if (user_role == 0) {
                    $('#message_content').val('');
                    removeOptions(document.getElementById("suggestions"));
                } else {
                    removeOptions(document.getElementById("suggestions"));
                    //console.log('ends right here');
                }

            }

        }, 3000);



       // $("#chat_viewport").scrollTop($("#chat_viewport")[0].scrollHeight);

        $('#message_content').keypress(function(e) {
            if (e.which == 13) {
                send_message();
                //usleep(500);
                // get_last_sender();
                //
                // console.log('last_sender: ' + last_sender);
                // if (last_sender) {
                //     get_last_message();
                //     console.log('last_message: ' + last_message);
                //     get_auto_responses(last_message);
                // }

                return false;
            }
        });

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

                   // get_last_sender();
                   // console.log('last_sender: ' + last_sender);
                   // if (last_sender) {
                   //     get_last_message();
                   //     console.log('last_message: ' + last_message);
                   //     get_auto_responses(last_message);
                   // }


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
            //data: "<?php echo $_SESSION['chat_id']; ?>",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    //var current_content = $("#chat_viewport").html();
                    //$("#chat_viewport").html(current_content + data.content);
                    $("#chat_viewport").html(data.content);
                    // if (get_last_sender()) {
                    //     var last_message = get_last_message();
                    //     //console.log(last_message);
                    //     get_auto_responses(last_message);
                    // }

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

    function get_last_message() {
        $.ajax({
            url: "<?php echo site_url('index.php/Chat/ajax_get_last_message');?>",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.message) {
                    //alert('last message is ' + message);
                    //console.log(JSON.stringify(data.message));
                    last_message = data.message.message;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Get last Message Error adding / update data: ' + errorThrown);
            }
        });
    }

    function get_last_sender() {
        //var result;
        $.ajax({
            url: "<?php echo site_url('index.php/Chat/ajax_get_last_sender');?>",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data) {
                    last_sender = data.last_sender.last_sender;
                    //console.log('last_sender2: '+ data.last_sender.last_sender);
                } else {
                    console.log('error');
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Get last Message Error adding / update data: ' + errorThrown);
            }
        });
        //return result;
    }

    function get_auto_responses(content) {

        //$('#suggestions').html('');
        //document.getElementById('suggestions').innerHTML = '';

        var str = content;
        str = str.replace(/\s+/g, '-').toLowerCase();

        //alert("'"+content+"'");
        //console.log(content);
        //console.log(str);


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
                   //console.log('check' + i);
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

    function removeOptions(selectbox)
    {
        var i;
        for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
        {
            selectbox.remove(i);
        }
    }

    //scroll_down();
    get_chat_messages();

</script>