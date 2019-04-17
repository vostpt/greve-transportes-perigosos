from io import StringIO
import xml.etree.ElementTree as ET
import json
from shapely.geometry import mapping, shape, Point

DATA_DIR = "data"
TYPES = {
	"95_SIMPLES": "1",
	"95_ADIT": "2",
    "98_SIMPLES": "3",
    "98_ADIT": "4",
    "GASOLEO_SIMPLES": "5",
    "GASOLEO_ADIT": "6",
    "GPL": "7"
}

# strips namespaces from xml
def strip_namespaces(xml):
    it = ET.iterparse(StringIO(xml))
    for _, el in it:
        if '}' in el.tag:
            el.tag = el.tag.split('}', 1)[1]  # strip all namespaces

    return it.root

# populate stations with data
def get_stations():
    stations = {}
    concelhos = []

    # get areas
    with open(f'{DATA_DIR}/concelhos.json') as f:
        data = f.read()
    concelhos_json = json.loads(data)
    for feat in concelhos_json['features']:
        concelhos.append({
            "properties": feat['properties'],
            "shape": shape(feat['geometry'])
        })

    # get station data
    with open(f'{DATA_DIR}/earth-all.kml') as f:
        data = f.read()
    root = strip_namespaces(data)
    placemarks = root.findall('.//*/Placemark')

    for p in placemarks:
        id = p.attrib['id']
        name = p.find('name').text.strip()
        lng, lat = p.find('Point').find('coordinates').text.split(',')
        lat = float(lat)
        lng = float(lng)
        municipio = None
        distrito = None

        point = Point(lng, lat)
        for c in concelhos:
            if c['shape'].contains(point):
                props = c['properties']
                municipio = props['municipio']
                distrito = props['distrito']
                break

        stations[id] = {
            'name': name,
            'latitude': lat,
            'longitude': lng,
            'types': [],
            'municipio': municipio,
            'distrito': distrito
        }

    return stations

# populates stations with available fuels
def populate_fuels(stations):
    # get available fuels per station
    for typ, id in TYPES.items():
        with open(f'{DATA_DIR}/maps-{id}.kml') as f:
            data = f.read()

        root = strip_namespaces(data)
        #tree = ET.parse(FN % id)
        #root = tree.getroot()

        placemarks = root.find('Document').findall('Placemark')

        for p in placemarks:
            id = p.find('description').text
            stations[id]['types'].append(typ)

# main
if __name__ == "__main__":
    stations = get_stations()
    populate_fuels(stations)

    print(json.dumps(stations, indent=2))
