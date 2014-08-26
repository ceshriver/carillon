var kSidebarDefaultSize = 180;
var kMainContentDefaultSize = 730;
var sidebarVisible = false;

function setSidebarVisible(vis, anim,complete) {
	var sidebar = $("#sidebar-left");
	var content = $("#content");
	if (anim) {
	var comp = function () {sidebarVisible = vis;if (complete) complete();};
		sidebar.animate({width:vis?kSidebarDefaultSize:0}, {
		duration: 300,
		complete:comp,
		step:function (now,fx) {
			content.width(kMainContentDefaultSize - now);
		}});
	} else {
		sidebar.width(vis?kSidebarDefaultSize:0);
		content.width(vis?kMainContentSize:kMainContentDefaultSize-kSidebarDefaultSize);
		sidebarVisible = vis;
		//content.
	}
}



/* Utility Functions */

function go(s) {
	window.location = baseURL + s;
}

/* End Utility Functions */





/* Begin Library Functions */

function selectLibraryEntry(id) {
	window.location = baseURL + "library/entries/" + id;
}

var kLibrarySearchTimer = false;
var LIBRARY_SEARCH_STRING = '';
var LIBRARY_IS_SEARCHING = false;
function performLibrarySearch(query) {
	LIBRARY_SEARCH_STRING = query;
	if (kLibrarySearchTimer) {clearTimeout(kLibrarySearchTimer); kLibrarySearchTimer = false;}
	kLibrarySearchTimer = setTimeout(function () {librarySearch(LIBRARY_SEARCH_STRING)},60);
}

function librarySearch(query) {
	
	/*
	if (LIBRARY_IS_SEARCHING) {
	    //Invalidate the current request
	    LIBRARY_IS_SEARCHING.abort();
	    console.log("Aborting Search");
	}
	*/
	
	//var rowData = [{title:"Test"},{title:"New Piece", composer:"Stephen", year:"1909"}];
	var proxy = $("#libraryRowProxy").clone();
	var table = $("#libraryEntries");
	
	var complete = function (rowData) { 
		//Clear rows
        //if (!rowData) {
        //    console.log("Library Search Returned Null");
        //    return;
        //}
		//console.log(rowData);
		
		table.html("");
		proxy.css('opacity',1);
		for (var i=0;i<rowData.length;i++) {
			var newRow = proxy.clone();			
			loadLibraryRowWithData(newRow,rowData[i]);
			
			//Re-bind click handler
			
			newRow.attr('tag',rowData[i].id);
			
			newRow.removeClass("lightRow");
			if (i%2==1) newRow.addClass("lightRow");
			
			table.append(newRow);
		}
		if (rowData.length == 0) {
			proxy.css('opacity',0);
			table.append(proxy);
		}
		
		
		var resultString = rowData.length + " Result" + (rowData.length==1?"":"s");
		if (query == "") resultString = rowData.length + " Record" + (rowData.length==1?"":"s");
		$("#libraryEntryCount").html(resultString);
		
	}
	
	if (query == "") {
		window.location=window.location;	
	} else {
		AJAXQueryPostJSON("library",["search"],{"query":query},complete,false,false,false);	
	}
	
}

function loadLibraryRowWithData(row,data) {
	row.find(".library_data_title").html(data.title);
	row.find(".library_data_composer").html(data.composer);
	row.find(".library_data_genre").html(data.genre);
	row.find(".library_data_year").html(data.year);
	
	
	row.find(".library_data_collection").html(data.collection);
	row.find(".library_data_arranger").html(data.arranger);
	
}
function libraryRowData(row) {
	var title = row.find(".library_data_title").html();
	var composer = row.find(".library_data_composer").html();
	var genre = row.find(".library_data_genre").html();
	var year = row.find(".library_data_year").html();
	return {title:title, composer:composer, genre:genre, year:year};
}

/* End Libarary Functions */



/* Guildies Functions */


var kSearchLimit = 5;
function searchForPieces(query) {
	var proxy = $("#guildies_play_proxy_row").clone();
	var searchRow = $("#guildies_play_search_row");
	var table = $("#guildies_play_table");
	
	
	if (query == "") {
		table.children("tbody").children("tr").not(".guildies_play_permanent").remove();
		return;
	}
	
	var complete = function (rowData) { 
		
		//Clear rows
		table.children("tbody").children("tr").not(".guildies_play_permanent").remove();
		
		for (var i=0;i<rowData.length;i++) {
			var newRow = proxy.clone();
			
			
			
			newRow.attr("tag", rowData[i].id);
			if (guildiePlaysPiece(newRow)) continue;
			
			loadLibraryRowWithData(newRow,rowData[i]);
			libraryBindRow(newRow);
			
			
			newRow.css("display","");
			
			newRow.removeClass("lightRow");
			if (i%2==1) newRow.addClass("lightRow");
			newRow.removeClass("guildies_play_permanent");
			newRow.attr("id","");
			
			table.append(newRow);
		
			var style = kLibraryRowAdd;
			setLibraryRowStyle(newRow, style);
			
		}
		
		updateFormPieces();
	}
	
	AJAXQueryPostJSON("library",["search"],{"limit":kSearchLimit,"query":query},complete,false,false,false);
}

function guildiePlaysPiece(row) {
	var rows = $("#guildies_play_table").find(".guildies_play_permanent");
	
	for (var i=0;i<rows.length;i++) {
		if (rows.eq(i).attr('tag') == row.attr('tag') ) return true;
	}
	return false;
}

var kLibraryRowAdd = 0;
var kLibraryRowRemove = 1;
var kLibraryRowSelected = 2;

function setLibraryRowStyle(row, style) {
	var add = row.find(".guildies_play_add");
	
	row.removeClass("guildies_play_add_selected");
	row.removeClass("guildies_play_add_remove");
	
	add.removeClass("guildies_play_add_selected");
	add.removeClass("guildies_play_add_remove");
	
	switch(style) {
		case kLibraryRowAdd:
			add.html("+");
			break;
		case kLibraryRowSelected:
			row.addClass("guildies_play_selected");
			add.addClass("guildies_play_add_selected");
			add.html("&#x2713;");	
			break;
		case kLibraryRowRemove:
			row.addClass("guildies_play_remove");
			add.addClass("guildies_play_add_remove");
			add.html("&#x2717;");
			break;
	}
}

function libraryRowHover(row,enter) {
	if (guildiePlaysPiece(row)) {
		if (enter) {
			setLibraryRowStyle(row, kLibraryRowRemove);
		} else {
			setLibraryRowStyle(row, kLibraryRowSelected);
		}
	}
}

function guildieAddPiece(row) {
	
	var tag = row.attr("tag");
	var table = $("#guildies_play_table");
	var searchRow = $("#guildies_play_search_row");
	
	if (!guildiePlaysPiece(row)) {
		searchRow.after(row);
		libraryBindRow(row);
		row.addClass("guildies_play_permanent");
		setLibraryRowStyle(row,kLibraryRowSelected);
	} else {
		row.remove();
	}
	updateFormPieces();
}

function libraryBindRow(row) {
	var add = row.find(".guildies_play_add");
	add.data('row',row);
	add.unbind().bind('click', function () {guildieAddPiece($(this).data('row'));})
	.bind('mouseover',function () {libraryRowHover($(this).data('row'),true)})
	.bind('mouseout',function () {libraryRowHover($(this).data('row'),false)});
}

function updateFormPieces() {
	var container = $("#piecesPlayed");
	var selectedPieces = $(".guildies_play_permanent")
	.not("#guildies_play_proxy_row")
	.not("#guildies_play_search_row")
	.not("#guildies_play_title_row");
	
	container.html("");
	for (var i=0;i<selectedPieces.length;i++) {
		var newElem = $(document.createElement("input"));
		newElem.attr('type','hidden').attr('name','pieces[]').attr('value',selectedPieces.eq(i).attr('tag'));
		container.append(newElem);
	}
}

$(document).ready(function () {
	
	// Bind Events for Guildie Edit
	if ($("#guildies_play_proxy_row")) {
		var selectedPieces = $(".guildies_play_permanent")
		.not("#guildies_play_proxy_row")
		.not("#guildies_play_search_row")
		.not("#guildies_play_title_row");
		for (var i=0;i<selectedPieces.length;i++) {
			libraryBindRow(selectedPieces.eq(i));
		}
	}
	
	if ($(".recording_table")) {
		runRecording();
	}
	
	
	var isNewsPage = $("#news_entry_table").attr('id') == "news_entry_table";
	
	
	var uploadScript = isNewsPage?'news/uploadimage':'guildies/uploadimage';
	var uploadPath = isNewsPage?"assets/images/NewsPhotos/":"assets/images/GuildPhotos/";
	//Bind Events for Guildie Image Upload
	if ($("#guildie_image_uploader").attr('id') == "guildie_image_uploader") {
	
		var myUpload = $("#guildie_image_uploader_overlay").upload({
		        name: 'file',
		        action: baseURL+uploadScript,
		        enctype: 'multipart/form-data',
		        params: {},
		        autoSubmit: true,
		        onSubmit: function() {},
		        onComplete: function(response) {
		       
			       	if (response.length == 0) {
			       		 alert("Your image must be less than 1600 x 1600 pixels.");
			       	} else {
			       
			        	var name = response;		        	
			        	var urlString = "url('" + baseURL.replace("index.php/","") + uploadPath + name + "')";
			        	$("#guildie_image_uploader").css("background-image",urlString);
			        	$("#photoInput").attr('value',name);
			        }
		        },
		        onSelect: function() {},
		        //Added Functions to library
		        mouseleft: function () {
		        	$("#guildie_image_uploader_overlay").animate({opacity:0, duration:100});
		        },
		        mouseentered: function () {
		        	$("#guildie_image_uploader_overlay").animate({opacity:1.0,duration:100});
		        }
		});
	}
	
	
	
	if ($("#sheet_image_upload_button").attr('id') == "sheet_image_upload_button") {
		
		var theID = window.location.href.replace(/^.*\/|\.[^.]*$/g, '');
		var uploadScript = 'library/uploadSheet?id='+theID;
		var myUpload = $("#sheet_image_upload_button").upload({
		        name: 'file',
		        action: baseURL+uploadScript,
		        enctype: 'multipart/form-data',
		        params: {},
		        autoSubmit: true,
		        onSubmit: function() {},
		        onComplete: function(response) {
		       
			       	if (response.length == 0) {
			       		 alert("You did not select a valid PDF");
			       	} else {
			       
			        	//var name = response;		        	
			        	//var urlString = "url('" + baseURL.replace("index.php/","") + uploadPath + name + "')";
			        	//$("#guildie_image_uploader").css("background-image",urlString);
			        	//$("#sheet_image_upload_button").attr('id').attr('value',name);
			        	window.location = window.location;
			        }
		        },
		        onSelect: function() {},
		        //Added Functions to library
		        mouseleft: function () {
		        	//$("#guildie_image_uploader_overlay").animate({opacity:0, duration:100});
		        },
		        mouseentered: function () {
		        	//$("#guildie_image_uploader_overlay").animate({opacity:1.0,duration:100});
		        }
		});
	
	}
	
	
	
	
});


function guildieSearch(query) {
	var complete = function (data) {
		var rows = $(".guildie_results_row");
		if (data) {
			
			rows.css("display","none");
			for (var i=0;i<rows.length;i++) {
				for (var p=0;p<data.length;p++) {
					if (rows.eq(i).attr('tag') == data[p].id) {
						rows.eq(i).css("display","");
						break;
					}
				}
			}	
		} else {
			rows.css("display","");
		}
	}

	AJAXQueryPostJSON("guildies",["search"],{"query":query},complete,false,false,false);
	
}


/* End Guildies Functions */



/* Recordings Scripts */


function runRecording() {
	$("#recordings_edit_search_table").css('opacity',0);
}


function categorizeRecording() {
	var searchTable = $("#recordings_edit_search_table");
	var searchBox = $("#recordings_edit_search_table input")
	
	
	var visible = searchTable.data("visible");
	
	
	var animateRow = function (vis) {
		if (!vis) searchTable.css('display', '');
		searchTable.animate({opacity:vis?0:1}, 200, function () {
			if (vis) $(this).css('display', 'none');
			
			searchBox.animate({opacity:1.0}, 100);
			
		});
		searchTable.data("visible",!vis);
	};
	
	animateRow(visible);
	/*
	if (visible) {
		searchBox.animate({opacity:0}, 100, function () {
			animateRow(visible);
		});
	} else {
		searchBox.css('opacity',0);
		animateRow(visible);
	}*/
	
}

function recordingCategorySearchForPieces(query) {
	var proxy = $("#recordings_edit_search_proxy_row");
	var searchRow = $("#recordings_edit_search_row");
	var table = $("#recordings_edit_search_table");
	
	
	if (query == "") {
		table.children("tbody").children("tr").not(".recordings_row_permanent").not("#recordings_edit_search_proxy_row").remove();
		return;
	}
	
	var complete = function (rowData) { 
		
		//Clear rows
		table.children("tbody").children("tr").not(".recordings_row_permanent").not("#recordings_edit_search_proxy_row").remove();
		
		for (var i=0;i<rowData.length;i++) {
			
			var newRow = proxy.clone();
			
			newRow.attr('tag',rowData[i].id);
			
			loadLibraryRowWithData(newRow,rowData[i]);
			recordingCategoryBindRow(newRow);
			
			
			newRow.css("display","");
			
			newRow.removeClass("lightRow");
			if (i%2==1) newRow.addClass("lightRow");
			newRow.attr("id","");
			
			table.append(newRow);
			
		}
	}
	
	AJAXQueryPostJSON("library",["search"],{"limit":kSearchLimit,"query":query},complete,false,false,false);
}

function recordingCategoryBindRow(row) {
	row.unbind().bind('mouseover',function () {
		row.addClass('recordings_edit_search_result_hover');
	})
	.bind('mouseout',function () {
		row.removeClass('recordings_edit_search_result_hover');
	}).click(function () {
		var tag = row.attr('tag');
		if (!tag) tag = 0;
		
		var input = $("#path");
		var guildie = $("#guildie");
		if (guildie.length > 0) {
			guildie = guildie.attr('value');
		} else {
			guildie = '0';
		}
		
		var pieceinput = $("#pieceinput");
		
		var url = 'recordings/recategorize?oldpath='+ encodeURIComponent(input.attr('value'))+"&library="+tag+"&guildie="+guildie+"&name="+ encodeURIComponent(pieceinput?pieceinput.val():"");
		
		go(url);
		//var data = libraryRowData(row);
		//var indicatorRow = $("#recording_selection_table_firstrow");
		//loadLibraryRowWithData(indicatorRow,data);
		//indicatorRow.attr('tag',row.attr('tag'));
		//$("#recordings_edit_search_row").find('input').attr('value','');
		//recordingCategorySearchForPieces('');
	});
}



//RECORDING SEARCH
function recordingSearch(query) {
	
	//var rowData = [{title:"Test"},{title:"New Piece", composer:"Stephen", year:"1909"}];
	var proxy = $("#libraryRowProxy").clone();
	var table = $("#libraryEntries");
	
	
	var complete = function (rowData) { 
		//Clear rows
		table.html("");
		
		for (var i=0;i<rowData.length;i++) {
			var newRow = proxy.clone();			
			loadLibraryRowWithData(newRow,rowData[i]);
			
			//Re-bind click handler
			
			newRow.attr('tag',rowData[i].id);
			
			newRow.removeClass("lightRow");
			if (i%2==1) newRow.addClass("lightRow");
			
			table.append(newRow);
		}
	}
	
	AJAXQueryPostJSON("library",["search"],{"query":query},complete,false,false,false);	
}

function recordingRowWithData(row,data) {
	row.find(".library_data_title").html(data.title);
	row.find(".library_data_composer").html(data.composer);
	row.find(".library_data_genre").html(data.genre);
	row.find(".library_data_year").html(data.year);
}
function recordingRowData(row) {
	var title = row.find(".library_data_title").html();
	var composer = row.find(".library_data_composer").html();
	var genre = row.find(".library_data_genre").html();
	var year = row.find(".library_data_year").html();
	return {title:title, composer:composer, genre:genre, year:year};
}

/* End Recording Functions */

/* Begin News Functions */


function newsSearch(query) {
	//var rowData = [{title:"Test"},{title:"New Piece", composer:"Stephen", year:"1909"}];
	var proxy = $("#newsRowProxy").clone();
	
	var table = $("#newsEntries");
	
	
	var complete = function (rowData) { 
		//Clear rows
		table.html('');
		
		for (var i=0;i<rowData.length;i++) {
			var newRow = proxy.clone();			
			loadNewsRowWithData(newRow,rowData[i]);
			
			//Re-bind click handler
			newRow.css('opacity',1);
			newRow.attr('tag',rowData[i].id);
			
			if (i>0) newRow.attr('id','');
			
			newRow.removeClass("lightRow");
			if (i%2==1) newRow.addClass("lightRow");
			
			table.append(newRow);
		}
		
		if (rowData.length == 0) {
			proxy.css('opacity',0);
			table.append(proxy);
		}
		
		$("#newsEntryCount").html(rowData.length + " Result"+(rowData.length==1?"":"s"));
		
		if (query == "") $("#newsEntryCount").html(rowData.length + " News Stor"+(rowData.length==1?"y":"ies"));
		
	}
	
	AJAXQueryPostJSON("news",["search"],{"query":query},complete,false,false,false);	
}


function loadNewsRowWithData(row,data) {
	row.find(".news_title").html(data.title);
	row.find(".news_content").html(data.content);
	row.find(".news_author").html(data.author);
	row.find(".news_date").html(data.date);
	row.find(".news_photo").css('background-image',"url(\"../assets/images/NewsPhotos/"+data.thumb+"\")");
}
function newsRowData(row) {
	var title = row.find(".library_data_title").html();
	var composer = row.find(".library_data_composer").html();
	var genre = row.find(".library_data_genre").html();
	var year = row.find(".library_data_year").html();
	return {title:title, composer:composer, genre:genre, year:year};
}

/* End News Functions */



/* AJAX Helper Functions */

function AJAXQueryParametersWithKeysAndValues() {
	if (arguments.length % 2 != 0) {BLog("Queries Require an equal Number of Keys and Values"); return;}
	var newArray = [];
	
	for (var i=0;i<arguments.length;i+=2) {
		newArray.splice(0,0,{key:arguments[i],value:arguments[i+1]});
	}
	return newArray;
}
function AJAXGetParameterString(params) {
	var getString="";
	for (var i=0;i<params.length;i++) {
		getString+=(i==0?"?":"&");
		getString += (params[i].key+"="+params[i].value);
	}
	return getString;
}
function AJAXJSONParameterString(params) {
	var getString="";
	for (var i=0;i<params.length;i++) {
		getString+="/";
		getString += params[i];
	}
	return getString;
}

function AJAXQueryScriptWithCompletionHandlerAndParameters(script, completion, params,error) {
	
	var getString = AJAXGetParameterString(params);

	$.get(script+getString, function(data){
		completion(data);
	}).error(function (data) {
		if (error) error();
	});
}

function AJAXQueryGetJSON(dbScript,params,completion,delegate,userInfo,error) {
	var getString = AJAXJSONParameterString(params);
	var req = $.getJSON(baseURL+dbScript+getString, function(data) {
		if (!userInfo) userInfo = false;
		if (delegate) {
			delegate.completion(data,userInfo);
		} else {
			completion(data,userInfo);
		}
	});
	if (error) {
		req.error(function () {
			if (delegate) {
				delegate.error(userInfo);
			} else {
				error(userInfo);
			}
		});
	}
}

function AJAXQueryPostJSON(dbScript,params,postData,completion,delegate,userInfo,error) {
	var getString = AJAXJSONParameterString(params);
	
	//alert("Requesting URL: " + baseURL+dbScript+getString);
	//alert("postdata " + postData);
	
	//BLog('Sending: %@,%@', getString, JSON.stringify(postData));
	
	var req = $.post(baseURL+dbScript+getString, postData, function(data) {
		//BLog("RAW: %@,%@",data,getString);
		if (data.length > 0) {
			//alert(data);
			data = JSON.parse(data);
		}
		if (delegate) {
			delegate.completion(data,userInfo);
		} else {
			completion(data,userInfo);
		}
	});
	if (error) {
		req.error(function () {
			if (delegate) {
				delegate.error(userInfo);
			} else {
				error(userInfo);
			}
		});
	}
	
	return req;
}
/* End AJAX Helper Functions */


