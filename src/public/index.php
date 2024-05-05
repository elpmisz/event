<?php
require_once(__DIR__ . "/vendor/autoload.php");

$ROUTER = new AltoRouter();

##################### SERVICE #####################
################### EVENT ###################
$ROUTER->map("GET", "/event", function () {
  require(__DIR__ . "/src/Views/event/index.php");
});
$ROUTER->map("GET", "/event/create", function () {
  require(__DIR__ . "/src/Views/event/create.php");
});
$ROUTER->map("GET", "/event/export", function () {
  require(__DIR__ . "/src/Views/event/export.php");
});
$ROUTER->map("GET", "/event/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/event/edit.php");
});
$ROUTER->map("POST", "/event/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/event/action.php");
});

##################### SETTING #####################
################### CUSTOMER ###################
$ROUTER->map("GET", "/customer", function () {
  require(__DIR__ . "/src/Views/customer/index.php");
});
$ROUTER->map("GET", "/customer/create", function () {
  require(__DIR__ . "/src/Views/customer/create.php");
});
$ROUTER->map("GET", "/customer/export", function () {
  require(__DIR__ . "/src/Views/customer/export.php");
});
$ROUTER->map("GET", "/customer/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/customer/edit.php");
});
$ROUTER->map("POST", "/customer/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/customer/action.php");
});
################### CUSTOMER-TYPE ###################
$ROUTER->map("GET", "/customer-type", function () {
  require(__DIR__ . "/src/Views/customer-type/index.php");
});
$ROUTER->map("GET", "/customer-type/create", function () {
  require(__DIR__ . "/src/Views/customer-type/create.php");
});
$ROUTER->map("GET", "/customer-type/export", function () {
  require(__DIR__ . "/src/Views/customer-type/export.php");
});
$ROUTER->map("GET", "/customer-type/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/customer-type/edit.php");
});
$ROUTER->map("POST", "/customer-type/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/customer-type/action.php");
});
################### COUNTRY ###################
$ROUTER->map("GET", "/country", function () {
  require(__DIR__ . "/src/Views/country/index.php");
});
$ROUTER->map("GET", "/country/create", function () {
  require(__DIR__ . "/src/Views/country/create.php");
});
$ROUTER->map("GET", "/country/export", function () {
  require(__DIR__ . "/src/Views/country/export.php");
});
$ROUTER->map("GET", "/country/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/country/edit.php");
});
$ROUTER->map("POST", "/country/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/country/action.php");
});


##################### SETTING #####################
###################################################
$ROUTER->map("GET", "/system", function () {
  require(__DIR__ . "/src/Views/system/index.php");
});
$ROUTER->map("POST", "/system/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/system/action.php");
});

#####################
$ROUTER->map("GET", "/user", function () {
  require(__DIR__ . "/src/Views/user/index.php");
});
$ROUTER->map("GET", "/user/create", function () {
  require(__DIR__ . "/src/Views/user/create.php");
});
$ROUTER->map("GET", "/user/profile", function () {
  require(__DIR__ . "/src/Views/user/profile.php");
});
$ROUTER->map("GET", "/user/change", function () {
  require(__DIR__ . "/src/Views/user/change.php");
});
$ROUTER->map("GET", "/user/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/user/edit.php");
});
$ROUTER->map("POST", "/user/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/user/action.php");
});


##################### AUTH #####################
###################################################
$ROUTER->map("GET", "/", function () {
  require(__DIR__ . "/src/Views/home/login.php");
});
$ROUTER->map("GET", "/home", function () {
  require(__DIR__ . "/src/Views/home/index.php");
});
$ROUTER->map("GET", "/error", function () {
  require(__DIR__ . "/src/Views/home/error.php");
});
$ROUTER->map("POST", "/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/home/action.php");
});
$ROUTER->map("GET", "/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/home/action.php");
});


$MATCH = $ROUTER->match();

if (is_array($MATCH) && is_callable($MATCH["target"])) {
  call_user_func_array($MATCH["target"], $MATCH["params"]);
} else {
  header("HTTP/1.1 404 Not Found");
  require_once(__DIR__ . "/src/Views/home/error.php");
}
