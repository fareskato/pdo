<?php
/**
 * exec : returns the number of affected rows : so USE it ALWAYS with insert and update and delete
 * query : returns the result as PDOStatement object so USE it ALWAYS with select
 */
require_once 'db.php';
require_once 'employee.php';

$message = '';
 if(isset($_POST['submit'])){
     // Filter data from inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $salary = filter_input(INPUT_POST, 'salary', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tax = filter_input(INPUT_POST, 'tax', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data = array(
         ':name' => $name,
         ':address' => $address,
         ':age' => $age,
         ':tax' => $tax,
         ':salary' => $salary,
     );
     if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])){
         $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
         $sql = "UPDATE employees SET name = :name, address = :address, age = :age, tax = :tax, salary = :salary WHERE id = :id";
         $data['id'] = $id;
     } else {
         $sql = "INSERT INTO employees SET name = :name, address = :address, age = :age, tax = :tax, salary = :salary";
     }
     $stmt = $connection->prepare($sql);

    // Insert and update data
    if($stmt->execute($data) === true){
        $message = 'Employees '. $name . ' has been saved successfully';
        header('Location: http://pdo.dev'); exit();
    } else {
        $message = 'Error';
    }
 }

    // Edit data
    if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = 'SELECT * FROM employees WHERE id= :id';
            $result = $connection->prepare($sql);
            $foundData = $result->execute(array(':id' => $id));
            if($foundData === true){
                $user = $result->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Employee', array('name', 'age', 'address', 'tax', 'salary'));
                $user = array_shift($user); // or $user = $user[0]
            }
        }
    }
    // DELETE data
    if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if($id > 0){
            $sql = 'DELETE FROM employees WHERE id= :id';
            $result = $connection->prepare($sql);
            $foundData = $result->execute(array(':id' => $id));
            if($foundData === true){
                header('Location: http://pdo.dev'); exit();
            }
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
                            <input type="text" name="name" class="form-control" id="name" placeholder="Employee name" value="<?= isset($user)? $user->name : '' ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="age" class="col-sm-3 control-label">Age</label>
                        <div class="col-sm-8">
                            <input type="number" name="age" class="form-control" id="age" value="<?= isset($user)? $user->age : '' ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-sm-3 control-label">Address</label>
                        <div class="col-sm-8">
                            <input type="text" name="address" class="form-control" id="name" placeholder="Employee address" value="<?= isset($user)? $user->address : '' ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="salary" class="col-sm-3 control-label">Salary</label>
                        <div class="col-sm-8">
                            <input type="number" name="salary" step="0.01" min="1500" max="9999" class="form-control" id="salary" value="<?= isset($user)? $user->salary : '' ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tax" class="col-sm-3 control-label">Tax</label>
                        <div class="col-sm-8">
                            <input type="number" name="tax" step="0.01" min="1" max="5" class="form-control" id="tax" value="<?= isset($user)? $user->tax : '' ?>"  required>
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
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(false !== $result): ?>
                        <?php foreach ($result as $employee): ?>
                            <tr>
                                <td><?php echo $employee->name ?></td>
                                <td><?php echo $employee->age ?></td>
                                <td><?php echo $employee->address ?></td>
                                <td><?php echo round($employee->calculateSalary()) ?></td>
                                <td><?php echo $employee->tax ?></td>
                                <td>
                                    <a href="/?action=edit&id=<?php echo $employee->id ?>"><span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span></a>
                                    <a href="/?action=delete&id=<?php echo $employee->id ?>"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                                </td>
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