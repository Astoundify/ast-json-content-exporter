#!/bin/bash

# set -e;
# cd "$(dirname "$0")";
# cd ..;
rm -rf build;
mkdir build;
cd ..;

rsync -arvq --exclude-from=./ast-content-exporter/.buildexcludes ast-content-exporter/ ast-content-exporter/build/ast-content-exporter/

cd ast-content-exporter/build

# status "Packaging..."

ls -al;

zip -rq "ast-content-exporter.zip" ./ast-content-exporter

rm -rf ./ast-content-exporter


