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
//
//	Modified 2018, Terence Eden

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
	var file = $('#fileform [name=userfile1]')[0].files[0];
	// console.log(file.size);

	if (file.size > 4000000) {
		// alert("Photo is too large to run text detection. 4MB is the maximum file size. Please use a smaller photo or type the inscription manually.");
		
		//	Send the lower resolution canvas image instead
		var canvases = document.getElementsByTagName("canvas");
		var canvas = canvases[0];

		canvas.toBlob(function(blob) { 
			var reader = new window.FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function() {
				var base64data = reader.result;
				sendFileToCloudVision(
					base64data.replace('data:image/jpeg;base64,', '')
				);
			}	
		}, 'image/jpeg', 0.75);
		
	} else {
		//	Hide the detect button
		$('#textButtons').hide();
		
		var reader = new FileReader();
		reader.onloadend = processFile;
		reader.readAsDataURL(file);
	}
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

	$('#message').text('Automatic text detection in progress…');
	$.post({
		url: CV_URL,
		data: JSON.stringify(request),
		contentType: 'application/json'
	}).fail(function (jqXHR, textStatus, errorThrown) {
		$('#message').text('Automatic text detection encountered an error:\n ' + textStatus + ' ' + errorThrown + '.\nSorry about that. Please enter the inscription yourself.');
	 //	Unhide the inscription box
	 $('#inscription-hidden').show();
	 $('#submitButton').show();
	}).done(displayJSON);
	//setTimeout( function() { displayJSON(JSON.parse('{  "responses": [  { "fullTextAnnotation": { "text": "BUFFY ANNE SUMMERS\\n1981 - 2001\\nBELOVED SISTER\\nDEVOTED FRIEND\\nSHE SAVED THE WORLD\\nA LOT" } }] }')); }, 5000);
}

/**
 * Displays the results.
 */
function displayJSON (data) {
	//  Tell visitor the automatic text detection finished 
	$('#message').text('Automatically detected text is shown below.\nPlease check and edit if needed.');
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
