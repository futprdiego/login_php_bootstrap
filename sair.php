<?php
/**
 * @author Glaubert Dumpierre
 * @since 15/08/2014
 */
session_start();

session_unset();

session_destroy();

header('Location: login.php');