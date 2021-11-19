<?php
header("Cache-Control:none");

// This php section is used to get the data from the GET method used in the writetToJson();
//Check if the data is set and is not empty.
if( isset($_GET["data"]) && !empty($_GET["data"]) ){

    //decode the incoming string.
    $data = base64_decode($_GET["data"], true);

    //Write contents to file.
    $x = file_put_contents("test.json",$data);
}

?>
<html>
    <head>
        <title>Food Upload</title>
        <link rel='stylesheet' href='basic.css'>
        <script src="upload.js"></script>
    </head>
    <body>
        <div class='container'>
            <h2>Food Section</h2>
            <hr/>
            <Button class="delete" >Delete Food</Button>
            <div class='food-tb'>
                
                <div class='food-header'>
                    <div class='food-hdng' style='width:250px'>
                        Food
                    </div>
                    <div class='food-hdng'>
                        Portion Size(g)
                    </div>
                    <div class='food-hdng'>
                        Carb
                    </div>
                    <div class='food-hdng'>
                        Protein 
                    </div>
                    <div class='food-hdng'>
                        Fat
                    </div>
                    <div class='food-hdng'>
                        Calories
                    </div>
                </div>
                <div id='food-bd'>     
                </div>
            </div>
        </div>

<script>
displayAll();
addFood();
saveFood();
deleteFood();
selectedRow();
</script>
    </body>
</html>
