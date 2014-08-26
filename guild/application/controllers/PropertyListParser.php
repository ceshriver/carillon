<?php


// Property List Parser
// Stephen Hall 2013

// Functions
//  - parsePropertyList(string pathToFile)
//			-> returns an array with the contents of the property list
//	- serializePropertyList(array propertyListArray)
//			-> returns a string in the format of a property list
//	- serializePropertyListToFile(array propertyList, string file)
//			-> writes a property list array to a file

class PropertyListParser {

	function parsePropertyList($pathToFile) {
		if (!file_exists ($pathToFile)) {
			return false;
		}
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
							
							if (array_key_exists("wasKey", $result) && $result['wasKey']) {
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
						$data .= $c;
					}
				} else if ($c == '/') {
						// This is going to be an end tag. Ignore it
						$lastChar = $tagContents[$i-1];
						if ($lastChar != '<') $data .= $c;
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

	function serializePropertyListToFile($propertyList, $file) {
		$listString = $this->serializePropertyList($propertyList);
		file_put_contents($file, $listString);
	}
	
	function wrapPropertyList($list) {
		return ($this->propertyListHeader()).$this->wrapStringWithParameters($list,"plist",array("version"=>"1.0"));
	}
	
	function propertyListHeader() {
		return '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">';
	}

	function serializePropertyList($propertyList) {
		return $this->wrapPropertyList($this->wrapObject($propertyList));	
	}
	function wrapObject($object) {
	
		$type = gettype($object);
		//echo "<pre>";
		//print_r($object);
		//echo "</pre>";
		//echo "Wrapping Object: $type <br>";
			
		if ($type == "boolean") {
			return $object?"<true/>":"<false/>";
		} else if ($type == "integer") {
			return $this->wrapString($object,"number");
		} else if ($type == "double") {
			return $this->wrapString($object,"number");
		} else if ($type == "string") {
			return $this->wrapString($object,"string");
		} else if ($type == "array" && !$this->isAssoc($object)) {
			//This is a standard Array
			//echo "ASSOC";
			
			$workingString = "";
			for ($i=0;$i<count($object);$i++) {
				
				//echo "<pre>";
				//print_r($object);
				//echo "</pre>";
				
				$wrapString = $this->wrapObject($object[$i]);
				$workingString = $workingString.$wrapString;
			}
			return $this->wrapString($workingString,"array");
		} else if ($type == "array" && $this->isAssoc($object)) {
			//echo "NOT ASSOC";
			//This is a "dictionary" keyed Array
			$workingString = "";
			foreach ($object as $key => $value) {
				$keystring = $this->wrapString($key, "key");
				//echo "keyString: $keystring<br>";
				//echo "object:";
				//echo "<pre>";
				//print_r($object);
				//echo "</pre>";
				
				$valueString = $this->wrapObject($object[$key]);
				$workingString = $workingString.$keystring.$valueString;
			}
			return $this->wrapString($workingString,"dict");
		} else if ($type == "object") {
			if (method_exists ($object , "serialize")) {
				return $object->serialize();
			} else if (method_exists ($object , "stringValue")) {
				return $this->wrapString($object->stringValue(), "string");
			} else if (method_exists ($object , "data")) {
				return $this->wrapString($object->data(),"data");
			} else {
				return $this->wrapString("object","string");
			}
		} else if ($type == "resource") {
			return $this->wrapString("resource","string");
		} else if ($type == "NULL") {
			return "";
		} else if ($type == "unknown type") {
			return $this->wrapString("unknown type","string");
		} else {
			return "";
		}
	}
	function wrapString($string, $tag) {
		return "<".$tag.">".$string."</".$tag.">\n";
	}
	
	//Same as the wrap string function but will add the parameters to the first tag
	function wrapStringWithParameters($string, $tag, $parameters) {
		$parameterString = "";
		foreach ($parameters as $key => $value) {
			$parameterString = $parameterString." ".$key.'="'.$value.'"';
		}
		return "<".$tag.$parameterString.">".$string."</".$tag.">";
	}
	
	function isAssoc($arr) {
    	return array_keys($arr) !== range(0, count($arr) - 1);
	}

}

?>