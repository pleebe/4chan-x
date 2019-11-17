#!/bin/bash
#make clean
rm testbuilds/*
make
cp testbuilds/4plebs-X.site.js backend_templates/test.js
