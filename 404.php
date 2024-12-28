<?php
header("Location:http" . (!empty($_SERVER['HTTPS']) ? 's' : '') . "://" . $_SERVER['HTTP_HOST']);
exit();