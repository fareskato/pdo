<?php

class Employee
{
    private $id;
    private  $name;
    private  $age;
    private  $address;
    private  $tax;
    private  $salary;

    public function __construct($name, $age, $address, $tax, $salary)
    {
        $this->name = $name;
        $this->age = $age;
        $this->address = $address;
        $this->tax = $tax;
        $this->salary = $salary;
    }

    /**
     * To access private attributes
     * @param $prop
     * @return mixed
     */
    public function __get($prop)
    {
        return $this->$prop;
    }

    /**
     * @return mixed
     */
    public function calculateSalary()
    {
        return $this->salary - ($this->salary * $this->tax / 100);
    }
}