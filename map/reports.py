#!/usr/bin/env python3

import csv
import requests
from io import StringIO
from fuzzywuzzy import fuzz
import json
from unidecode import unidecode
import sys
import time

KEY = '1WD3ojeEd-ll2T-xCXMda5UJrQhVxX6TgvEbEtkqL2J4'
SHEET = 'clean-export'
CSV_URL = f'https://docs.google.com/spreadsheets/d/{KEY}/gviz/tq?tqx=out:csv&sheet={SHEET}'

# print to stderr
def eprint(*args, **kwargs):
    print(*args, file=sys.stderr, **kwargs)

# load postos
def get_stations():
    with open('postos.json') as f:
        stations = json.load(f)
        return stations

# load reports
def get_reports():
    reports = []
    
    r = requests.get(CSV_URL)
    report_csv = r.text

    #with open('file.csv') as f:
    #    report_csv = f.read()

    # for each report try to find the proper gas station
    reader = csv.DictReader(StringIO(report_csv))
    for row in reader:
        reports.append({
            k: unidecode(v).strip() for k, v in row.items()
        })

    return reports

# fuzzy find station
def find_station(concelho, distrito, localidade, nome):
    scores = {}

    eprint(f"{distrito} > {concelho} {nome} - {localidade}")

    for id, meta in POSTOS.items():
        scores[id] = (
            20 * fuzz.token_set_ratio(distrito, meta['distrito']) + 
            10 * fuzz.token_set_ratio(concelho, meta['municipio']) + 
            5 * fuzz.token_set_ratio(f'{nome} {localidade}', meta['name'])) / 35

    ranking = sorted(scores.items(), key=lambda r: r[1], reverse=True)
    match = ranking[0]
    if match[1] > 95:
        eprint(f"Match ({match[1]}%)")
        #POSTOS[match[1]]

    #for row in ranking[:10]:
    #    p = POSTOS[row[0]]
    #    print(f'{row[1]}: {p["name"]}')
    #    print("--------")

# find report station
def find_station_reports(station, reports):
    scores = {}

    distrito = station['distrito']
    municipio = station['municipio']
    name = station['name']

    for i, r in enumerate(reports):
        # filter distrito
        if fuzz.ratio(r['Distrito'], distrito) < 95:
            continue

        # filter concelho
        if fuzz.ratio(r['Concelho'], municipio) < 95:
            continue

        scores[i] = fuzz.token_set_ratio(f"{r['Nome Posto Combustível']} {r['Localidade']}", name)

    # filtered
    matches = sorted(
        map(
            lambda r: (r[1], reports[r[0]]), 
            filter(
                lambda r: r[1] > 95,
                scores.items())),
        key=lambda r: r[0],
        reverse=True
    )

    matches = [
        {**{'certainty': certainty}, **data}
        for certainty, data in matches
    ]

    eprint(matches)

    return matches

if __name__ == "__main__":
    stations = get_stations()
    reports = get_reports()

    i = 0
    total = len(stations)

    for id, station in stations.items():
        eprint(f'{i} / {total}')
        i += 1

        matches = find_station_reports(station, reports)
        stations[id]['matches'] = matches

    print(json.dumps({
        'last_update': time.time(),
        'stations': stations
    }))
