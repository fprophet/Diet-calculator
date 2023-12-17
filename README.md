# Diet calculator
 THE CALCULATOR - client side
This is a fitness diet calculator that works on a simple principle. First choose the day you want to program your meals. When adding a new meal from the dropdown menus and the Portion input is set the program will automatically fill in the rest of the row cells with the specific food values taken from the .json file.
Portion size, carbs, proteins, fats and calories are calculated using the formula x*portion where x will be one of the elements specified earlier.

Hitting the plus button will add another food row for the specific meal. Breakfast and Lunch have a limit of maximum 5 meals while dinner has only 4.

Pressing the "Calculate" button will take all the entries in the table, calculate and the result will be displayed in the total row.
The "Save" button will retrieve the total values and place them in the day table for the selected day.

The "Clear fields" button will clear all the calculator fields.

THE FOOD TABLE - admin side
It displays the current food list stored in the .json file.

To add an item to the list use the input fields at the start of the table and hit "Add to list".
If you want to delete an entry, check the box of the specific row and hit "Delete".
