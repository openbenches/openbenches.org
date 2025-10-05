# /// script
# dependencies = [
# 	"requests",
# ]
# ///

#	This Python script does the following:
#	Queries OpenStreetMap for all the nodes which have an *existing* OpenBenches ID.
#	Saves the resultant JSON.
#	Converts the JSON into a MySQL query to update existing benches with the osmID.
#	The output must be manually copied into MySQL. Sorry later me!
#	Run with:
#		uv run getOSM.py

import requests
import json

#	Prepare the request.
payload = {"data": '[out:json][timeout:25];nwr["openbenches:id"](-90,-180,90,180);out geom;'}

#	Send the request to OSM.
r = requests.post('https://overpass-api.de/api/interpreter', data=payload)

#	Turn it to data.
data = r.json()

#	Save it as a nicely formatted document.
file = open("OSM.json", "w")
file.write( json.dumps( json.loads( r.text ), indent=4) )
file.close()

#	OSM IDs can change. Benches can be removed. 
#	Reset all existing entries, then import them again.
print( "UPDATE `benches` SET `osmID` = NULL WHERE 1;" )

#	Read each item.
for element in data["elements"]:
	#	Is this a node?
	if element["type"] != "node":
		continue
	#	Set the ID.
	osmID = element["id"]
	#	Is the OpenBenches tag is present?
	if "tags" not in element:
		continue
	if "openbenches:id" not in element["tags"]:
		continue
	benchID = element["tags"]["openbenches:id"]
	#	Is it a numeric ID (some aren't!)
	if benchID.isdigit():
		#	Output the SQL syntax.
		print( f"UPDATE `benches` SET `osmID` = '{osmID}' WHERE `benches`.`benchID` = {benchID}; ")
		#	Some benches have duplicate nodes. Should we filter them out?
