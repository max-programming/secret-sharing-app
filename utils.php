<?php
function check_empty_value($value, $name, $page)
{
    if (empty(trim($value)) || $value == null) {
        echo "<script>alert('$name is required!'); window.location.href='$page.php';</script>";
        exit;
    }
}
function validate_email($email, $page)
{
    if (!preg_match("/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/", $email)) {
        echo "<script>alert('Invalid email format!'); window.location.href='$page.php';</script>";
        exit;
    }

}