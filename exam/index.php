<html>
    <header>
        <title>Convert number to word</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </header>
    <body>
        <input type="number" id="input_no" placeholder="input number">
        <input type="button" id="submit_no" value="Submit">
        <h5>(Input Limit: Hundred million to two decimal places only)</h5></br>
        <p id="word_output"></p>
    </body>
    <script>
        $("#submit_no").click(function(){
            var input_no = $("#input_no").val();
            if(input_no != ""){
                $.ajax({
                    type: "POST",
                    url: "convert.php",
                    data: {
                        'input_no': input_no,
                    },
                    success: function(result){
                        if(result != "INVALID")
                            $("#word_output").empty().append(result);
                        else
                            alert("Invalid Input.");
                    },
                });
            }
        });
    </script>
</html>