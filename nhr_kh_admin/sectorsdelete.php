<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sectorsinfo.php" ?>
<?php include_once "admin_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sectors_delete = NULL; // Initialize page object first

class csectors_delete extends csectors {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{2740043E-02B0-4E15-B10B-02037850EEC7}";

	// Table name
	var $TableName = 'sectors';

	// Page object name
	var $PageObjName = 'sectors_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (sectors)
		if (!isset($GLOBALS["sectors"]) || get_class($GLOBALS["sectors"]) == "csectors") {
			$GLOBALS["sectors"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sectors"];
		}

		// Table object (admin_users)
		if (!isset($GLOBALS['admin_users'])) $GLOBALS['admin_users'] = new cadmin_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sectors', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (admin_users)
		if (!isset($UserTable)) {
			$UserTable = new cadmin_users();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("sectorslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $sectors;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sectors);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("sectorslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in sectors class, sectorsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->sector_title->setDbValue($rs->fields('sector_title'));
		$this->sector_brief->setDbValue($rs->fields('sector_brief'));
		$this->sector_details->setDbValue($rs->fields('sector_details'));
		$this->sector_icon->Upload->DbValue = $rs->fields('sector_icon');
		$this->sector_icon->CurrentValue = $this->sector_icon->Upload->DbValue;
		$this->sector_pic->Upload->DbValue = $rs->fields('sector_pic');
		$this->sector_pic->CurrentValue = $this->sector_pic->Upload->DbValue;
		$this->sector_keywrds->setDbValue($rs->fields('sector_keywrds'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->sector_title->DbValue = $row['sector_title'];
		$this->sector_brief->DbValue = $row['sector_brief'];
		$this->sector_details->DbValue = $row['sector_details'];
		$this->sector_icon->Upload->DbValue = $row['sector_icon'];
		$this->sector_pic->Upload->DbValue = $row['sector_pic'];
		$this->sector_keywrds->DbValue = $row['sector_keywrds'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// sector_title
		// sector_brief
		// sector_details
		// sector_icon
		// sector_pic
		// sector_keywrds

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// sector_title
		$this->sector_title->ViewValue = $this->sector_title->CurrentValue;
		$this->sector_title->ViewCustomAttributes = "";

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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// sector_title
			$this->sector_title->LinkCustomAttributes = "";
			$this->sector_title->HrefValue = "";
			$this->sector_title->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "sectorslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($sectors_delete)) $sectors_delete = new csectors_delete();

// Page init
$sectors_delete->Page_Init();

// Page main
$sectors_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sectors_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsectorsdelete = new ew_Form("fsectorsdelete", "delete");

// Form_CustomValidate event
fsectorsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsectorsdelete.ValidateRequired = true;
<?php } else { ?>
fsectorsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($sectors_delete->Recordset = $sectors_delete->LoadRecordset())
	$sectors_deleteTotalRecs = $sectors_delete->Recordset->RecordCount(); // Get record count
if ($sectors_deleteTotalRecs <= 0) { // No record found, exit
	if ($sectors_delete->Recordset)
		$sectors_delete->Recordset->Close();
	$sectors_delete->Page_Terminate("sectorslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sectors_delete->ShowPageHeader(); ?>
<?php
$sectors_delete->ShowMessage();
?>
<form name="fsectorsdelete" id="fsectorsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sectors_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sectors_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sectors">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($sectors_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $sectors->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($sectors->id->Visible) { // id ?>
		<th><span id="elh_sectors_id" class="sectors_id"><?php echo $sectors->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sectors->sector_title->Visible) { // sector_title ?>
		<th><span id="elh_sectors_sector_title" class="sectors_sector_title"><?php echo $sectors->sector_title->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sectors->sector_icon->Visible) { // sector_icon ?>
		<th><span id="elh_sectors_sector_icon" class="sectors_sector_icon"><?php echo $sectors->sector_icon->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sectors->sector_pic->Visible) { // sector_pic ?>
		<th><span id="elh_sectors_sector_pic" class="sectors_sector_pic"><?php echo $sectors->sector_pic->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$sectors_delete->RecCnt = 0;
$i = 0;
while (!$sectors_delete->Recordset->EOF) {
	$sectors_delete->RecCnt++;
	$sectors_delete->RowCnt++;

	// Set row properties
	$sectors->ResetAttrs();
	$sectors->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$sectors_delete->LoadRowValues($sectors_delete->Recordset);

	// Render row
	$sectors_delete->RenderRow();
?>
	<tr<?php echo $sectors->RowAttributes() ?>>
<?php if ($sectors->id->Visible) { // id ?>
		<td<?php echo $sectors->id->CellAttributes() ?>>
<span id="el<?php echo $sectors_delete->RowCnt ?>_sectors_id" class="sectors_id">
<span<?php echo $sectors->id->ViewAttributes() ?>>
<?php echo $sectors->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sectors->sector_title->Visible) { // sector_title ?>
		<td<?php echo $sectors->sector_title->CellAttributes() ?>>
<span id="el<?php echo $sectors_delete->RowCnt ?>_sectors_sector_title" class="sectors_sector_title">
<span<?php echo $sectors->sector_title->ViewAttributes() ?>>
<?php echo $sectors->sector_title->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sectors->sector_icon->Visible) { // sector_icon ?>
		<td<?php echo $sectors->sector_icon->CellAttributes() ?>>
<span id="el<?php echo $sectors_delete->RowCnt ?>_sectors_sector_icon" class="sectors_sector_icon">
<span>
<?php echo ew_GetFileViewTag($sectors->sector_icon, $sectors->sector_icon->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($sectors->sector_pic->Visible) { // sector_pic ?>
		<td<?php echo $sectors->sector_pic->CellAttributes() ?>>
<span id="el<?php echo $sectors_delete->RowCnt ?>_sectors_sector_pic" class="sectors_sector_pic">
<span>
<?php echo ew_GetFileViewTag($sectors->sector_pic, $sectors->sector_pic->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$sectors_delete->Recordset->MoveNext();
}
$sectors_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sectors_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsectorsdelete.Init();
</script>
<?php
$sectors_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sectors_delete->Page_Terminate();
?>
