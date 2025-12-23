#!/bin/bash
: 1. paramter a kisbetus 2. Nagybetus

rm js/*
rm Entities/*
rm Controllers/*
rm tpl/default/*

cp Howto.php Entities/$2.php
cp howtoJQGridController.php Controllers/$1Controller.php
cp HowtoRepository.php Entities/$2Repository.php