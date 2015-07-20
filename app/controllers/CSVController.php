<?php

use League\Csv\Reader;

/**
* Handles the uploading and parsing the CSV files sent to the application. 
* CSVController is responsible extracting and cleaning all the data in the CSV file and 
* allows for a dynamic number of header columns. 
*/

class CSVController extends BaseController {

	/**
	* Take in our CSV, save it to a folder on the server, then open and extract headers
	* Each header "cell" represents a column. We then present user with ability to match datatypes to columns
	* @return View defineHeaders
	*/
	public function processCSV()
	{
		$data  = Input::all();
		$rules = array(
			'csvFile' => 'required|mimes:csv'
		);

		$validator = Validator::make($data, $rules);

		if($validator->fails())
		{
			return Redirect::to('/')->withErrors($validator);
		}

		// Copy the CSV file to permanent holding location
		$file            = Input::file('csvFile');
		$destinationPath = public_path().'/csv/';
		$fileName        = str_random(8).'_'.$file->getClientOriginalName();

		$file->move($destinationPath, $fileName);

		// Create Reader instance to parse data from CSV
		$inputCSV = Reader::createFromPath($destinationPath.$fileName);
		$inputCSV->setDelimiter(',');

		//get headers, assuming they are the first row
		$headers = $inputCSV->fetchOne(0);

		//define data types for headers
		$dataTypes = array(
			'city',
			'state',
			'zip',
			'address',
			'category'
		);

		return View::make('defineHeaders')->with('headers', $headers)->with('dataTypes', $dataTypes)->with('filePath', $destinationPath.$fileName);
				

	}


	/**
	* Parses each row of the CSV file and then extracts addresses, groups by category, and GeoCodes address data.
	* Sends GeoCoded coordinates to mapview.blade View where the Maps API v3 places markers at those locations
	* @return View
	*/
	public function processHeaders()
	{
		//flash old input to session in case of errors
		Input::flashOnly('filePath');

		//setup holding array for locations ordered by category
		$groupedLocations = array();
		// Create $locations array to hold address + zip of each row
		$locations = array();

		// Check if all values in the input array only appear once
		$data = array_count_values(Input::all());

		foreach($data as $key => $value)
		{
			if($value != 1)
			{
				return Redirect::to('/')->withInput()->with('errorMsg', 'Multiple headers cannot reference the same column.');
			}
		}


		//our data is now the keys (integers) of each column. This is a 0-based column index
		$inputCSV = Reader::createFromPath(urldecode(Input::get('filePath')));
		$inputCSV->setDelimiter(',');

		$validRows = $inputCSV->addFilter(function ($row, $index){
			return $index > 0; //ignore headers
		})
		->addFilter(function ($row){
			return isset($row[Input::get('zip')], $row[Input::get('address')], $row[Input::get('category')]); //only get rows where zip, addr, and category are filled
		})
		->fetchAll();


		// Loop through fetched rows from CSV
		for($i = 0; $i < sizeof($validRows); $i++)
		{
			//Add addresses to $locations array formatted as: 555+Address+St+ZIPCODE
			$locations[] = array(
									'addrMarker' => urlencode($validRows[$i][Input::get('address')]." ".$validRows[$i][Input::get('zip')]),
									'category'   => $validRows[$i][Input::get('category')]
								);
		}

		// Get geocoded coordinates for each entry in $location
		foreach($locations as $location)
		{
			$geocodedLoc = Geocoder::geocode('json', array('address' => $location['addrMarker']));

			// Hold Geocoded coordinates
			$tempLatLang = json_decode($geocodedLoc, $assoc = true);

			// Build grouped array based on category data
			// Create new subarray for each unique category
			if(!isset($groupedLocations[$location['category']]))
			{
				$groupedLocations[$location['category']] = array();
			}

			$groupedLocations[$location['category']][] = array(
																'lat'      => $tempLatLang['results'][0]['geometry']['location']['lat'],
																'lng'      => $tempLatLang['results'][0]['geometry']['location']['lng'],
																'category' => $location['category']
						  									); 
		}

		return View::make('mapview')->with('mapDataPts', $groupedLocations);

	}

}