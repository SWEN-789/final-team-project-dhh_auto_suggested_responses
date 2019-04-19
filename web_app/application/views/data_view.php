<?php
?>
<script type="text/javascript">

    var output = '';

    $(document).ready(function() {
        //var dInput;
        $( "#whichkey" ).on( "keyup", function( event ) {

            //$( "#log" ).html( event.type + ": " +  event.which );

            if (event.keyCode == 13) {
                output = output + '[ENTER]';
                console.log(output);
                write_data(output);
                $('#whichkey').val('');
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
            userName = 'testuser2';

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

    });
</script>
<div class="container">

    <input id="whichkey" placeholder="type something">
<!--    <div id="log"></div>-->

</div>
