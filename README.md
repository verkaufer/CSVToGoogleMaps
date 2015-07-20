# CSVToGoogleMaps
Exercise to categorize and map CSV data on a Google Maps instance

## Purpose
Built as a coding exercise in October 2014 to take in a `.csv` file with address, city, state, ZIP, and category fields. Laravel then processes the data and displays a Google Maps instance with markers placed on the locations defined in the CSV file. 

Markers are color-coded based on category (e.g. If two rows are in the 'Retail' category, they would both have the same color map markers.)

After uploading the CSV file, users are able to correlate the address, city, state, ZIP, and category fields with a column index to make processing more flexible. 

## Example CSV Structure


| Address   | City |  State |  ZIP |  Category |
|----------|-------------|------|-----|-----|
| 155 Main St |  Atlanta | GA | 30309 | Retail | 
| 221B Baker St | Atlanta | GA | 30309 | Retail |
| 255 Corner Ave | Flower Mound | TX | 75028 | Restaurant |