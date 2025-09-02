<?php
function check_empty_value($value, $name, $page)
{
    if (empty(trim($value)) || $value == null) {
        echo "<script>alert('$name is required!'); window.location.href='$page.php';</script>";
        exit;
    }
}
