#!/bin/bash

cd "$(dirname "$0")"

python3 reports.py > new.json && mv -f new.json postos-reports.json
