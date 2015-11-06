[![Build Status](https://travis-ci.org/mixmasteru/ha_api.svg?branch=master)](https://travis-ci.org/mixmasteru/ha_api)
[![Coverage Status](https://coveralls.io/repos/mixmasteru/ha_api/badge.svg?branch=master&service=github)](https://coveralls.io/github/mixmasteru/ha_api?branch=master)
[![codecov.io](https://codecov.io/github/mixmasteru/ha_api/coverage.svg?branch=master)](https://codecov.io/github/mixmasteru/ha_api?branch=master)
[![Code Climate](https://codeclimate.com/github/mixmasteru/ha_api/badges/gpa.svg)](https://codeclimate.com/github/mixmasteru/ha_api)
![codecov.io](https://codecov.io/github/mixmasteru/ha_api/branch.svg?branch=master)
# ha_api
RESTful API for home automation
store and read home automation data

## endpoints
* temperatur
* humidity (WIP)
* power consumtion (TBD)

## dependencies
* php 5.6
* mysql
* [Tonic Framework](https://github.com/peej/tonic)

## Installation
* create mysql data
* run init.sql from sql folder
* enter credetials in web/dispatch.php
* run composer install

## To Do
* deployment automation
* non http testing (with coverage merge)
* device endpoint
* env based config
