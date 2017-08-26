<?php
/**
 * exec : returns the number of affected rows : so USE it ALWAYS with insert and update and delete
 * query : returns the result as PDOStatement object so USE it ALWAYS with select
 */
require_once 'db.php';
require_once 'employee.php';

$message = '';
 if(isset($_POST['submit'])){
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $salary = filter_input(INPUT_POST, 'salary', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tax = filter_input(INPUT_POST, 'tax', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $employee = new Employee($name, $age, $address, $tax, $salary);
     /**
      * Inserting Data
      */
     $sql = "INSERT INTO employees SET name='". $name .
            "',address='". $address ."',age='". $age ."',salary='".$salary."', tax='".$tax."'";

    if($connection->exec($sql)){
        $message = 'Employees '. $name . ' has been inserted successfully';
    } else {
        $message = 'Error';
    }
 }
    /**
     * Reading data
     */
    $sql = "SELECT * FROM employees";
    $stmt = $connection->query($sql);
    // ORM
    $objConstructorArgs = array('name', 'age', 'address', 'tax', 'salary');
    $result = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Employee', $objConstructorArgs);
    $result = (is_array($result) && !empty($result)) ? $result : false;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>pdo</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"

    </head>
    <body>
    <div class="container" style="padding-top: 50px">
        <div class="row">
            <div class="col-lg-6" style="border-right:solid 2px #dddddd ">
                <?php echo $message; ?>
                <form class="form-horizontal" method="post" enctype="application/x-www-form-urlencoded">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Employee name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="age" class="col-sm-3 control-label">Age</label>
                        <div class="col-sm-8">
                            <input type="number" name="age" class="form-control" id="age" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-sm-3 control-label">Address</label>
                        <div class="col-sm-8">
                            <input type="text" name="address" class="form-control" id="name" placeholder="Employee address" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="salary" class="col-sm-3 control-label">Salary</label>
                        <div class="col-sm-8">
                            <input type="number" name="salary" step="0.01" min="1500" max="9999" class="form-control" id="salary" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tax" class="col-sm-3 control-label">Tax</label>
                        <div class="col-sm-8">
                            <input type="number" name="tax" step="0.01" min="1" max="5" class="form-control" id="tax"  required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Salary</th>
                        <th>Tax (%)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(false !== $result): ?>
                        <?php foreach ($result as $employee): ?>
                            <tr>
                                <td><?php echo $employee->name ?></td>
                                <td><?php echo $employee->age ?></td>
                                <td><?php echo $employee->address ?></td>
                                <td><?php echo $employee->calculateSalary() ?></td>
                                <td><?php echo $employee->tax ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <td colspan="5"><p class="text-center">No data to list</p></td>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </body>
</html>