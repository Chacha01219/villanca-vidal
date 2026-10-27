# From model to code

1. Which file is the model, and why is it platform independent?

The model is `app/PIM/customer_order.json`. It describes the CustomerOrder concept with six plain fields
(customerName, itemName, quantity, totalPrice, isPaid, orderedAt) using everyday types such as text,
wholenumber, decimal, yesno, and datetime, and it names no database word like VARCHAR or INT and no
framework, so the same file can be read by any target.

2. Which files were generated from it?

For the target ci4 the command wrote the migration `app/Database/Migrations/2026-10-27-150317_CreateCustomerOrderTable.php`
and the model class `app/Models/CustomerOrderModel.php`; for the target sql it wrote
`writable/generated/customer_orders.sql`. All three were produced by `php spark model:generate`
reading only the one model file.

3. Which mapping rule turned a text field into a column, and what did it become?

In the `$ci4Types` list of `app/Commands/ModelGenerate.php`, the rule `'text' => ['VARCHAR', 255]` turned the
model field customerName into the column `customer_name` of type VARCHAR(255), and because its required is
true the column line ends with `'null' => false`.

4. What would you change to produce code for a different platform?

I would add a third mapping list next to `$ci4Types` and `$sqlTypes` in `app/Commands/ModelGenerate.php`
that says what each model type means on that platform, plus a matching branch in `run()` that writes the
new kind of file, and the model file itself would not change at all.
