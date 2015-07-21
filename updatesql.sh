#!/bin/bash

php vendor/bin/doctrine orm:schema-tool:update --dump-sql >update.sql