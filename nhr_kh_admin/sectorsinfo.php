<?php

// Global variable for table object
$sectors = NULL;

//
// Table class for sectors
//
class csectors extends cTable {
	var $id;
	var $sector_title;
	var $sector_brief;
	var $sector_details;
	var $sector_icon;
	var $sector_pic;
	var $sector_keywrds;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'sectors';
		$this->TableName = 'sectors';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`sectors`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('sectors', 'sectors', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// sector_title
		$this->sector_title = new cField('sectors', 'sectors', 'x_sector_title', 'sector_title', '`sector_title`', '`sector_title`', 200, -1, FALSE, '`sector_title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['sector_title'] = &$this->sector_title;

		// sector_brief
		$this->sector_brief = new cField('sectors', 'sectors', 'x_sector_brief', 'sector_brief', '`sector_brief`', '`sector_brief`', 201, -1, FALSE, '`sector_brief`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['sector_brief'] = &$this->sector_brief;

		// sector_details
		$this->sector_details = new cField('sectors', 'sectors', 'x_sector_details', 'sector_details', '`sector_details`', '`sector_details`', 201, -1, FALSE, '`sector_details`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['sector_details'] = &$this->sector_details;

		// sector_icon
		$this->sector_icon = new cField('sectors', 'sectors', 'x_sector_icon', 'sector_icon', '`sector_icon`', '`sector_icon`', 200, -1, TRUE, '`sector_icon`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->fields['sector_icon'] = &$this->sector_icon;

		// sector_pic
		$this->sector_pic = new cField('sectors', 'sectors', 'x_sector_pic', 'sector_pic', '`sector_pic`', '`sector_pic`', 200, -1, TRUE, '`sector_pic`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->fields['sector_pic'] = &$this->sector_pic;

		// sector_keywrds
		$this->sector_keywrds = new cField('sectors', 'sectors', 'x_sector_keywrds', 'sector_keywrds', '`sector_keywrds`', '`sector_keywrds`', 201, -1, FALSE, '`sector_keywrds`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['sector_keywrds'] = &$this->sector_keywrds;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`sectors`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "sectorslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "sectorslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("sectorsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("sectorsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "sectorsadd.php?" . $this->UrlParm($parm);
		else
			$url = "sectorsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("sectorsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("sectorsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("sectorsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			$arKeys[] = $isPost ? ew_StripSlashes(@$_POST["id"]) : ew_StripSlashes(@$_GET["id"]); // id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->sector_title->setDbValue($rs->fields('sector_title'));
		$this->sector_brief->setDbValue($rs->fields('sector_brief'));
		$this->sector_details->setDbValue($rs->fields('sector_details'));
		$this->sector_icon->Upload->DbValue = $rs->fields('sector_icon');
		$this->sector_pic->Upload->DbValue = $rs->fields('sector_pic');
		$this->sector_keywrds->setDbValue($rs->fields('sector_keywrds'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// sector_title
		// sector_brief
		// sector_details
		// sector_icon
		// sector_pic
		// sector_keywrds
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// sector_title
		$this->sector_title->ViewValue = $this->sector_title->CurrentValue;
		$this->sector_title->ViewCustomAttributes = "";

		// sector_brief
		$this->sector_brief->ViewValue = $this->sector_brief->CurrentValue;
		$this->sector_brief->ViewCustomAttributes = "";

		// sector_details
		$this->sector_details->ViewValue = $this->sector_details->CurrentValue;
		$this->sector_details->ViewCustomAttributes = "";

		// sector_icon
		if (!ew_Empty($this->sector_icon->Upload->DbValue)) {
			$this->sector_icon->ImageAlt = $this->sector_icon->FldAlt();
			$this->sector_icon->ViewValue = $this->sector_icon->Upload->DbValue;
		} else {
			$this->sector_icon->ViewValue = "";
		}
		$this->sector_icon->ViewCustomAttributes = "";

		// sector_pic
		if (!ew_Empty($this->sector_pic->Upload->DbValue)) {
			$this->sector_pic->ImageAlt = $this->sector_pic->FldAlt();
			$this->sector_pic->ViewValue = $this->sector_pic->Upload->DbValue;
		} else {
			$this->sector_pic->ViewValue = "";
		}
		$this->sector_pic->ViewCustomAttributes = "";

		// sector_keywrds
		$this->sector_keywrds->ViewValue = $this->sector_keywrds->CurrentValue;
		$this->sector_keywrds->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// sector_title
		$this->sector_title->LinkCustomAttributes = "";
		$this->sector_title->HrefValue = "";
		$this->sector_title->TooltipValue = "";

		// sector_brief
		$this->sector_brief->LinkCustomAttributes = "";
		$this->sector_brief->HrefValue = "";
		$this->sector_brief->TooltipValue = "";

		// sector_details
		$this->sector_details->LinkCustomAttributes = "";
		$this->sector_details->HrefValue = "";
		$this->sector_details->TooltipValue = "";

		// sector_icon
		$this->sector_icon->LinkCustomAttributes = "";
		if (!ew_Empty($this->sector_icon->Upload->DbValue)) {
			$this->sector_icon->HrefValue = ew_GetFileUploadUrl($this->sector_icon, $this->sector_icon->Upload->DbValue); // Add prefix/suffix
			$this->sector_icon->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->sector_icon->HrefValue = ew_ConvertFullUrl($this->sector_icon->HrefValue);
		} else {
			$this->sector_icon->HrefValue = "";
		}
		$this->sector_icon->HrefValue2 = $this->sector_icon->UploadPath . $this->sector_icon->Upload->DbValue;
		$this->sector_icon->TooltipValue = "";
		if ($this->sector_icon->UseColorbox) {
			$this->sector_icon->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->sector_icon->LinkAttrs["data-rel"] = "sectors_x_sector_icon";

			//$this->sector_icon->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
			//$this->sector_icon->LinkAttrs["data-placement"] = "bottom";
			//$this->sector_icon->LinkAttrs["data-container"] = "body";

			$this->sector_icon->LinkAttrs["class"] = "ewLightbox img-thumbnail";
		}

		// sector_pic
		$this->sector_pic->LinkCustomAttributes = "";
		if (!ew_Empty($this->sector_pic->Upload->DbValue)) {
			$this->sector_pic->HrefValue = ew_GetFileUploadUrl($this->sector_pic, $this->sector_pic->Upload->DbValue); // Add prefix/suffix
			$this->sector_pic->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->sector_pic->HrefValue = ew_ConvertFullUrl($this->sector_pic->HrefValue);
		} else {
			$this->sector_pic->HrefValue = "";
		}
		$this->sector_pic->HrefValue2 = $this->sector_pic->UploadPath . $this->sector_pic->Upload->DbValue;
		$this->sector_pic->TooltipValue = "";
		if ($this->sector_pic->UseColorbox) {
			$this->sector_pic->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->sector_pic->LinkAttrs["data-rel"] = "sectors_x_sector_pic";

			//$this->sector_pic->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
			//$this->sector_pic->LinkAttrs["data-placement"] = "bottom";
			//$this->sector_pic->LinkAttrs["data-container"] = "body";

			$this->sector_pic->LinkAttrs["class"] = "ewLightbox img-thumbnail";
		}

		// sector_keywrds
		$this->sector_keywrds->LinkCustomAttributes = "";
		$this->sector_keywrds->HrefValue = "";
		$this->sector_keywrds->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// sector_title
		$this->sector_title->EditAttrs["class"] = "form-control";
		$this->sector_title->EditCustomAttributes = "";
		$this->sector_title->EditValue = $this->sector_title->CurrentValue;
		$this->sector_title->PlaceHolder = ew_RemoveHtml($this->sector_title->FldCaption());

		// sector_brief
		$this->sector_brief->EditAttrs["class"] = "form-control";
		$this->sector_brief->EditCustomAttributes = "";
		$this->sector_brief->EditValue = $this->sector_brief->CurrentValue;
		$this->sector_brief->PlaceHolder = ew_RemoveHtml($this->sector_brief->FldCaption());

		// sector_details
		$this->sector_details->EditAttrs["class"] = "form-control";
		$this->sector_details->EditCustomAttributes = "";
		$this->sector_details->EditValue = $this->sector_details->CurrentValue;
		$this->sector_details->PlaceHolder = ew_RemoveHtml($this->sector_details->FldCaption());

		// sector_icon
		$this->sector_icon->EditAttrs["class"] = "form-control";
		$this->sector_icon->EditCustomAttributes = "";
		if (!ew_Empty($this->sector_icon->Upload->DbValue)) {
			$this->sector_icon->ImageAlt = $this->sector_icon->FldAlt();
			$this->sector_icon->EditValue = $this->sector_icon->Upload->DbValue;
		} else {
			$this->sector_icon->EditValue = "";
		}
		if (!ew_Empty($this->sector_icon->CurrentValue))
			$this->sector_icon->Upload->FileName = $this->sector_icon->CurrentValue;

		// sector_pic
		$this->sector_pic->EditAttrs["class"] = "form-control";
		$this->sector_pic->EditCustomAttributes = "";
		if (!ew_Empty($this->sector_pic->Upload->DbValue)) {
			$this->sector_pic->ImageAlt = $this->sector_pic->FldAlt();
			$this->sector_pic->EditValue = $this->sector_pic->Upload->DbValue;
		} else {
			$this->sector_pic->EditValue = "";
		}
		if (!ew_Empty($this->sector_pic->CurrentValue))
			$this->sector_pic->Upload->FileName = $this->sector_pic->CurrentValue;

		// sector_keywrds
		$this->sector_keywrds->EditAttrs["class"] = "form-control";
		$this->sector_keywrds->EditCustomAttributes = "";
		$this->sector_keywrds->EditValue = $this->sector_keywrds->CurrentValue;
		$this->sector_keywrds->PlaceHolder = ew_RemoveHtml($this->sector_keywrds->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->sector_title->Exportable) $Doc->ExportCaption($this->sector_title);
					if ($this->sector_brief->Exportable) $Doc->ExportCaption($this->sector_brief);
					if ($this->sector_details->Exportable) $Doc->ExportCaption($this->sector_details);
					if ($this->sector_icon->Exportable) $Doc->ExportCaption($this->sector_icon);
					if ($this->sector_pic->Exportable) $Doc->ExportCaption($this->sector_pic);
					if ($this->sector_keywrds->Exportable) $Doc->ExportCaption($this->sector_keywrds);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->sector_title->Exportable) $Doc->ExportCaption($this->sector_title);
					if ($this->sector_icon->Exportable) $Doc->ExportCaption($this->sector_icon);
					if ($this->sector_pic->Exportable) $Doc->ExportCaption($this->sector_pic);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->sector_title->Exportable) $Doc->ExportField($this->sector_title);
						if ($this->sector_brief->Exportable) $Doc->ExportField($this->sector_brief);
						if ($this->sector_details->Exportable) $Doc->ExportField($this->sector_details);
						if ($this->sector_icon->Exportable) $Doc->ExportField($this->sector_icon);
						if ($this->sector_pic->Exportable) $Doc->ExportField($this->sector_pic);
						if ($this->sector_keywrds->Exportable) $Doc->ExportField($this->sector_keywrds);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->sector_title->Exportable) $Doc->ExportField($this->sector_title);
						if ($this->sector_icon->Exportable) $Doc->ExportField($this->sector_icon);
						if ($this->sector_pic->Exportable) $Doc->ExportField($this->sector_pic);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
