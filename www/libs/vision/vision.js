// Copyright 2015, Google, Inc.
// Licensed under the Apache License, Version 2.0 (the "License")
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//		http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

'use strict';

var CV_URL = 'https://vision.googleapis.com/v1/images:annotate?key=' + window.apiKey;

$(function () {
	// $('#fileform').on('submit', uploadFiles);
	$('#detectButton').click(uploadFiles);
	
	$( "#typeButton" ).click(function() {
		$('#textButtons').hide();
		//	Unhide the inscription box
		$('#inscription-hidden').show();
		$('#submitButton').show();
	});
});



/**
 * 'submit' event handler - reads the image bytes and sends it to the Cloud
 * Vision API.
 */
function uploadFiles (event) {
	// event.preventDefault(); // Prevent the default form post
	
	//	Hide the detect button
	$('#textButtons').hide();

	// Grab the file and asynchronously convert to base64.
	var file = $('#fileform [name=userfile1]')[0].files[0];
	var reader = new FileReader();
	reader.onloadend = processFile;
	reader.readAsDataURL(file);
}

/**
 * Event handler for a file's data url - extract the image data and pass it off.
 */
function processFile (event) {
	var content = event.target.result;
	sendFileToCloudVision(content.replace('data:image/jpeg;base64,', ''));
}

/**
 * Sends the given file contents to the Cloud Vision API and outputs the
 * results.
 */
function sendFileToCloudVision (content) {
	// Strip out the file prefix when you convert to json.
	var request = {
		requests: [{
			image: {
				content: content
			},
			features: [{
				type: 'TEXT_DETECTION'
			}]
		}]
	};

	$('#message').text('Scanning for text...');
	$.post({
		url: CV_URL,
		data: JSON.stringify(request),
		contentType: 'application/json'
	}).fail(function (jqXHR, textStatus, errorThrown) {
		$('#message').text('ERRORS: ' + textStatus + ' ' + errorThrown);
	 //	Unhide the inscription box
	 $('#inscription-hidden').show();
	 $('#submitButton').show();
	}).done(displayJSON);
}

/**
 * Displays the results.
 */
function displayJSON (data) {
	//	Hide the scanning message
	$('#message').hide();
	//	Unhide the inscription box
	$('#inscription-hidden').show();
	$('#submitButton').show();
	//	Get the text
	var contents = data.responses[0].fullTextAnnotation.text;
	//	Add the detected inscription
	$('#inscription').val(contents);

	var evt = new Event('results-displayed');
	evt.results = contents;
	document.dispatchEvent(evt);
}
