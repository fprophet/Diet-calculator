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
        <script data-main = 'libs/main' src = 'libs/require.js'></script>
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
    </body>
</html>
<?php

?>
<script>

    /*
        Similar tot the getVals() from food-calculator, this requests the file from the server, parses the contents and returns them.
        @Return{array}
    */
    function getVals(){
        var x = new XMLHttpRequest();
        x.open("GET","/calculator/fitness-diet-calculator/test.json", false);
        x.send();
        var data = JSON.parse(x.responseText,true);
        return data;
    }
    
    /*
        This function adds a click event listener and is used to highlight all the selected rows for delete.
        Finds all the ckeckboxes and highlights the checked ones with a specific color.
    */
    function selectedRow(){
        var checks = document.querySelectorAll(".chk-box");
        for( i=0; i < checks.length; i++ ){
            checks[i].addEventListener("click", function(){
                console.log(this.parentNode);
                if ( event.currentTarget.checked ){
                    this.parentNode.style.backgroundColor = "#8ee6d78c";
                } else {
                    this.parentNode.style.backgroundColor = "white";
                }
            })
        }
    }


    /*
    This function is used to create a new row in the food table everytime a new 
    meal is added to the food-list. It is called in the displayAll() function, so for every meal a row is created and inserted in the table.
    It is also called in the addFood() function to create an input row.
    $Params{string:"div", "input"}
    @Return{HTML element(table row)};
    */
    function createRow(element){
        var fields = ["fd-type", "portion-size", "carb", "protein","fat", "calories"];

        //First reach the body of the table.
        var bd = document.querySelector("#food-bd");

        //Create the row element and add specific classes.
        var row = document.createElement("div");
        row.classList.add("fd-row");

        //Loop that creates every row cell of the table usint the fields[] array in order to add the class of the element.
        for( i=0; i < fields.length; i++ ){
            var x = document.createElement(element);
            x.classList.add(fields[i]);
            x.classList.add("element");
            row.appendChild(x);
        }

        //this section is used to add a checkbox to the row. Used for deleting a meal from the list.
        var boxes = row.querySelectorAll(".chk-box");

        //This statement verifies if the row has already a checkbox or not.
        if( boxes.length == 0 ){
            var chk = document.createElement("input");
            chk.type = "checkbox";
            chk.classList.add("chk-box");
            row.appendChild(chk);
            
        }
        bd.appendChild(row);
        return row;
    }

    /*
    This function is used to display all the meals in the .json file in the food table.
    */
    function displayAll(){
        var data = getVals();
        var fields = ["portion-size", "carb", "protein","fat", "calories"];
        for( k=0; k < Object.values(data).length; k++ ){
            var row = createRow("div");
            var x = row.querySelectorAll(".element");
            for( j=0; j < x.length; j++ ){
                
                //This statement checks if the first element in the loop is the food header.
                if( j == 0 ){

                    //If it is, the element will display the meal's name.
                    x[j].innerHTML = Object.keys(data)[k];

                    //Else display the values of the food.
                }else{
                    x[j].innerHTML = Object.values(data)[k][fields[j-1]];
                }
            }
        }
    }

    /*
    This function is used to create the "add to list" section of the table.
    Creates an input row at the beggining of the table.
    */
    function addFood(){
        var fields = ["fd-type", "portion-size", "carb", "protein","fat", "calories"];

        //Target location: the table itself
        var bd = document.querySelector(".food-tb");

        //Create row and remove the checkbox and add class.
        var row = createRow("input");
        row.children[6].remove();
        row.classList.add("add");

        //Placeholders for all the inputs.
        for( i=0; i < fields.length; i++ ){
            row.children[i].placeholder = fields[i];
        }

        //Button for saving the inserted food.
        var btn = document.createElement("button");
        btn.classList.add("save-fd");
        btn.id = "save-btn";
        btn.innerText = "Add to List";

        //Insertion of all elements in the table.
        bd.insertBefore(row, bd.childNodes[0]);
        bd.insertBefore(btn, bd.childNodes[0]);
        var line = document.createElement("hr");
        bd.insertBefore(line,bd.childNodes[3]);
    }

    /*
    This function adds a click event listener to the Add to list button on the insertion row.
    */
    function saveFood(){
        var x = document.querySelector(".save-fd");
        x.addEventListener("click", function(){

            //The hdngs variable is used to store all the table headers.
            var hdngs = document.querySelectorAll(".food-hdng");

            //Create a new object from the existing foods.
            var objs = createObj();

            //deletes of cases of empty entries.
            delete objs[""];

            // Variable new_ent stores all the input elements from the insertion row to retrieve the values.
            var new_ent = document.querySelectorAll("input.element");

            //Used to store the object created from the input values
            var ob = {};

            var c ;
            for( i=0; i < new_ent.length; i++ ){

                //Statement that verifies if the first element is a header.
                if( i == 0 ){
                    food_name = new_ent[i].value.toLowerCase().trim();

                    //Stores the food name in the input object
                    ob[food_name] = {};
                    
                    //transform all the headings to match the .json file format
                    //Example: "Portion Size(g)" will become "portion-size".
                }else{
                    name = hdngs[i].innerHTML.toLowerCase();
                    name = name.trim();
                        if( name.indexOf("portion") >= 0 ){ 
                            name = name.replace(" ", "-");
                            name = name.substr(0,name.length-3);
                        }
                        
                    //The values taken from each input stored in the specific object attribute.
                    val = new_ent[i].value;
                    ob[food_name][name] = val;
                }
            }

            // Result takes the object created from the existing foods in the table and adds the inputted object to the list
            // and make a string out of it.
            var result = Object.assign(objs, ob);
            result = JSON.stringify(result);

            //Encoding the string to.
            var encoded = btoa(result);
            writeToJson(encoded);
        })
        
    }


    /*
    This function is used to write new entries to the .json file.
    */
    function writeToJson(f){
        var x = new XMLHttpRequest();

        //Get method used to send the data.
        x.open("GET", "/calculator/fitness-diet-calculator/food-upload.php?data="+f, false);
        x.send();

        //refresh the window.
        window.location.href = window.location.href;
    }

    /*
    This function adds a clicl event listener to the "Delete" button.
    Verifies if the boxes of the rows that are about to be deleted are checked.
    */
    function deleteFood(){
        var del = document.querySelector(".delete");

        //When deleting a food we are removing a row from the table.
        var rows = document.querySelectorAll(".fd-row");

        del.addEventListener("click", function(){
            var boxes = document.querySelectorAll(".chk-box");

            //Loop that checks if the checkboxes are checked.
            for( i=0; i < boxes.length; i++ ){
                if( boxes[i].checked == true ){
                    rows[i+1].remove();
                }
            }

            //Create a new object from the remaining list and add that data to the .json file.
            var objs = createObj();
            delete objs[""];
            result = JSON.stringify(objs);
            var encoded = btoa(result);
            writeToJson(encoded);
            window.location.href = window.location.href;
        })
    }

    /*
        This function creates an object of the current food list from the table.
        It is called in the saveFood() and deleteFood() functions, so everytime a new change in the table is made
        a new object is created with the current food lists.
        @Return{List of objects}.
    */
    function createObj(){

        //We need all the table rows with food names and attributes.
        var rows = document.querySelectorAll(".fd-row");

        //Variable used to format the table headers for food object attributes.
        var hdngs = document.querySelectorAll(".food-hdng");

        var objs = {};
        for( k=0; k<rows.length; k++ ){
            var elems = rows[k].querySelectorAll(".element");
            var food_name;

            //Loop that creates the object.
            for( j=0; j < elems.length; j++ ){

                //If the first instance is a food name the object will get it's name from here.
                if( j == 0 ) {
                    food_name = elems[j].innerHTML.toLowerCase().trim();
                    objs[food_name] = {};

                    //Else the object will retrieve it's specific values
                }else{
                    var name = hdngs[j].innerHTML.toLowerCase();
                    name = name.trim();
                    if( name.indexOf("portion") >= 0 ){ 
                        name = name.replace(" ", "-");
                        name = name.substr(0,name.length-3);
                    }
                    var val = elems[j].innerHTML;
                    objs[food_name][name] = val;
                }

            }
        }
        return objs;
    }

    displayAll();
    addFood();
    saveFood();
    deleteFood();
    selectedRow();

</script>
