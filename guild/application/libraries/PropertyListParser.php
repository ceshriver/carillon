<?php


class PropertyListParser {




	function parsePropertyList($pathToFile) {
		$file = file_get_contents($pathToFile, FILE_USE_INCLUDE_PATH);
		$tagContents = $file;
		$propertyArray = $this->parseTag($tagContents,0);
		$object = $propertyArray['object'];
		return $object;
	}
	
	
	function parseTag($tagContents, $startIndex) {
		
		$contentLength = strlen($tagContents);
		//Loop over the string's contents
		
		$currentStartTag = '';
		$currentEndTag = '';
		
		$hasTagStart = 0;
		$hasStartTagName = 0;
		$hasEndStart = 0;
		$hasEndTagName = 0;
		
		$workingObjectType = '';
		$workingDictionaryKey = '';
		$workingObject = 0;
		
		
		for ($i=$startIndex;$i<$contentLength;$i++) {
			
			
			
			$c = $tagContents[$i];
			
			//echo "i: $i<br>";
			
				if ($c == '<') {
				// We need to peek ahead and see if the next character is an '/'
				// This means we have an ending tag!
					$c2 = $tagContents[$i+1];
					//echo "Checkin next character: $c2<br>";
					if ($c2 == '/') {
						//This is going to be an ending tag.
						$hasEndStart = 1; 
						//echo "Found / Character <br>";
					} else {
						//This is another start tag...
						//Call this function recursively with the new file position
						//echo "Recursion... <br>";
						$result = $this->parseTag($tagContents, $i+1);
						$i = $result['endIndex'];
						$object = $result['object'];
						
						if ($currentStartTag == "array" || $currentStartTag == "plist") {
							if (!$workingObject) $workingObject = array();
							$workingObject[count($workingObject)] = $object;
							//echo "Parsed an Array:";
							//var_dump($workingObject);
							//echo" <br>";
						} else if ($currentStartTag == "dict") {
							
							if ($result['wasKey']) {
								//echo "Parsed a Dictionary Key: $object <br>";
								$workingDictionaryKey = $object;							
							} else {
								if (!$workingObject) $workingObject = array();
								$workingObject[$workingDictionaryKey] = $object;
							}
							//echo "Parsed a Dictionary Element <br>";
						} else {
							//date,number,string,array,dict
							$workingObject = $object;
							//echo "Parsed a(n) '$currentStartTag'<br>";
							//var_dump($object);echo "<br>";
						}
					}
				} else if ($c == '>') {
					if ($hasEndStart) {
						
						//echo "Found End Tag Character <br>";
						
						//This is the end of the tag!
						//The 'data' now contains the value of our object
						//we need to process
						if ($currentStartTag == $currentEndTag) {
							//Okay, data is a full object
							/*
							echo "Start Tag: $currentStartTag";
							echo "<br>";
							echo "End Tag: $currentEndTag";
							echo "<br>";
							echo "Data Tag: $data";
							echo "<br>";
							*/
							
							if ($currentStartTag == 'key') $result['wasKey'] = 1;
							
							if ($currentStartTag != "array" && $currentStartTag != "dict" && $currentStartTag != "plist") {
								$result['object'] = $data;
							} else {
								$result['object'] = $workingObject;
							}
							break;
						}
					} else {
						//This is the end of a start tag....
						//echo "End Start Tag has start:$hasTagStart hastaghame:$hasStartTagName tagname: <br>";
						if (!$hasTagStart && !$hasStartTagName) {
							// This tag must have some properties (version, etc)
							// Accept the start
							//$currentStartTag = $data;
							//echo "Current Tag Name: $currentStartTag <br>";
							$data = '';
							$hasStartTagName = 1;
							$hasTagStart = 1;
						} else {
							//We have a tag, so just add the spaces to the data
						}
					}
				} else if ($c == ' ') {
					//echo "Found Space Character <br>";
					if (!$hasTagStart) {
						// This tag must have some properties (version, etc)
						// Accept the start
						$data = '';
						$hasStartTagName = 1;
					} else {
						//We have a tag, so just add the spaces to the data
					}
				} else if ($c == '/') {
						// This is going to be an end tag. Ignore it
				} else {
						if (!$hasTagStart && !$hasStartTagName) {
							$currentStartTag .= $c;
							//echo "Adding to Start Tag: $c  making $currentStartTag<br>";
						} else if ($hasEndStart) {
							//echo "Adding to End Tag: $c <br>";
							$currentEndTag .= $c;
						} else {
							//echo "Adding to Data: $c <br>";
							$data .= $c;
						}
				}
		}

		//echo "Recusion Done <br>";
		$result['endIndex'] = $i;
		return $result;
	}


}


?>